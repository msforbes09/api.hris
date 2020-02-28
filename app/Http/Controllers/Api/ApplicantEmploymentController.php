<?php

namespace App\Http\Controllers\Api;

use App\Module;
use App\Applicant;
use App\ApplicantEmployment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantEmploymentRequest;

class ApplicantEmploymentController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'applicant')->first();
    }

    public function index(Applicant $applicant)
    {
        $this->authorize('allows', [$this->module, 'view']);

        return $applicant->employments()->orderBy('id', 'desc')->get();
    }

    public function store(ApplicantEmploymentRequest $request, Applicant $applicant, ApplicantEmployment $employment)
    {
        $this->authorize('allows', [$this->module, 'store']);

        $employment = $applicant->employments()->create($request->only($employment->fillable));

        Log::info(auth()->user()->username . ' - Applicant Employment Created', [
            'data' => $employment
        ]);

        return response()->json([
            'message' => 'Successfuly added to record.',
            'employment' => $employment
        ]);
    }

    public function update(ApplicantEmploymentRequest $request, Applicant $applicant, ApplicantEmployment $employment)
    {
        $this->authorize('allows', [$this->module, 'update']);

        $employment->update($request->only($employment->fillable));

        Log::info(auth()->user()->username . ' - Applicant Employment Updated', [
            'data' => $employment
        ]);

        return response()->json([
            'message' => 'Successfuly updated record.',
            'employment' => $employment
        ]);
    }

    public function destroy(Applicant $applicant, ApplicantEmployment $employment)
    {
        $this->authorize('allows', [$this->module, 'delete']);

        $employment->delete();

        Log::info(auth()->user()->username . ' - Applicant Employment Deleted', [
            'data' => $employment
        ]);

        return [
            'message' => 'Successfuly deleted record.'
        ];
    }
}
