<?php

namespace App\Http\Controllers\Api;

use App\Module;
use App\Applicant;
use App\ApplicantEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantEducationRequest;

class ApplicantEducationController extends Controller
{
    private $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'applicant')->first();
    }

    public function index(Applicant $applicant)
    {
        $this->authorize('allows', [$this->module, 'view']);

        return $applicant->education()->orderBy('id', 'desc')->get();
    }

    public function show(Applicant $applicant, ApplicantEducation $education)
    {
        $this->authorize('allows', [$this->module, 'show']);

        return $education;
    }

    public function store(ApplicantEducationRequest $request, Applicant $applicant, ApplicantEducation $education)
    {
        $this->authorize('allows', [$this->module, 'create']);

        $education = $applicant->education()->create($request->only($education->fillable));

        Log::info(auth()->user()->username . ' - Applicant Education Created', [
            'data' => $education
        ]);

        return response()->json([
            'message' => 'Successfully added to record.',
            'education' => $education
        ]);
    }

    public function update(ApplicantEducationRequest $request, Applicant $applicant, ApplicantEducation $education)
    {
        $this->authorize('allows', [$this->module, 'update']);

        $education->update($request->only($education->fillable));

        Log::info(auth()->user()->username . ' - Applicant Education Updated', [
            'data' => $education
        ]);

        return response()->json([
            'message' => 'Successfully updated record.',
            'education' => $education
        ]);
    }

    public function destroy(Applicant $applicant, ApplicantEducation $education)
    {
        $this->authorize('allows', [$this->module, 'delete']);

        $education->delete();

        Log::info(auth()->user()->username . ' - Applicant Education Deleted', [
            'data' => $education
        ]);

        return [
            'message' => 'Successfully deleted record.'
        ];
    }
}
