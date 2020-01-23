<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class ApplicantController extends Controller
{
    public function index(Request $request, Applicant $applicant)
    {
        $rowsPerPage = (int) $request->rowsPerPage > 0 ? $request->rowsPerPage : 10;
        $descending = ($request->descending) ? 'DESC' : 'ASC';
        $sortBy = $this->validateSortby($request->sortBy, $applicant->getFillable());


        return Applicant::where( function($q) use ($request) {
                if ($request->search)
                    $q->search(urldecode($request->search));
            })
            ->orderBy($sortBy, $descending)
            ->paginate($rowsPerPage);
    }

    public function show(Applicant $applicant)
    {
        $applicant->families;

        $applicant->education;

        $applicant->employments;

        $applicant->applications;

        return $applicant;
    }

    public function store(ApplicantRequest $request, Applicant $applicant)
    {
        $applicant = Applicant::create($request->only($applicant->fillable));

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
        $applicant->employments()->delete();
        $applicant->education()->delete();
        $applicant->families()->delete();
        $applicant->delete();

        return [
            'message' => 'Successfully deleted applicant.',
        ];
    }

    protected function validateSortby($sortBy, $valids)
    {
        return (in_array($sortBy, $valids)) ? $sortBy : 'id';
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
            ->limit(4)
            ->get();

        $exactMatch = $applicants->where('match_diff', 0)->first();

        $otherMatches = $applicants->where('id', '<>', $exactMatch ? $exactMatch->id : null);

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
