<?php

namespace App\Services;

use App\Repositories\UploadRepository;
use Carbon\Exceptions\Exception;
use App\Traits\ApiResponseTrait;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class UploadService
{
    use ApiResponseTrait;

    protected $uploadRepository;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    public function uploadImage(Request $request)
    {
        try {
            $userProfile = request()->get('userProfile'); // Accessing the attribute directly
            $rolesAndPermisions = $userProfile['roles_and_permissions'];

            if ($rolesAndPermisions['admin'] && $rolesAndPermisions['admin']['create'] == true) {
                $validatedData = $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);

                $validatedData['image'] = $request->file('image')->store('images');
                $validatedData['user_id'] = $userProfile['id'];

                $uploadedImage = $this->uploadRepository->upload($validatedData);

                return $this->apiResponse($uploadedImage, 'Image uploaded successfully', 200);
            } else {
                return $this->errorApiResponse([], "You don't have permision to upload image", 403);
            }
        } catch (ValidationException $e) {
            return $this->errorApiResponse(
                $e->errors(),
                'Validation failed',
                422 // Validation errors typically use 422 Unprocessable Entity
            );
        } catch (Exception $e) {
            $statusCode = $e->getCode() > 0 ? $e->getCode() : 500; // Default to 500 for invalid codes
            return $this->errorApiResponse(
                $e->getMessage(),
                'Error at Upload photo',
                $statusCode
            );
        }
    }

    public function getUserImages()
    {
        try {
            $userProfile = request()->get('userProfile'); // Accessing the attribute directly
            $rolesAndPermisions = $userProfile['roles_and_permissions'];
            if ($rolesAndPermisions['admin'] && $rolesAndPermisions['admin']['create'] == true) {
                $userImages = $this->uploadRepository->getUserImages($userProfile['id']);

                return $this->apiResponse($userImages, "User's photos", 200);
            } else {
                return $this->errorApiResponse([], "You don't have permision to view images", 403);
            }
        } catch (Exception $e) {
            return $this->errorApiResponse($e->getMessage(), "Error at get user's photo", $e->getCode());
        }
    }
}
