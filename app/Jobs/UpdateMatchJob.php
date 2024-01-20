<?php

namespace App\Jobs;

use App\Models\Competition;
use App\Models\FootballMatch;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdateMatchJob implements ShouldQueue
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
        $apiKey = config('myconfig.call_api.api_key');
        $apiMatch = config('myconfig.call_api.api_match_url');
        $responseMatches = Http::withHeaders(['X-Auth-Token' => $apiKey])->get($apiMatch);

        // if (!$responseMatches->successful()) {
        //     throw new \Exception('Failed to fetch match data from the API.');
        // }

        $matches = $responseMatches->json()['matches'] ?? [];
        foreach ($matches as $match) {
            $requiredFields = ['homeTeam', 'awayTeam', 'area', 'competition'];

            if (!$this->hasRequiredFields($match, $requiredFields)) {
                continue;
            }
            $result = [
                'points_team1' => $match['score']['fullTime']['home'],
                'points_team2' => $match['score']['fullTime']['away'],
            ];
            if (isset($match['competition']['id'])) {
                $competitionId = $match['competition']['id'];

                // Retrieve the competition based on the competition_id
                $competition = Competition::where('competition_id', $competitionId)->first();

                // Check if the competition was found
                if ($competition) {
                    $footballMatch = FootballMatch::updateOrCreate(
                        ['match_id' => $match['id'], 'deleted_at' => null],
                        [
                            'home_team' => $match['homeTeam']['name'],
                            'emblem_home' => $match['homeTeam']['crest'],
                            'away_team' => $match['awayTeam']['name'],
                            'emblem_away' => $match['awayTeam']['crest'],
                            'area_name' => $match['area']['name'],
                            'competition_name' => $match['competition']['name'] ?? null,
                            'status' => 0,
                            'seat' => 0,
                            'result' => json_encode($result, JSON_UNESCAPED_SLASHES),
                            'date_time' => Carbon::parse($match['utcDate'])->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s'),
                            'competition_id' => $competition->id,
                        ]
                    );
                }
            }
        }
    }
    private function hasRequiredFields(array $match, array $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (!isset($match[$field])) {
                return false;
            }
        }
        return true;
    }
}
