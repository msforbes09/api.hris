<?php

namespace App\Imports;

use App\Key;
use App\Keyword;
use App\Applicant;
use Carbon\Carbon;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\sheets;
use App\Http\Requests\ApplicantRequest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ApplicantImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
    public $success;
    public $failed;
    public $total;

    public function __construct()
    {
        $this->success = [];
        $this->failed = [];
        $this->total = 0;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        $request = new ApplicantRequest();

        return $request->rules();
    }

    protected function hasDuplicates($row)
    {
        return Applicant::where('last_name', $row['last_name'])
            ->where('first_name', $row['first_name'])
            ->count() > 0;
    }

    public function model(array $row)
    {
        $this->total++;

        if ($this->hasDuplicates($row))
        {
            array_push($this->failed, [
                'reason' => 'Applciant already exists.',
                $row
            ]);

            return null;
        }

        array_push($this->success, $row);

        return new Applicant([
            'last_name' => Str::title($row['last_name']),
            'first_name' =>  Str::title($row['first_name']),
            'middle_name' => Str::title($row['middle_name']),
            'nick_name' => Str::title($row['nick_name']),
            'current_address' =>  Str::title($row['current_address']),
            'permanent_address' =>  Str::title($row['permanent_address']),
            'birth_date' =>  Carbon::create($row['birth_date']),
            'birth_place' =>  Str::title($row['birth_place']),
            'gender' =>  $row['gender'],
            'height' => $row['height'],
            'weight' => $row['weight'],
            'civil_status' =>  $row['civil_status'],
            'tax_code' => $row['tax_code'],
            'citizenship' => $row['citizenship'],
            'religion' => $row['religion'],
            'contact_no' =>  $row['contact_no'],
            'email' =>  $row['email'],
            'crn' => $row['crn'],
            'sss' =>  $row['sss'],
            'tin' =>  $row['tin'],
            'philhealth' =>  $row['philhealth'],
            'pagibig' =>  $row['pagibig'],
            'pagibig_tracking' => $row['pagibig_tracking']
        ]);
    }
}
