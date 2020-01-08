<?php

namespace App\Http\Controllers\Api;

use App\Contracts\IModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ModuleController extends Controller
{
    protected $iModule;

    public function __construct(IModule $iModule)
    {
        $this->iModule = $iModule;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = $this->iModule->all();

        return ResponseBuilder::asSuccess(200)
            ->withData($modules)
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleRequest $request)
    {
        $validatedRequest = $request->validated();
        
        $newModule = $this->iModule->store($validatedRequest);
        
        return ResponseBuilder::asSuccess(200)
            ->withData($newModule)
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $module = $this->iModule->getById($id);
        
        return ResponseBuilder::asSuccess(200)
            ->withData($module)
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleRequest $request, $id)
    {
        $validatedRequest = $request->validated();
        
        $updatedModule = $this->iModule->update($validatedRequest, $id);
        
        return ResponseBuilder::asSuccess(200)
            ->withData($updatedModule)
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->iModule->destroy($id))
        {
            return ResponseBuilder::asSuccess(200)
                ->withMessage('Module successfully deleted.')
                ->build();
        }
    }
}
