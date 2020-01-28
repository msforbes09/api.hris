<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use App\ApplicantEducation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantEducationRequest;

class ApplicantEducationController extends Controller
{
    public function index(Applicant $applicant)
    {
        return $applicant->education;
    }

    public function store(ApplicantEducationRequest $request, Applicant $applicant, ApplicantEducation $education)
    {
        $education = $applicant->education()->create($request->only($education->fillable));

        return response()->json([
            'message' => 'Successfully added to record.',
            'education' => $education
        ]);
    }

    public function update(ApplicantEducationRequest $request, Applicant $applicant, ApplicantEducation $education)
    {
        $education->update($request->only($education->fillable));

        return response()->json([
            'message' => 'Successfully updated record.',
            'education' => $education
        ]);
    }

    public function destroy(Applicant $applicant, ApplicantEducation $education)
    {
        $education->delete();

        return [
            'message' => 'Successfully deleted record.'
        ];
    }
}
