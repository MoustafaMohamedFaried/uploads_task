<?php

namespace App\Repositories;

use App\Models\Upload;

class UploadRepository
{
    public function upload($data)
    {
        $userImage = Upload::create([
            'path' => $data['image'],
            'user_id' => $data['user_id']
        ]);

        return $userImage;
    }

    public function getUserImages($userId)
    {
        $userImages = Upload::select(['path'])
            ->where('user_id', $userId)
            ->pluck('path')->toArray();

        return $userImages;
    }
}
