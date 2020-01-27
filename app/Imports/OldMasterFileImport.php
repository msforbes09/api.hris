<?php

namespace App\Imports;

use App\Key;
use App\Keyword;
use App\Applicant;
use Carbon\Carbon;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\sheets;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OldMasterFileImport implements OnEachRow, WithHeadingRow, WithMultipleSheets, SkipsUnknownSheets, WithValidation
{
    use SkipsFailures;

    protected $row;

   public function headingRow()
    {
        return 5;
    }

    public function sheets(): array
    {
        return [
            'Master File' => new OldMasterFileImport()
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        abort(403, $sheetName . ' sheet was not found on uploaded file.');
    }

    public function rules(): array
    {
        return [
            'last_name' => 'required',
            'first_name' => 'required',
            'date_of_birth' => 'required|date',
        ];
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();
        dd($row);
        $applicant =  Applicant::updateOrCreate([
            'last_name' => Str::title($row['last_name']),
            'first_name' =>  Str::title($row['first_name']),
            'middle_name' => Str::title($row['middle_name']),
            'birth_date' =>  Carbon::create($row['date_of_birth'])
            ],[
            'current_address' =>  Str::title($row['current_address']),
            'permanent_address' =>  Str::title($row['address']),
            'birth_place' =>  Str::title($row['place_of_birth']),
            'gender' =>  $row['sex'],
            'height' => $row['height_ft'] . 'ft',
            'civil_status' =>  $row['civil_status'],
            'contact_no' =>  str_replace('-', '', $row['contact_nos']),
            'email' =>  $row['email_address'],
            'sss' =>  $row['sss'],
            'tin' =>  $row['tin'],
            'philhealth' =>  $row['philhealth'],
            'pagibig' =>  $row['pagibig']
        ]);

        if(isset($row['contact_person_in_case_of_emergency']))
        {
            $applicant->families()->updateOrCreate([
                'name' => Str::title($row['contact_person_in_case_of_emergency'])
                ],[
                'relationship' => Str::title($row['relation']),
                'address' => Str::title($row['emergency_contact']),
                'living' => 1,
                'emergency_contact' => 1
            ]);
        }
    }
}
