<?php

namespace App\Exports;

use App\Applicant;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ApplicantExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $model = new Applicant();

        return Applicant::all($model->getFillable());
    }

    public function headings(): array
    {
        $model = new Applicant();

        return $model->getFillable();
    }
}
