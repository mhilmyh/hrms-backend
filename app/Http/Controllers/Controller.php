<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private $publicPath = 'storage';
    private $destFolder = 'upload';

    /**
     * Format response when send to client
     *
     * @param data      only array or null
     * @param status    status code response
     * @param message   message string response
     * @return object
     */
    protected function responseHandler($data = null, $status = 200, $message = "")
    {
        return response()->json(
            array_merge($data ?? [], ['message' => $message]),
            $status
        );
    }


    /**
     * Helper function for uploading image
     *
     * @param request   Request object
     * @param entity    Model name
     * @return string
     */
    protected function imageUploadHelper(Request $request, $entity = 'U')
    {
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $image_name = $entity . '-' . time() . '-' . Str::random() .  '.' . $extension;
            $success = $request->file('image')->move(
                storage_path('app/public/' . $this->destFolder),
                $image_name
            );
            if ($success) {
                $prefix_name = request()->getSchemeAndHttpHost() . '/' . $this->publicPath . '/' . $this->destFolder;
                return $prefix_name . '/' . $image_name;
            }
            return null;
        }
    }

    /**
     * Helper function for deleting image
     *
     * @param image_url string url of image
     * @return bool
     */
    protected function imageDeleteHelper($image_url = '')
    {
        $array = explode("/", $image_url);
        $image_name = end($array);
        try {
            File::Delete('storage/' . $this->destFolder . '/' . $image_name);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Error response when validation failed
     *
     * @param request   request object
     * @param error     list error
     * @return object
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return response()->json([
            "message" => "Check your input",
            "errors" => $errors
        ], 400);
    }
}
