<?php

namespace App\Http\Controllers\Api;

use App\Branch;
use App\Client;
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

    public function store(ApplicationRequest $request, Applicant $applicant, Application $application)
    {
        $code = ['code' => getCode(new Application(), [Branch::findOrFail($request->branch_id)])];

        $application = $applicant->applications()->create($request->merge($code)->only($application->fillable));

        Log::info(auth()->user()->username . ' - Application Created', [
            'data' => $application
        ]);

        return response()->json([
            'message' => 'Successfully created application.',
            'application' => $application
        ]);
    }

    public function update(ApplicationRequest $request, Applicant $applicant, Application $application)
    {
        $application->update($request->only($application->fillable));

        Log::info(auth()->user()->username . ' - Application Updated', [
            'data' => $application
        ]);

        return response()->json([
            'message' => 'Successfully updated application.',
            'application' => $application
        ]);
    }

    public function destroy(Applicant $applicant, Application $application)
    {
        $application->delete();

        Log::info(auth()->user()->username . ' - Application Deleted', [
            'data' => $application
        ]);

        return [
            'message' => 'Successfully deleted application.'
        ];
    }
}
