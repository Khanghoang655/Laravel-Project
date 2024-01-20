<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\Seat;
use App\Models\Seat_rows;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index()
    {
        $matches = FootballMatch::withTrashed()->get();
        return view('admin.seat.admin-seat', compact('matches'));
    }

    public function store(Request $request)
    {

        // Validate input data
        $request->validate([
            'seat_number' => 'required|numeric|min:10|max:1000|integer',
            'price' => 'required|numeric|min:100|integer',
            'match_id' => 'required|exists:football_matches,id',
        ]);
        $seat_name = [
            'available' => [],
            'unavailable' => [],
        ];

        for ($i = 1; $i <= $request->seat_number; ++$i) {
            $seat_name['available'][] = $request->name . $i;
        }
        $seat_name_json = json_encode($seat_name);


        try {
            // Create or update Seat record
            $seatRow = Seat_rows::updateOrCreate(
                ['name' => $request->name, 'match_id' => $request->match_id],
                [
                    'name' => $request->name,
                    'match_id' => $request->match_id,
                    'seat_name' => $seat_name_json,
                ]
            );
            $seat = Seat::updateOrCreate([
                'seat_row_id' => $seatRow->id,
            ], [
                'seat_row_id' => $seatRow->id,
                'seat_number' => $request->seat_number,
                'seat_price' => round($request->price),
                'match_id' => $request->match_id,

            ]);

            $seatNumbersSum = Seat::where('seat_row_id', $seat->id)->sum('seat_number');
            $seatRow->total_seats = $seatNumbersSum;
            $seatRow->save();
            $totalSeatRow = Seat_rows::where('match_id', $request->match_id)->sum('total_seats') ?? 0;
            FootballMatch::where('id', $request->match_id)->update(['seat' => $totalSeatRow]);


        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return redirect()->route('admin.seat.index', $seatRow->id)->with('msg', "Tạo thành công.");
    }


    public function forceDelete($id)
    {
        $seatRow = Seat_rows::where('match_id', $id)->first();
        $seat = Seat::where('match_id', $id)->first();
        $seatName = json_decode($seatRow->seat_name)->unavailable;
        if (empty($seatName)) {
            // Xóa ghế
            $seatRow->forceDelete();
            $seat->forceDelete();

            // Cập nhật total_seat trong FootballMatch
            $match = FootballMatch::find($id);  // Lấy đối tượng trận đấu
            $match->update(['seat' => 0, 'seats_remaining' => 0]);

            $matches = FootballMatch::withTrashed()->with('seat_rows')->get();

            return view('admin.seat.admin-seat', [
                'matches' => $matches,
                'msg' => "Xóa thành công",
            ]);
        } else {
            return redirect()->route('admin.seat.index')->with('msg', "Bạn không thể xóa ghế này ");
        }

        // return redirect()->route('admin.seat.index')->with('msg', "Seat not found");
    }
    protected function calculateRemainingSeats($matchId)
    {
        $match = FootballMatch::find($matchId);

        if ($match) {
            $totalSeats = $match->total_seat;
            $soldSeats = Seat::where('match_id', $matchId)->where('status', 'unavailable')->sum('seat_number');
            return max(0, $totalSeats - $soldSeats);
        }

        return 0;
    }
}
