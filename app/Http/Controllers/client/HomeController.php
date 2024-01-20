<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\admin\CompetitionController;
use App\Http\Controllers\admin\MatchController;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateClub;
use App\Jobs\UpdateCompetitionJob;
use App\Jobs\UpdateMatchJob;
use App\Jobs\UpdateSeatJob;
use App\Models\Club;
use App\Models\Competition;
use App\Models\FootballMatch;
use App\Models\Potential;
use App\Models\PotentialCustomer;
use App\Models\Seat_rows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\IsEmpty;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $competitionController;
    protected $matchController;

    public function create()
    {
        return view('auth.login');
    }
    public function __construct(CompetitionController $competitionController, MatchController $matchController)
    {
        $this->competitionController = $competitionController;
        $this->matchController = $matchController;
    }

    public function index()
    {
        $matches = FootballMatch::all();
        $competitions = Competition::all();
        $clubs = Club::all();
        // Kiểm tra và dispatch job cập nhật competition nếu không có competition
        if ($competitions->isEmpty()) {
            UpdateCompetitionJob::dispatch()->onQueue('high');
        }

        // Dispatch job cập nhật match cho mỗi competition
        UpdateMatchJob::dispatch()->onQueue('high');

        // Kiểm tra và dispatch job cập nhật seat nếu có match có seat khác 0
        if ($matches->where('seat', '=', 0)->isNotEmpty()) {
            UpdateSeatJob::dispatch()->onQueue('high');
        }
        $countClubs = $clubs->count();

        // if ($countClubs<200) {
        //     UpdateClub::dispatch();
        // }
        // else{
        //     UpdateClub::dispatch()->onQueue('low')->delay(now()->addMinutes(10));

        // }
        return view('client.index', [
            'matches' => $matches,
            'competitions' => $competitions
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->input('query');

        $competitionSuggestions = Competition::where('name_of_competition', 'like', '%' . $query . '%')->get();
        $clubSuggestions = Club::where('name', 'like', '%' . $query . '%')->get();


        $suggestions = $competitionSuggestions->merge($clubSuggestions);

        return response()->json($suggestions);
    }

    public function competitionDetail($id)
    {
        $competition = Competition::where('id', $id)->first();
        $competitions = Competition::all();
        return view('client.competition.competition-detail', [
            'competition' => $competition,
            'competitions' => $competitions
        ]);
    }
    public function matchSeat($id)
    {
        $match = FootballMatch::where('match_id', $id)->first();
        $matches = FootballMatch::get();
        return view('client.sport.matches', [
            'match' => $match,
            'matches' => $matches,
        ]);
    }
    public function seatPlan($id)
    {
        $match = FootballMatch::where('match_id', $id)->first();

        if ($match->seat == 0) {
            return back()->with('msg', 'Xin lỗi chúng tôi chưa có vé cho trận đấu này hoặc đã hết vé.');
        }
        if ($match->date_time < now()->subMinutes(30)) {
            return back()->with('msg', 'Sorry, ticket sales for this match have ended.');
        }
        return view('client.ticket.match_ticket', [
            'match' => $match,
        ]);
    }
    public function findIndex($seats, $seatNum)
    {
        for ($i = 0; $i < count($seats); $i++) {
            $currentSeatNum = (int) (substr($seats[$i], 1));
            if ($currentSeatNum == $seatNum) {
                return $i;
            }
        }
        return -1;
    }
    public function checkout(Request $request, $id)
    {
        if (empty($request->seats)) {
            return back()->with('error', "không hợp lệ, không được để trống ghế");
        }
        $seats = json_decode(request('seats') ?? old('seats'), true); //A1 2 3 , 7 8
        // dd($seats);
        $seat_rows = Seat_rows::where('match_id', $id)->first();
        $request_seats = [];
        foreach ($seats as $seat) {
            $request_seats[] = $seat['seatNum'];
        }
        $seatUnAvailabel = json_decode($seat_rows->seat_name)->unavailable;
        $allSeats = array_merge($request_seats, $seatUnAvailabel);
        sort($allSeats);

        // dd($allSeats);
        $totalPrice = request('totalPrice') ?? old('totalPrice');
        $maxSeatCount = (int) ($request->maxSeatCount);
        $temp = Seat_rows::where('match_id', $id)->get();
        $lastSeatArray = [];
        foreach ($temp as $value) {
            $lastSeatArray[$value->name] = $value->total_seats;

        }
        for ($i = 0; $i < count($allSeats); ++$i) {
            $current_seat_name = (substr($allSeats[$i], 0, 1));
            $lastSeatNum = $lastSeatArray[$current_seat_name];
            $lastSeatIndex = $this->findIndex($allSeats, $lastSeatNum);
            $current_seat_num = (int) (substr($allSeats[$i], 1));
            $current_seat_row = floor($current_seat_num / $maxSeatCount) + 1;
            $left_most_seat_num = ($current_seat_row - 1) * $maxSeatCount + 1;
            $left_seat_index = $this->findIndex($allSeats, $left_most_seat_num);
            $right_most_seat_num = ($current_seat_row) * $maxSeatCount;
            $right_seat_index = $this->findIndex($allSeats, $right_most_seat_num);

            if ($left_seat_index == -1 && $current_seat_num - $left_most_seat_num == 1) {
                session()->flash('seats', $seats);
                session()->flash('totalPrice', $totalPrice);
                return redirect()->route('seat.plan', ['id' => $request->matchId])
                    ->with('error', "không hợp lệ, không được để trống ghế $current_seat_name$left_most_seat_num ");
            }

            if (($right_seat_index == -1 && $current_seat_num + 1 == $right_most_seat_num)) {
                session()->flash('seats', $seats);
                session()->flash('totalPrice', $totalPrice);
                return redirect()->route('seat.plan', ['id' => $request->matchId])
                    ->with('error', "không hợp lệ, không được để trống ghế $current_seat_name$right_most_seat_num ");
            }

            if (($lastSeatIndex == -1 && $current_seat_num + 1 == $lastSeatNum)) {
                session()->flash('seats', $seats);
                session()->flash('totalPrice', $totalPrice);
                return redirect()->route('seat.plan', ['id' => $request->matchId])
                    ->with('error', "không hợp lệ, không được để trống ghế $current_seat_name$lastSeatNum ");
            }
            if ($i + 1 < count($allSeats)) {
                $next_seat_num = (int) (substr($allSeats[$i + 1], 1));
                $next_seat_row = floor($next_seat_num / $maxSeatCount) + 1;
                if ($current_seat_row == $next_seat_row && $current_seat_num + 2 == $next_seat_num) {
                    $error_seat_num = $current_seat_num + 1;
                    session()->flash('seats', $seats);
                    session()->flash('totalPrice', $totalPrice);
                    return redirect()->route('seat.plan', ['id' => $request->matchId])
                        ->with('error', "không hợp lệ, không được để trống ghế $current_seat_name$error_seat_num ");
                }
            }
        }
        return view('client.checkout.checkout')->with('seats', $seats)->with('totalPrice', $totalPrice)->with('seat_row', $temp);
    }

    public function potential(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
        PotentialCustomer::updateOrCreate(
            [
                'email' => $request->email,
            ],
            [
                'email' => $request->email,
            ]
        );
        return redirect()->back()->with('msg', 'Chúng tôi sẽ thông báo khi có sự kiện');
    }

}
