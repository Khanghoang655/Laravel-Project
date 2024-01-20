<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class CompetitionController extends Controller
{
    public function index()
    {
        $competition = Competition::withTrashed()->get();

        // Check if $competition is empty
        if ($competition->isEmpty()) {
            return view('admin.admin')->with('competitions', []);
        }

        return view('admin.admin')->with('competitions', $competition);
    }
    public function updateCompetition()
    {
        $apiKey = config('myconfig.call_api.api_key');
        $apiCompetition = config('myconfig.call_api.api_competition_url');
        $responseMatches = Http::withHeaders(['X-Auth-Token' => $apiKey])->get($apiCompetition);

        $competitions = $responseMatches->json()['competitions'] ?? [];


        // Check if 'competitions' key exists
        if (!empty($competitions)) {
            $competitionID = collect($competitions)->pluck('id')->all();
            foreach ($competitions as $competition) {
                $requiredFields = ['name', 'code', 'emblem'];

                // Check if id exists in $competitionID array
                if (!$this->hasRequiredFields($competition, $requiredFields)) {
                    continue;
                }

                $competitionName = $competition['name'];

                $competitionShortName = $competition['code'];
                $competitionImage = $competition['emblem'];
                $winner = [
                    'name' => $competition['currentSeason']['winner']['name'] ?? null,
                    'crest' => $competition['currentSeason']['winner']['crest'] ?? null,
                ];
                // Check if id exists in the database
                $existingCompetition = Competition::where('name_of_competition', $competitionName)->first();
                if (!$existingCompetition) {
                    Competition::create([
                        'name_of_competition' => $competitionName,
                        'short_name' => $competitionShortName,
                        'emblem' => $competitionImage,
                        'start_date' => Carbon::parse($competition['currentSeason']['startDate']),
                        'end_date' => Carbon::parse($competition['currentSeason']['endDate']),
                        'current_matchday' => $competition['currentSeason']['currentMatchday'],
                        'winner' => json_encode($winner),
                        'status' => 0,
                    ]);
                    $this->addImagesToCompetition($existingCompetition, $competition['id']);

                } else {
                    $existingCompetition->update([
                        'winner' => json_encode($winner),
                        'competition_id' => $competition['id'],

                    ]);
                    if (Carbon::parse($competition['currentSeason']['endDate'])->isToday()) {
                        $existingCompetition->update([
                            'status' => 1,
                        ]);
                    }
                    $this->addImagesToCompetition($existingCompetition, $competition['id']);
                }
            }
        }

        $competition = Competition::withTrashed()->paginate(5);
        return view('admin.competition.Admin-compettion', [
            "competition" => $competition,
            'totalPage' => $competition->lastpage(),
            'itemPerPage' => $competition->perPage(),
            'page' => $competition->currentPage(),
        ]);
    }

    private function hasRequiredFields(array $competition, array $requiredFields)
    {
        $requiredFields[] = 'id'; // Thêm trường id vào mảng requiredFields
        $missingFields = array_diff($requiredFields, array_keys($competition));
        return empty($missingFields);
    }
    private function addImagesToCompetition($competition, $competitionId)
    {
        // Kiểm tra xem $competition có tồn tại không
        if ($competition) {
            // Đường dẫn đến thư mục chứa ảnh
            $imageDirectory = public_path("img/competitions/$competitionId");

            // Kiểm tra xem thư mục có tồn tại không
            if (File::isDirectory($imageDirectory)) {
                // Lấy danh sách tất cả các tệp trong thư mục
                $imageFiles = File::files($imageDirectory);

                // Nếu có ít nhất một tệp ảnh
                if (count($imageFiles) > 0) {
                    // Lấy dữ liệu JSON hiện tại từ cột 'images'
                    $currentImages = json_decode($competition->images, true) ?? [];

                    // Lặp qua từng tệp và thêm vào mảng 'images'
                    foreach ($imageFiles as $imageFile) {
                        $imageName = pathinfo($imageFile, PATHINFO_FILENAME);
                        $imageExtension = $imageFile->getExtension(); // Lấy định dạng của ảnh

                        // Tạo chuỗi có cả tên và định dạng của ảnh
                        $imageFullName = $imageName . '.' . $imageExtension;

                        // Kiểm tra xem tên và định dạng của ảnh đã tồn tại trong mảng 'images' chưa
                        if (!in_array($imageFullName, $currentImages)) {
                            // Thêm tên và định dạng của ảnh vào mảng 'images'
                            $currentImages[] = $imageFullName;
                        }
                    }

                    // Cập nhật cột 'images' với mảng mới
                    $competition->update([
                        'images' => json_encode($currentImages),
                    ]);
                }
            }
        }
        // Nếu $competition là null, bỏ qua và không thực hiện thêm ảnh
    }


    public function destroy($id)
    {
        $competition = Competition::find($id);

        if ($competition) {
            $competition->delete();
            return redirect()->route('admin.update-competition')->with('msg', 'Xóa thành công giải đấu');
        } else {
            return redirect()->route('admin.update-competition')->with('error', 'Không tìm thấy giải đấu');
        }
    }
    public function detail($id)
    {
        $competition = Competition::find($id);

        return redirect()->route('admin.club.filter', ['competitionId' => $competition['id']]);
    }
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'name_of_competition' => [
                'required',
                'string',
                'max:255',
                Rule::unique('competition')->ignore($id, 'id'),
            ],
            'short_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('competition')->ignore($id, 'id'),
            ],
            'start_date' => [
                'required',
                'date_format:Y-m-d',
                'lte:today()', // So sánh start_date với ngày hiện tại
                'after_or_equal:' . now()->toDateString(),
            ],
            'end_date' => [
                'required',
                'date_format:Y-m-d',
                'after:start_date',
            ],
        ]);
        $competition = Competition::where('id', $id)->firstOrFail();

        // Update the match data
        $competition->update([
            'name_of_competition' => $request->name_of_competition,
            'short_name' => $request->short_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.competition.update', ['id' => $id])->with('msg', 'Cập nhật thành công');
    }
}
