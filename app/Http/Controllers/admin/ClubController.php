<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ClubController extends Controller
{
    public function index()
    {
        return view('admin.club.club', [
            'competitions' => Competition::all()
        ]);
    }
    public function filter($competitionId = null)
    {
        // dd($request);
        $apiKey = config('myconfig.call_api.api_key');
        $apiCompetitions = [];
        $selectedCompetitionId = $competitionId ;
        // dd($competitionId);
        $competitions = Competition::where('id',$selectedCompetitionId)->get();
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
        $selectedCompetitionId = $competitionId ;

        $clubData = Club::where('competition_id',$selectedCompetitionId)->get();
        return view('admin.club.club', [
            'clubs' => $clubData,
            'competitions' => Competition::all(),
            'competitionId' => $competitionId
        ]);

    }
    

}
