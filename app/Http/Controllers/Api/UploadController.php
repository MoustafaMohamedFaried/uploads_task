<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Carbon\Exceptions\Exception;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
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
            return $this->errorApiResponse($e->getMessage(), 'Error at get Posts', $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get the user profile from the request
            $userProfile = $request->attributes->get('userProfile');

            $validatedData = $request->validate([
                'title' => 'required',
                'content' => 'required',
            ]);

            $validatedData['uploader_id'] = $userProfile['data']['id'];
            $validatedData['uploader_name'] = $userProfile['data']['name'];

            $createdUpload = $this->uploadService->createUpload($validatedData);

            DB::commit();

            return $this->apiResponse($createdUpload, 'Post created successfully', 200);
        } catch (ValidationException $e) {

            return $this->errorApiResponse($e->errors(), 'Validation failed', $e->getCode());
        } catch (Exception $e) {

            return $this->errorApiResponse($e->getMessage(), 'Error at create Upload', $e->getCode());
        }
    }

    public function show($post_id)
    {
        return $this->apiResponse($this->uploadService->getUpload($post_id), '', 200);
    }

    public function update(Request $request, $post_id)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'title' => 'required',
                'content' => 'required',
            ]);

            $updatedUpload = $this->uploadService->updateUpload($post_id, $validatedData);

            DB::commit();

            return $this->apiResponse($updatedUpload, 'Post updated successfully', 200);
        } catch (ValidationException $e) {

            return $this->errorApiResponse($e->errors(), 'Validation failed', $e->getCode());
        } catch (Exception $e) {

            return $this->errorApiResponse($e->getMessage(), 'Error at update Upload', $e->getCode());
        }
    }

    public function destroy($post_id)
    {
        try {
            $this->uploadService->deleteUpload($post_id);

            return $this->apiResponse([], 'Post deleted successfully', 200);
        } catch (Exception $e) {

            return $this->errorApiResponse($e->getMessage(), 'Error at delete Upload', $e->getCode());
        }
    }
}
