<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\IModuleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleActionRequest;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ModuleActionController extends Controller
{
    protected $iModuleAction;

    public function __construct(IModuleAction $iModuleAction)
    {
        $this->iModuleAction = $iModuleAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($moduleId)
    {
        $moduleActions = $this->iModuleAction->all($moduleId);

        return ResponseBuilder::asSuccess(200)
            ->withData($moduleActions)
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleActionRequest $request, $moduleId)
    {
        $validatedRequest = $request->validated();
        
        $moduleAction = $this->iModuleAction->store($validatedRequest, $moduleId);
        
        return ResponseBuilder::asSuccess(200)
            ->withData($moduleAction)
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($moduleId, $id)
    {
        $moduleAction = $this->iModuleAction->getById($moduleId, $id);

        return ResponseBuilder::asSuccess(200)
            ->withData($moduleAction)
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleActionRequest $request, $moduleId, $id)
    {
        $validatedRequest = $request->validated();
        
        $moduleAction = $this->iModuleAction->update($validatedRequest, $moduleId, $id);
        
        return ResponseBuilder::asSuccess(200)
            ->withData($moduleAction)
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($moduleId, $id)
    {
        if($this->iModuleAction->destroy($moduleId, $id))
        {
            return ResponseBuilder::asSuccess(200)
                ->withMessage('Module Action successfully deleted.')
                ->build();
        }
    }
}
