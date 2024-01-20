<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use App\Models\Competition;
use App\Models\FootballMatch;
use App\Models\Seat;
use App\Notifications\SmsCustomer;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MatchController extends Controller
{



    private $deletedMatches = [];

    public function updateMatch(Request $request)
    {
        $this->deletedMatches = session('deletedMatches', []);

        // Lưu giá trị deletedMatches vào session
        session(['deletedMatches' => $this->deletedMatches]);

        // dd($this->deletedMatches);
        $deletedMatches = session('deletedMatches') ?? [];
        $apiKey = config('myconfig.call_api.api_key');
        $apiMatch = config('myconfig.call_api.api_match_url');
        $responseMatches = Http::withHeaders(['X-Auth-Token' => $apiKey])->get($apiMatch);

        if (!$responseMatches->successful()) {
            throw new \Exception('Failed to fetch match data from the API.');
        }

        $matches = $responseMatches->json()['matches'] ?? [];
        foreach ($matches as $match) {
            $requiredFields = ['homeTeam', 'awayTeam', 'area', 'competition'];

            if (!$this->hasRequiredFields($match, $requiredFields)) {
                continue;
            }

            // Check if the match ID is in the deletedMatches array
            $isDeleted = in_array($match['id'], $deletedMatches);

            // dd($isDeleted);
            // If the match is not deleted, add it back
            if (!$isDeleted) {
                $result = [
                    'points_team1' => $match['score']['fullTime']['home'],
                    'points_team2' => $match['score']['fullTime']['away'],
                ];

                $competitionId = Competition::where('competition_id', $match['competition']['id'])->first();

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
                        'competition_id' => $competitionId->id,
                    ]
                );
                // $totalSeat = Seat::where('match_id', $footballMatch->id)->value('total_seat') ?? 0;
                // $footballMatch->seat =  $totalSeat;
                // $footballMatch->save();

            }
        }
        $notPlayedChecked = $request->has('not_played') ? true : false;
        $selectedCompetition = $request->input('competition', 'all');
        $competitionId = null;

        // dd($selectedCompetition);
        if ($selectedCompetition !== 'all') {
            $competitionId = Competition::where('name_of_competition', $selectedCompetition)->first();
        }
        $competitions = FootballMatch::distinct('competition_name')->pluck('competition_name');

        $query = ($selectedCompetition !== 'all')
            ? FootballMatch::where('competition_id', $competitionId->id)->withTrashed()
            : FootballMatch::withTrashed();
        if ($notPlayedChecked) {
            $query->where('date_time', '>=', now());
        }
        $filteredMatches = $query->paginate(10);

        return view('admin.sport.admin-sport', [
            'matches' => $filteredMatches,
            'competitions' => $competitions,
            'selectedCompetition' => $selectedCompetition,
            'totalPage' => $filteredMatches->lastPage(),
            'itemPerPage' => $filteredMatches->perPage(),
            'page' => $filteredMatches->currentPage(),
            'notPlayedChecked' => $notPlayedChecked,
        ]);
    }


    /**
     * Check if the required fields exist in the match data.
     *
     * @param array $match
     * @param array $requiredFields
     * @return bool
     */
    private function hasRequiredFields(array $match, array $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (!isset($match[$field])) {
                return false;
            }
        }
        return true;
    }



    public function restore($id)
    {
        $product = FootballMatch::withTrashed()->where('match_id', $id);
        $product->restore();
        return redirect()->route('admin.update-matches')->with('msg', 'Khoi phuc tran dau thanh cong');
    }
    public function destroy($id)
    {
        $match = FootballMatch::withTrashed()->where('match_id', $id)->first();

        $match->is_deleted = 1;

        $match->save();
        FootballMatch::where('match_id', $id)->delete();
        return redirect()->route('admin.update-matches')->with('msg', 'Xoa tran dau thanh cong');
    }
    public function forceDelete($id)
    {
        // Lấy tất cả thông tin của các trận đấu cần xóa
        $deletedMatches = FootballMatch::withTrashed()->where('match_id', $id)->get()->toArray();

        // Xóa vĩnh viễn các trận đấu
        FootballMatch::withTrashed()->where('match_id', $id)->forceDelete();

        // Lưu thông tin của các trận đấu đã xóa vào session
        session(['deletedMatches' => $deletedMatches]);

        // Chuyển hướng đến trang quản lý trận đấu và hiển thị thông báo
        return redirect()->route('admin.update-matches')->with('msg', 'Xoa tran dau thanh cong');
    }
    public function createSlug(Request $request)
    {
        $slug = Str::slug($request->name ?? '');
        return response()->json(['slug' => $slug]);
    }


    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'home_team' => 'required|string|max:255',
            'away_team' => 'required|string|max:255',
            'home_name_slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('football_matches')->ignore($id, 'match_id'),
            ],
            'away_name_slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('football_matches')->ignore($id, 'match_id'),
            ],
        ]);

        // Find the match or throw a 404 exception
        $matches = FootballMatch::where('match_id', $id)->firstOrFail();

        // Update the match data
        $matches->update([
            'home_team' => $request->home_team,
            'away_team' => $request->away_team,
            'home_name_slug' => Str::slug($request->home_name_slug ?? ''),
            'away_name_slug' => Str::slug($request->away_name_slug ?? ''),
        ]);

        return redirect()->route('admin.update-matches')->with('msg', 'Cập nhật thành công');
    }
    public function detail($id)
    {
        $matches = FootballMatch::where('match_id', $id)->firstOrFail();
        return view('admin.sport.admin-sport-detail')->with('matches', $matches);
    }
    public function restoreMatch()
    {
        $matchesDeleted = session('deletedMatches'); // Lấy thông tin các trận đấu đã xóa từ session
        // dd($matchesDeleted);
        // Duyệt qua từng trận đấu cần khôi phục
        if (empty($matchesDeleted)) {
            return redirect()->route('admin.update-matches')->with('msg', 'Không có dữ liệu cần khôi phục');
        }
        foreach ($matchesDeleted as $match) {
            // Tạo bản ghi trận đấu mới với thông tin được lưu trữ
            FootballMatch::updateOrCreate(
                ['match_id' => $match['match_id']],
                [
                    'home_team' => $match['home_team'] ?? $match->home_team ?? null,
                    'emblem_home' => $match['emblem_home'] ?? $match->emblem_home ?? null,
                    'away_team' => $match['away_team'] ?? $match->away_team ?? null,
                    'emblem_away' => $match['emblem_away'] ?? $match->emblem_away ?? null,
                    'area_name' => $match['area_name'] ?? $match->area_name ?? null,
                    'competition_name' => $match['competition_name'] ?? $match->competition_name ?? null,
                    'status' => 0,
                    'seat' => 70000,
                    'result' => json_encode($match['result'] ?? $match->result ?? null, JSON_UNESCAPED_SLASHES),
                    'date_time' => Carbon::parse($match['date_time'] ?? $match->date_time ?? null)->toDateTimeString(),
                    'competition_id' => $match['competition_id'] ?? $match->competition_id ?? null,
                ]
            );
        }

        // Xóa thông tin các trận đấu đã khôi phục khỏi session
        session()->forget('deletedMatches');

        // Chuyển hướng đến trang quản lý trận đấu và hiển thị thông báo
        return redirect()->route('admin.update-matches')->with('msg', 'Khôi phục thành công');
    }

}