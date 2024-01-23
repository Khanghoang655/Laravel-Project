<?php

namespace App\Jobs;

use App\Models\FootballMatch;
use App\Models\Seat;
use App\Models\Seat_rows;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSeatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $footballMatches = FootballMatch::get();
       

        foreach ($footballMatches as $footballMatch) {
            $seat_name = [
                'available' => [],
                'unavailable' => [],
            ];
            if ($footballMatch->seat == 0) {
                $seatNumbers = range(1, request('seat_number') ?? 100);
                foreach ($seatNumbers as $number) {
                    $seat_name['available'][] = 'A'.$number;
                }
                $seat_name_json = json_encode($seat_name);
                try {
                    $seatRow = Seat_rows::updateOrCreate(
                        ['name' => request('name') ?? 'A', 'match_id' => $footballMatch->id],
                        [
                            'name' => request('name') ?? 'A',
                            'match_id' => $footballMatch->id,
                            'seat_name' => $seat_name_json,
                        ]
                    );
                    $seat = Seat::updateOrCreate([
                        'seat_row_id' => $seatRow->id,
                    ], [
                        'seat_row_id' => $seatRow->id,
                        'seat_number' => request('seat_number') ?? 100,
                        'seat_price' => is_numeric(request('price')) ? round(request('price')) : 100,
                        'match_id' => $footballMatch->id,
                    ]);
                    $seatNumbersSum = Seat::where('match_id', $footballMatch->id)->sum('seat_number');
                    $seatRow->total_seats = (int)$seatNumbersSum;
                    $seatRow->save();
                    $totalSeatRow = Seat_rows::where('match_id', $footballMatch->id)->sum('total_seats') ?? 0;
                    FootballMatch::where('id', $footballMatch->id)->update(['seat' => $totalSeatRow]);
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }
        }
    }
}
