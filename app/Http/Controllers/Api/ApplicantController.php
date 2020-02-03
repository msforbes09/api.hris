<?php

namespace App\Http\Controllers\Api;

use App\Applicant;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ApplicantExport;
use App\Imports\ApplicantImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ApplicantRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Pagination\LengthAwarePaginator;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $rowsPerPage = is_numeric(request('rowsPerPage')) ? request('rowsPerPage') : 10 ;
        $descending = is_numeric(request('descending')) ? request('descending') == 1 ? 'ASC' : 'DESC' : 'DESC';
        $sortBy = $this->validateSortby(request('sortBy'), Applicant::getModel()->getFillable());

        return Applicant::where( function($query) {
                if (request('search'))
                    $query->search(urldecode(request('search')));
            })
            ->orderBy($sortBy, $descending)
            ->paginate($rowsPerPage);
    }

    public function show(Applicant $applicant)
    {
        return $applicant->load(['families', 'education', 'employments', 'applications']);
    }

    public function store(ApplicantRequest $request)
    {
        $applicant = Applicant::create(request()->only(Applicant::getModel()->getFillable()));

        Log::info(auth()->user()->username . ' has createdn an Applicant.', ['data' => $applicant]);

        return [
            'message' => 'Successfully created applicant.',
            'applicant' => $applicant
        ];
    }

    public function update(ApplicantRequest $request, Applicant $applicant)
    {
        $applicant->update(request()->only($applicant->getFillable()));

        Log::info(auth()->user()->username . ' has updated an Applicant.', ['data' => $applicant]);

        return [
            'message' => 'Successfully updated applicant.',
            'applicant' => $applicant
        ];
    }

    public function destroy(Applicant $applicant)
    {
        $applicant->delete();

        Log::info(auth()->user()->username . ' has deleted an Applicant.', ['data' => $applicant]);

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

    protected function validateHeadings($file)
    {
        $headings = (new HeadingRowImport)->toArray($file)[0][0];

        $requireds = [
            'last_name', 'first_name', 'middle_name', 'current_address', 'permanent_address', 'birth_date', 'birth_place',
            'gender', 'height', 'civil_status', 'contact_no', 'email', 'sss', 'tin', 'philhealth', 'pagibig',
        ];

        foreach ($requireds as $value) {
            if (!in_array($value, $headings)) {
                abort(403, $value .' heading is not found.');
            }
        }
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt|max:3000']);
        $file = $request->file('file');

        $this->validateHeadings($file);

        $import = new ApplicantImport();
        Excel::import($import, $file);

        return [
            'message' => 'Done importing '. count($import->success) . ' out of '. $import->total .' applicants.',
            'total' => $import->total,
            'success' => $import->success,
            'failed' => $import->failed
        ];
    }

    public function export()
    {
        return Excel::download(new ApplicantExport(), 'applicants_' . Carbon::now() .'.csv');
    }

    public function template()
    {
        return response()->download(storage_path('app/applicant_template.csv'));
    }
}
