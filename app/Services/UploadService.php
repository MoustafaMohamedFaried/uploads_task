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

    public function getUpload($post_id)
    {
        return $this->uploadRepository->find($post_id);
    }

    public function createUpload(array $data)
    {
        // Create the Upload using the repository
        $createdUpload = $this->uploadRepository->create($data);

        return $createdUpload;
    }

    public function updateUpload($post_id, array $data)
    {
        // Update the Upload using the repository
        return $this->uploadRepository->update($post_id, $data);
    }

    public function deleteUpload($post_id)
    {
        return $this->uploadRepository->delete($post_id);
    }
}
