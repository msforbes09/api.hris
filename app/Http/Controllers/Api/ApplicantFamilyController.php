<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use App\ApplicantFamily;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantFamilyRequest;

class ApplicantFamilyController extends Controller
{
    public function index(Applicant $applicant)
    {
        return $applicant->families;
    }

    public function show()
    {
        abort(404);
    }

    public function store(ApplicantFamilyRequest $request, Applicant $applicant, ApplicantFamily $family)
    {
        $applicantFamily = $applicant->families()->create($request->only($family->fillable));

        return response()->json([
            'message' => 'Successfully added to record.',
            'family' => $applicantFamily
        ]);
    }

    public function update(ApplicantFamilyRequest $request, Applicant $applicant, ApplicantFamily $family)
    {
        $family->update($request->only($family->fillable));

        return response()->json([
            'message' => 'Successfully updated record.',
            'family' => $family
        ]);
    }

    public function destroy(Applicant $applicant, ApplicantFamily $family)
    {
        $family->delete();

        return [
            'message' => 'Successfully deleted record.'
        ];
    }
}
