<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantRequest;

class ApplicantController extends Controller
{
    public function index()
    {
        return Applicant::paginate(request('per_page') ?? 10);
    }

    public function show(Applicant $applicant)
    {
        return $applicant;
    }

    public function store(ApplicantRequest $request)
    {
        $uniqueCode = ['code' => Str::random(9)];

        while (Applicant::where('code', $uniqueCode['code'])->count() > 0) {
            $uniqueCode['code'] = Str::random(9);
        }

        $applicant = Applicant::create(request()->merge($uniqueCode)->toArray());

        return response()->json([
            'message' => 'Successfully created applicant.',
            'applicant' => $applicant
        ]);
    }

    public function update(ApplicantRequest $request, Applicant $applicant)
    {
        $applicant->fill(request()->toArray());

        $applicant->save();

        return response()->json([
            'message' => 'Successfully updated applicant.',
            'applicant' => $applicant
        ]);
    }

    public function destroy(Applicant $applicant)
    {
        $applicant->delete();

        return [
            'message' => 'Successfully deleted applicant.',
        ];
    }
}
