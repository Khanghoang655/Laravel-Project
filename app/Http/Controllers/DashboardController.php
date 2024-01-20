<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            $orderItems = [];

            $orders = Order::where('user_id', Auth::user()->id)->with('order_items')->get();
            foreach ($orders as $order) {
                foreach ($order->order_items as $orderItem) {
                    $orderItems[] = $orderItem->match_id;
                }
            }
            $match = FootballMatch::where('id', $orderItems)->get();
            return view('admin.dashboard.guest_dashboard')->with([
                'orders' => $orders,
                'match' => $match,
            ]);
        }

    }

    public function indexAdmin()
    {

        if (Auth::user()->isAdmin()) {
            $user = User::where('id', Auth::user()->id)->first();
            $datas = DB::table('order')
                ->selectRaw('status, count(*) as number')
                ->groupBy('status')
                ->get();
            $totalSuccess = DB::table('order')
                ->where('status', '=', 'success')
                ->sum('total');
            $totalUnSuccess = DB::table('order')
                ->where('status', '!=', 'success')
                ->orWhereNull('status')
                ->sum('total');
            $result = [];
            $result[] = ['Status', 'Number'];
            foreach ($datas as $data) {
                $result[] = [ucfirst($data->status), $data->number];
            }
            $dataOrderNumber = DB::table('order')
                ->selectRaw("DATE_FORMAT(created_at, '%Y%m%d') as monthYear, count(*) as number")
                ->groupBy('monthYear')
                ->get();
            $orders = Order::get();
            $resultOrderNumber = [];
            $resultOrderNumber[] = ['Month Year', 'Number'];
            foreach ($dataOrderNumber as $data) {
                $resultOrderNumber[] = [$data->monthYear, $data->number];
            }
            return view('admin.dashboard.admin_dashboard')->with([
                'user' => $user,
                'result' => $result,
                'resultOrderNumber' => $resultOrderNumber,
                'totalSuccess' => $totalSuccess,
                'totalUnSuccess' => $totalUnSuccess,
                'orders' => $orders,
            ]);
        }
    }
    public function fullOrder()
    {
        $orders = Order::paginate(10);
        return view('admin.dashboard.order')->with([
            'orders' => $orders,
        ]);
    }
    public function filterOrders(Request $request)
    {
        $query = Order::query();
        $status = $request->input('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $dateFilter = $request->input('date_filter', 'newest');
        $totalFilter = $request->input('total_filter', 'asc');

        if ($dateFilter == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($dateFilter == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        if ($totalFilter == 'asc') {
            $query->orderBy('total', 'asc');
        } elseif ($totalFilter == 'desc') {
            $query->orderBy('total', 'desc');
        }

        $filteredOrders = $query->paginate(10);

        return view('admin.dashboard.order')->with([
            'orders' => $filteredOrders,
        ]);
    }
    public function customers(Request $request)
    {
        $totalFilter = $request->input('total_filter', 'asc'); // Giá trị mặc định là 'asc'

        $users = User::withTrashed()->get();
        $userCounts = [];

        foreach ($users as $user) {
            $orders = Order::where('user_id', $user->id)->get();
            $userCount = 0;

            foreach ($orders as $order) {
                if ($order->user_id !== null) {
                    $userCount += $order->total;
                }
            }
            $userCounts[$user->id] = $userCount;
        }

        $filteredUsers = $users;

        if ($totalFilter !== 'all') {
            // Sắp xếp mảng $filteredUsers dựa trên giá trị $userCounts
            $filteredUsers = collect($filteredUsers)->sortBy(function ($user) use ($userCounts) {
                return $userCounts[$user->id];
            })->values()->all();

            // Đảo ngược mảng nếu totalFilter là desc
            if ($totalFilter === 'desc') {
                $filteredUsers = array_reverse($filteredUsers);
            }
        }

        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $pagedData = array_slice($filteredUsers, ($currentPage - 1) * $perPage, $perPage);
        $usersPaginated = new \Illuminate\Pagination\LengthAwarePaginator($pagedData, count($filteredUsers), $perPage, $currentPage);

        return view('admin.dashboard.customers')->with([
            'customers' => $usersPaginated,
            'usersPaginated' => $usersPaginated,
            'userCounts' => $userCounts,
            'totalFilter' => $totalFilter,
        ]);
    }


    public function matchController()
    {
        $matches = FootballMatch::paginate(5);
        return view('admin.dashboard.match_controller', ["matches" => $matches]);
    }
    public function role(Request $request, $id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return back()->with('error', 'Người dùng không tồn tại');
        }

        $action = $request->input('action');

        if ($action == 'set_admin') {
            $user->update(['is_admin' => 1]);
            if ($user->trashed()) {
                $user->restore();
            }
            return back()->with('success', 'Chọn quản trị viên thành công');
        } elseif ($action == 'delete') {
            $user->update(['is_admin' => 0]);
            $user->delete();
            return back()->with('success', 'Đã xóa người dùng thành công');
        }
    }

}
