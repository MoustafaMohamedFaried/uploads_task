<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Carbon\Exceptions\Exception;
use App\Traits\ApiResponseTrait;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UploadController extends Controller
{
    use ApiResponseTrait;

    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function index()
    {
        try {
            return $this->apiResponse($this->uploadService->getAllUploads(), '', 200);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage(), 'Error at get Uploads', $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
            ]);

            $createdUpload = $this->uploadService->createUpload($validatedData);

            return $this->apiResponse($createdUpload, 'Upload created successfully', 200);
        } catch (ValidationException $e) {

            return $this->errorApiResponse($e->errors(), 'Validation failed', $e->getCode());
        } catch (Exception $e) {

            return $this->errorApiResponse($e->getMessage(), 'Error at create Upload', $e->getCode());
        }
    }

    public function show($file_id)
    {
        return $this->apiResponse($this->uploadService->getUpload($file_id), '', 200);
    }

    public function update(Request $request, $file_id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
            ]);

            $updatedUpload = $this->uploadService->updateUpload($file_id, $validatedData);

            return $this->apiResponse($updatedUpload, 'Upload updated successfully', 200);
        } catch (ValidationException $e) {

            return $this->errorApiResponse($e->errors(), 'Validation failed', $e->getCode());
        } catch (Exception $e) {

            return $this->errorApiResponse($e->getMessage(), 'Error at update Upload', $e->getCode());
        }
    }

    public function destroy($file_id)
    {
        try {
            $this->uploadService->deleteUpload($file_id);

            return $this->apiResponse([], 'File deleted successfully', 200);
        } catch (Exception $e) {

            return $this->errorApiResponse($e->getMessage(), 'Error at delete Upload', $e->getCode());
        }
    }
}
