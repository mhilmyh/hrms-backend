<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private $validateRule = [
        'create' => []
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
     * Get offices and departments
     * 
     * @return array offices and departments
     */
    public function index(Request $request)
    {
        $this->responseHandler();
    }

    /**
     * Create office or department
     * 
     * @return object office or department
     */
    public function create(Request $request)
    {
        $this->responseHandler();
    }

    /**
     * Update office or department
     * 
     * @return object updated office or department
     */
    public function update(Request $request)
    {
        $this->responseHandler();
    }

    /**
     * Delete office or department
     * 
     * @return boolean value
     */
    public function delete(Request $request)
    {
        $this->responseHandler(['value' => true]);
    }
}
