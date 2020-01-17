<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Applicant;
use App\Application;
use App\ClientBranch;
use App\ClientPosition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;

class ApplicationController extends Controller
{
    public function index(Applicant $applicant = NULL)
    {
        if($applicant) {
            return $applicant->applications;
        }

        return Application::paginate(request('per_page') ?? 10);
    }

    public function show(Application $application)
    {
        return $application;
    }

    public function store(ApplicationRequest $application, Applicant $applicant)
    {
        $code = ['code' => $this->applicationCode(request(), $applicant)];

        $application = $applicant->applications()->create(request()->merge($code)->toArray());

        return response()->json([
            'message' => 'Successfully created application.',
            'application' => $application
        ]);
    }

    public function update(ApplicationRequest $request, Applicant $applicant, Application $application)
    {
        $application->fill(request()->toArray());

        $application->save();

        return response()->json([
            'message' => 'Successfully updated application.',
            'application' => $application
        ]);
    }

    public function destroy(Applicant $applicant, Application $application)
    {
        $application->delete();

        return [
            'message' => 'Successfully deleted application.'
        ];
    }

    protected function applicationCode($request, $applicant)
    {
        $code = $applicant->code
            . '-' . Client::findOrFail($request['client_id'])->code
            . '-' . ClientBranch::findOrFail($request['client_branch_id'])->code
            . '-' . ClientPosition::findOrFail($request['client_position_id'])->code;

        return $code;
    }
}
