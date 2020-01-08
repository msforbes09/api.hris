<?php

namespace App\Http\Controllers\Api;

use App\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Successfully retrieved modules.',
            'data' => Module::with('moduleActions')->get()
        ]);
    }
}
