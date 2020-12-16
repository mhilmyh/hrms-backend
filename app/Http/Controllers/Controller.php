<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\Address;
use App\Models\Employee;
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
    protected function responseHandler($data = null, $status = 200, $message = '')
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
        $array = explode('/', $image_url);
        $image_name = end($array);
        try {
            File::Delete('storage/' . $this->destFolder . '/' . $image_name);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Helper function for update employee
     *
     * @param request request instace
     * @param employee employee model
     * @return object
     */
    public function updateEmployee(Request $request, Employee $employee)
    {
        $employee->first_name = $request->input('first_name') === null ? $employee->first_name : $request->input('first_name');
        $employee->mid_name = $request->input('mid_name') === null ? $employee->mid_name : $request->input('mid_name');
        $employee->last_name = $request->input('last_name') === null ? $employee->last_name : $request->input('last_name');
        $employee->phone = $request->input('phone') === null ? $employee->phone : $request->input('phone');
        $employee->gender = $request->input('gender') === null ? $employee->gender : $request->input('gender');
        $employee->birthday = $request->input('birthday') === null ? $employee->birthday : $request->input('birthday');
        $employee->salary = $request->input('salary') === null ? $employee->salary : $request->input('salary');
        $employee->job_position = $request->input('job_position') === null ? $employee->job_position : $request->input('job_position');
        return $employee;
    }

    /**
     * Helper function for update adddress
     *
     * @param request request instace
     * @param adddress address model
     * @return object
     */
    public function updateAddress(Request $request, Address $address)
    {
        $address->country = $request->input('country') === null ? $address->country : $request->input('country');
        $address->province = $request->input('province') === null ? $address->province : $request->input('province');
        $address->city = $request->input('city') === null ? $address->city : $request->input('city');
        $address->postal_code = $request->input('postal_code') === null ? $address->postal_code : $request->input('postal_code');
        $address->street = $request->input('street') === null ? $address->street : $request->input('street');
        return $address;
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
        $array = [];
        foreach ($errors as $key => $value) {
            array_push($array, $value[0]);
        }
        return response()->json([
            'message' => implode(' ', $array),
        ], 400);
    }
}
