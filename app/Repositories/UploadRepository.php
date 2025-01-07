<?php

namespace App\Repositories;

use App\Models\Upload;

class UploadRepository
{
    public function getAll()
    {
        return Upload::all();
    }

    public function find($file_id)
    {
        return Upload::findOrFail($file_id);
    }

    public function create(array $data)
    {
        return Upload::create($data);
    }

    public function update($file_id, array $data)
    {
        $Upload = Upload::findOrFail($file_id);
        $Upload->update($data);

        return $Upload;  // Return the updated Upload
    }

    public function delete($file_id)
    {
        $Upload = Upload::findOrFail($file_id);

        return $Upload->delete();
    }
}
