<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Competition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdateClub implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dontReport = [
        JobFailed::class,
    ];

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
       $apiCompetitions = [];
       $competitions = Competition::get();
       // dd($competitions);
       foreach ($competitions as $competition) {
           $apiCompetition = config('myconfig.call_api.api_competition_url') . '/' . $competition->short_name . '/teams';
           $apiCompetitions[] = $apiCompetition;
           $responseCompetition = Http::withHeaders(['X-Auth-Token' => $apiKey])->get($apiCompetition);
           $datas = $responseCompetition->json();
           foreach ($datas['teams'] as $data) {
               $coach = [
                   'id' => $data['coach']['id'] ?? null,
                   'name' => $data['coach']['name'] ?? null,
                   'dateOfBirth' => $data['coach']['dateOfBirth'] ?? null,
                   'nationality' => $data['coach']['nationality'] ?? null,
                   'contract' => [
                       'start' => $data['coach']['contract']['start'] ?? null,
                       'until' => $data['coach']['contract']['until'] ?? null,
                   ],
               ];
               dd($competition->id);
               $squad = [];
               foreach ($data['squad'] as $player) {
                   $squad[] = [
                       'name' => $player['name'] ?? null,
                       'position' => $player['position'] ?? null,
                   ];
               }

               $clubData = Club::updateOrInsert([
                   'club_id' => $data['id'],
               ], [
                   'club_id' => $data['id'],
                   'name' => $data['name'],
                   'tla' => $data['tla'],
                   'crest' => $data['crest'],
                   'website' => $data['website'],
                   'founded' => $data['founded'],
                   'coach' => json_encode($coach, JSON_UNESCAPED_SLASHES),
                   'squad' => json_encode($squad, JSON_UNESCAPED_SLASHES),
                   'competition_id' => $competition->id,
               ]);
           }
       }
    }
    public function delay()
    {
        return now()->addMinutes(2);
    }
}
