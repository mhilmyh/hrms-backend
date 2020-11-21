<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Office;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    private $validateRule = [
        'create' => [
            'image' => 'required|file|max:10240'
        ]
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'index'
        ]]);
    }

    /**
     * Create image controller
     *
     * @return boolean value
     */
    public function create(Request $request)
    {
        $this->validate($request, $this->validateRule['create']);

        // TODO: check param to determine model (employee or office)
        // id is for employeeId || officeId
        $id = $request->query('id');
        $model = $request->query('type') == "employee" ? Employee::find($id) : Office::find($id);
        $image = $this->imageUploadHelper($request);
        // TODO: save image according to model (employee or office)
        $tmp = parse_url($image, PHP_URL_PATH);
        $segments = explode("/", trim($tmp, "/"));
        $src = end($segments);
        $img = Image::create([
            'alt' => $src,
            'url' => $image,
        ]);

        $model->image_id = $img->id;
        $model->save();
        return $this->responseHandler(['value' => true], 201, "Image Uploaded Successfully.");
    }

    /**
     * Delete image controller
     *
     * @return boolean value
     */
    public function delete(Request $request)
    {
        // TODO: check param to determine model (employee or office)
        $id = $request->query('id');
        $model = $request->query('type') == "employee" ? Employee::find($id) : Office::find($id);
        // TODO: delete image
        $image = Image::find($model->image_id);
        if (!$this->imageDeleteHelper($image->url)){
            return $this->responseHandler(['value' => false], 404, "Failed when deleting an image");
        }
        $image->delete();
        // TODO: remove relation from model (employee or office)
        $model->image_id  = null;
        $this->responseHandler(['value' => true], 201, "Delete Image was successful");
    }
}
