<?php

namespace App\Services;

use App\Repositories\UploadRepository;


class UploadService
{
    protected $uploadRepository;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    public function getAllUploads()
    {
        return $this->uploadRepository->getAll();
    }

    public function getUpload($file_id)
    {
        return $this->uploadRepository->find($file_id);
    }

    public function createUpload(array $data)
    {
        // Create the Upload using the repository
        $createdUpload = $this->uploadRepository->create($data);

        return $createdUpload;
    }

    public function updateUpload($file_id, array $data)
    {
        // Update the Upload using the repository
        return $this->uploadRepository->update($file_id, $data);
    }

    public function deleteUpload($file_id)
    {
        return $this->uploadRepository->delete($file_id);
    }

    public function createNewToken($token){
        return[
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'Upload' => auth()->Upload()
        ];
    }

}
