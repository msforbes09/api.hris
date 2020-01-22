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
        $applicant->families;

        $applicant->educations;

        $applicant->employments;

        $applicant->applications;

        return $applicant;
    }

    public function store(ApplicantRequest $request, Applicant $applicant)
    {
        $uniqueCode = ['code' => Str::random(9)];

        while (Applicant::where('code', $uniqueCode['code'])->count() > 0) {
            $uniqueCode['code'] = Str::random(9);
        }

        $applicant = Applicant::create($request->merge($uniqueCode)->only($applicant->fillable));

        return response()->json([
            'message' => 'Successfully created applicant.',
            'applicant' => $applicant
        ]);
    }

    public function update(ApplicantRequest $request, Applicant $applicant)
    {
        $applicant->update($request->only($applicant->fillable));

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

    public function applicantCheck(Request $request)
    {
        $data = request()->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => ''
        ]);

        $applicants = Applicant::selectRaw('*,
            (levenshtein(?, `last_name`) + levenshtein(?, `first_name`) + levenshtein(?, `middle_name`)) as match_diff',
            [$data['last_name'], $data['first_name'], $data['middle_name']])
            ->orderBy('match_diff')
            ->get();

        $exactMatch = Applicant::find($applicants->where('match_diff', 0)->first());


        $otherMatches = $applicants->whereBetween('match_diff', [1, 10])
            ->slice(0, 3);

        if($exactMatch != null || $otherMatches->count() > 0) {
            return response()->json([
                'message' => $exactMatch ? 'Applicant record found.' : 'Applicant record not found. Please check other matches.',
                'exactMatch' => $exactMatch,
                'otherMatches' => $otherMatches
            ]);
        }

        return [
            'message' => 'Applicant record doesn\'t exist.'
        ];
    }
}
