<?php

namespace App\Repositories;

use App\Models\Upload;

class UploadRepository
{
    public function getAll()
    {
        return Upload::all();
    }

    public function find($post_id)
    {
        return Upload::findOrFail($post_id);
    }

    public function create(array $data)
    {
        return Upload::create($data);
    }

    public function update($post_id, array $data)
    {
        $upload = Upload::findOrFail($post_id);

        $upload->update($data);

        return $upload;  // Return the updated Upload
    }

    public function delete($post_id)
    {
        $upload = Upload::findOrFail($post_id);

        return $upload->delete();
    }
}
