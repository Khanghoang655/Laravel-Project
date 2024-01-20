<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Competition;
use App\Models\FootballMatch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FootballMatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Retrieve competition data for competition with ID 4
        $competitionData = Competition::whereIn('id', [4, 3])->first();

        if (!$competitionData) {
            dd('Competition not found');
        }

        $clubs = Club::whereIn('competition_id', [4, 3])->get();


        // Check if there are at least two clubs
        if (!$clubs || $clubs->count() < 2) {
            return [];
        }

        // Get unique team names from the retrieved clubs
        $uniqueTeamNames = $clubs->pluck('name')->unique();

        // Check if there are at least two unique team names
        if ($uniqueTeamNames->count() < 2) {
            return [];
        }

        // Randomly select two team names
        $randomClubs = $uniqueTeamNames->random(2);

        // Get the current datetime and create a future datetime between 2 to 4 hours from now
        $currentDateTime = Carbon::now('Asia/Ho_Chi_Minh');
        $futureDateTime = $currentDateTime->addMinutes(mt_rand(120, 240));

        // Initial result with null points for both teams
        $result = [
            'points_team1' => null,
            'points_team2' => null,
        ];

        // Define attributes for the FootballMatch model
        return [
            'match_id' => mt_rand(10000, 99999),
            'home_team' => $randomClubs[0],
            'away_team' => $randomClubs[1],
            'result' => json_encode($result, JSON_UNESCAPED_SLASHES),
            'date_time' => $futureDateTime->format('Y-m-d H:i:s'),
            'competition_name' => $competitionData->name_of_competition,
            'emblem_home' => $clubs->firstWhere('name', $randomClubs[0])->crest,
            'emblem_away' => $clubs->firstWhere('name', $randomClubs[1])->crest,
            'competition_id' => 4,
        ];
    }





}
