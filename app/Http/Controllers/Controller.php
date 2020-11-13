<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private $public_path = 'storage';
    private $dest_folder = 'upload';

    /**
     * Format response when send to client
     * 
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
                storage_path('app/public/' . $this->dest_folder),
                $image_name
            );
            if ($success) {
                $prefix_name = request()->getSchemeAndHttpHost() . '/' . $this->public_path . '/' . $this->dest_folder;
                return $prefix_name . '/' . $image_name;
            }
            return null;
        }
    }

    /**
     * Helper function for deleting image
     * 
     * @return bool
     */
    protected function imageDeleteHelper($image_url = '')
    {
        $array = explode("/", $image_url);
        $image_name = end($array);
        try {
            File::Delete('storage/' . $this->dest_folder . '/' . $image_name);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Error response when validation failed
     * 
     * @return object
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return [
            "code" => 400,
            "message" => "Periksa kembali inputan anda",
            "errors" => $errors
        ];
    }
}
