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
    public function index()
    {
        return Applicant::sortedPagination();
    }

    public function show(Applicant $applicant)
    {
        return $applicant->load(['families', 'education', 'employments', 'applications']);
    }

    public function store(ApplicantRequest $request)
    {
        $requestData = request()->only(Applicant::getModel()->getFillable());

        $applicant = Applicant::create($requestData);

        Log::info(auth()->user()->username . ' has created an Applicant.', ['data' => $applicant]);

        return [
            'message' => 'Successfully created applicant.',
            'applicant' => $applicant
        ];
    }

    public function update(ApplicantRequest $request, Applicant $applicant)
    {
        $requestData = request()->only($applicant->getFillable());

        $applicant->update($requestData);

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

    public function applicantCheck()
    {
        request()->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => ''
        ]);

        $results = Applicant::LevenshteinSearch();

        if($results['exactMatch'] != null || $results['otherMatches']->count() > 0) {
            return response()->json([
                'message' => $results['exactMatch'] ? 'Applicant record found.' : 'Applicant record not found. Please check other matches.',
                'exactMatch' => $results['exactMatch'],
                'otherMatches' => $results['otherMatches']
            ]);
        }

        return [
            'message' => 'Applicant record doesn\'t exist.'
        ];
    }

    public function import()
    {
        request()->validate(['file' => 'required|mimes:csv,txt|max:3000']);
        $file = request()->file('file');

        $this->validateImportHeadings($file);

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

    protected function validateImportHeadings($file)
    {
        $headings = (new HeadingRowImport)->toArray($file)[0][0];

        $requireds = Arr::except(Applicant::getModel()->getFillable(), ['id', 'create_at', 'updated_at']);

        foreach ($requireds as $value) {
            if (!in_array($value, $headings)) {
                abort(403, $value .' heading is not found.');
            }
        }
    }
}
