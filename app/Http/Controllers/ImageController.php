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

        $id = $request->input('id');
        $identifier = $request->input('identifier');

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

        $image_url = $this->imageUploadHelper($request);

        $image = Image::create([
            'alt' => $identifier,
            'url' => $image_url,
        ]);

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
        $id = $request->input('id');
        $identifier = $request->input('identifier');

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

        $image = Image::find($model->image_id);

        if (!$image) {
            return $this->responseHandler(null, 404, 'Image not found');
        }

        if (!$this->imageDeleteHelper($image->url)) {
            return $this->responseHandler(null, 404, "Failed when deleting an image");
        }

        $image->delete();

        $model->image_id  = null;
        $model->save();

        $this->responseHandler(null, 200, "Delete Image was successful");
    }
}
