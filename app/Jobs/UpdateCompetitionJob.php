<?php

namespace App\Jobs;

use App\Models\Competition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class UpdateCompetitionJob
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
                        'competition_id' => $competition['id'],
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
    }
    private function hasRequiredFields(array $competition, array $requiredFields)
    {
        $requiredFields[] = 'id'; // Thêm trường id vào mảng requiredFields
        $missingFields = array_diff($requiredFields, array_keys($competition));
        return empty($missingFields);
    }
    private function addImagesToCompetition($competition, $competitionId)
    {
        if ($competition) {
            // Đường dẫn đến thư mục chứa ảnh
            $imageDirectory = public_path("img/competitions/$competitionId");

            if (File::isDirectory($imageDirectory)) {
                // Lấy danh sách tất cả các tệp trong thư mục
                $imageFiles = File::files($imageDirectory);

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
    }
    public static function dispatchNow()
    {
        return new static();
    }
}
