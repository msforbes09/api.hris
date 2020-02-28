<?php

namespace App\Http\Controllers\Api;

use App\Module;
use App\Applicant;
use App\ApplicantFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantFamilyRequest;

class ApplicantFamilyController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'applicant')->first();
    }

    public function index(Applicant $applicant)
    {
        $this->authorize('allows', [$this->module, 'view']);

        return $applicant->families()->orderBy('id', 'desc')->get();
    }

    public function show()
    {
        abort(404);
    }

    public function store(ApplicantFamilyRequest $request, Applicant $applicant, ApplicantFamily $family)
    {
        $this->authorize('allows', [$this->module, 'store']);

        $family = $applicant->families()->create($request->only($family->fillable));

        Log::info(auth()->user()->username . ' - Applicant Family Created', [
            'data' => $family
        ]);

        return response()->json([
            'message' => 'Successfully added to record.',
            'family' => $family
        ]);
    }

    public function update(ApplicantFamilyRequest $request, Applicant $applicant, ApplicantFamily $family)
    {
        $this->authorize('allows', [$this->module, 'update']);

        $family->update($request->only($family->fillable));

        Log::info(auth()->user()->username . ' - Applicant Family Updated', [
            'data' => $family
        ]);

        return response()->json([
            'message' => 'Successfully updated record.',
            'family' => $family
        ]);
    }

    public function destroy(Applicant $applicant, ApplicantFamily $family)
    {
        $this->authorize('allows', [$this->module, 'delete']);

        $family->delete();

        Log::info(auth()->user()->username . ' - Applicant Family Deleted', [
            'data' => $family
        ]);

        return [
            'message' => 'Successfully deleted record.'
        ];
    }
}
