<?php

namespace App\Http\Controllers;

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
        // TODO: save image according to model (employee or office)

        $this->responseHandler(['value' => true]);
    }

    /**
     * Delete image controller
     * 
     * @return boolean value
     */
    public function delete(Request $request)
    {
        // TODO: check param to determine model (employee or office)
        // TODO: delete image
        // TODO: remove relation from model (employee or office)

        $this->responseHandler(['value' => true]);
    }
}
