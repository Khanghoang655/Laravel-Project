<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seat_rows;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

            $matches = FootballMatch::whereIn('id', $orderItems)->get();
            $matchJson = [];
            foreach ($matches as $footballMatch) {
                $match = [
                    'name' => $footballMatch->home_team . ' - ' . $footballMatch->away_team,
                    'date' => $footballMatch->date_time
                ];
                // $match['name'] =   ;
                // $match['date'] = ;
                $matchJson[] = $match;
            }
            return view('admin.dashboard.guest_dashboard')->with([
                'orders' => $orders,
                'matchJson' => $matchJson,
                'matches' => $matches,
            ]);
        }

    }
    public function orderGuest(Request $request, $id)
    {
        $action = $request->input('action');
        $order = Order::where('id', $id)->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();
        $match = FootballMatch::where('id', $orderItem->match_id)->first();
        $currentTime = date('Y-m-d H:i:s');
        $matchTimeMinus30Minutes = date('Y-m-d H:i:s', strtotime($match->date_time . ' - 30 minutes'));
        $matchTimeMinus60Minutes = date('Y-m-d H:i:s', strtotime($match->date_time . ' - 60 minutes'));
        $orderCreatedAt = $order->created_at;
        $orderCreatedAtVN = $orderCreatedAt->setTimezone('Asia/Ho_Chi_Minh');
        $orderCreatedAtVN = Carbon::parse($order->created_at)->setTimezone('Asia/Ho_Chi_Minh');
        if ($action == 'cancel-order' && $order->status = 'success') {
            if ($orderCreatedAtVN > $matchTimeMinus30Minutes) {
                return view('admin.dashboard.guest_dashboard')->with('msg', "You can not cancel the order after 30 minutes");
            } else {
                $seatRow = Seat_rows::where('match_id', $match->id)->get();
                $newSeatAvai = [];
                foreach ($seatRow as $row) {
                    $seatAvai = json_decode($row->seat_name)->available;
                    $seatNames = explode(',', $order->seat_name);

                    // Loại bỏ khoảng trắng ở đầu và cuối mỗi giá trị
                    $seatNames = array_map('trim', $seatNames);
                    $newSeatAvai = array_merge($seatAvai, $seatNames);
                    usort($newSeatAvai, function ($a, $b) {
                        preg_match('/([a-zA-Z]+)([0-9]+)/', $a, $matchesA);
                        preg_match('/([a-zA-Z]+)([0-9]+)/', $b, $matchesB);

                        $alphaA = $matchesA[1];
                        $numA = intval($matchesA[2]);

                        $alphaB = $matchesB[1];
                        $numB = intval($matchesB[2]);

                        $alphaComparison = strcmp($alphaA, $alphaB);

                        if ($alphaComparison == 0) {
                            return $numA - $numB;
                        }

                        return $alphaComparison;
                    });
                    // dd($newSeatAvai);
                    $row->seat_name = json_encode(['available' => $newSeatAvai]);
                    $row->save();
                }

                $order->status = 'cancel';
                $order->save();
                return redirect()->route('dashboard.guest')->with('msg', "Done");
            }
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
