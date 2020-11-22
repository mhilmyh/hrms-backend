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
            'id' => 'required|integer',
            'identifier' => 'required|string',
            'image' => 'required|file|max:10240'
        ],
        'delete' => [
            'id' => 'required|integer',
            'identifier' => 'required|string',
        ],
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create image controller
     *
     * @return boolean value
     */
    public function create(Request $request)
    {
        $this->validate($request, $this->validateRule['create']);

        // unpack request body
        $id = $request->input('id');
        $identifier = $request->input('identifier');

        // find model
        $model = null;
        switch ($identifier) {
            case "employee":
                $model = Employee::find($id);
                break;
            case "office":
                $model = Office::find($id);
                break;
            default:
                $this->responseHandler(null, 400, "Identifier not found");
                break;
        }

        // take image url
        $image_url = $this->imageUploadHelper($request);

        // create image 
        $image = Image::create([
            'alt' => $identifier,
            'url' => $image_url,
        ]);

        // save model
        $model->image_id = $image->id;
        $model->save();

        return $this->responseHandler(null, 201, "Image Uploaded Successfully.");
    }

    /**
     * Delete image controller
     *
     * @return boolean value
     */
    public function delete(Request $request)
    {
        // unpack request body
        $id = $request->input('id');
        $identifier = $request->input('identifier');

        // find model
        $model = null;
        switch ($identifier) {
            case "employee":
                $model = Employee::find($id);
                break;
            case "office":
                $model = Office::find($id);
                break;
            default:
                $this->responseHandler(null, 400, "Identifier not found");
                break;
        }

        // find image
        $image = Image::find($model->image_id);

        if (!$image) {
            return $this->responseHandler(null, 404, 'Image not found');
        }

        // deleting image
        if (!$this->imageDeleteHelper($image->url)) {
            return $this->responseHandler(null, 404, "Failed when deleting an image");
        }

        // delete image
        $image->delete();

        // save model
        $model->image_id  = null;
        $model->save();

        $this->responseHandler(null, 200, "Delete Image was successful");
    }
}
