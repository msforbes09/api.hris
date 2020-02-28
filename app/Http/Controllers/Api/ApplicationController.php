<?php

namespace App\Http\Controllers\Api;

use App\Branch;
use App\Client;
use App\Module;
use App\Applicant;
use App\Application;
use App\ClientBranch;
use App\ClientPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;

class ApplicationController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'applicant')->first();
    }

    public function index(Applicant $applicant)
    {
        $this->authorize('allows', [$this->module , 'view']);

        return $applicant->applications()->latest()->get();
    }

    public function show(Application $application)
    {
        $this->authorize('allows', [$this->module , 'show']);

        return $application;
    }

    public function store(ApplicationRequest $request, Applicant $applicant)
    {
        $this->authorize('allows', [$this->module , 'create']);

        $code = [
            'code' => Application::getModel()->generateCode(request('branch_id')),
            'applicant_id' => $applicant->id
        ];

        $application = Application::create(request()
            ->merge($code)
            ->only(Application::getModel()->getFillable())
        );

        Log::info(auth()->user()->username . ' has created an Application.', ['data' => $application]);

        return [
            'message' => 'Successfully created application.',
            'application' => $application
        ];
    }

    public function update(ApplicationRequest $request, Applicant $applicant, Application $application)
    {
        $this->authorize('allows', [$this->module , 'update']);

        $application->update(request()->only($application->getFillable()));

        Log::info(auth()->user()->username . ' has updated an Application.', ['data' => $application]);

        return [
            'message' => 'Successfully updated application.',
            'application' => $application
        ];
    }

    public function destroy(Applicant $applicant, Application $application)
    {
        $this->authorize('allows', [$this->module , 'delete']);

        $application->delete();

        Log::info(auth()->user()->username . ' has deleted an Application.', ['data' => $application]);

        return [
            'message' => 'Successfully deleted application.'
        ];
    }
}
