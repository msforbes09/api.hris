<?php

namespace App\Imports;

use App\Key;
use App\Keyword;
use App\Applicant;
use Carbon\Carbon;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\sheets;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OldInfoSheetImport implements OnEachRow, WithHeadingRow, WithMultipleSheets
{
    public function headingRow()
    {
        return 6;
    }

    public function sheets(): array
    {
        return [
            0 => new OldInfoSheetImport()
        ];
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $applicant =  Applicant::firstOrCreate([
            'last_name' => Str::title($row['last_name']),
            'first_name' =>  Str::title($row['first_name']),
            'middle_name' => Str::title($row['middle_name']),
            'birth_date' =>  Carbon::create($row['birthday']),
            ],[
            'current_address' =>  Str::title($row['current_address']),
            'permanent_address' =>  Str::title($row['permanent_address']),
            'birth_place' =>  Str::title($row['birthplace']),
            'gender' =>  $row['gender'],
            'civil_status' =>  $row['civilmarital_status'],
            'contact_no' =>  $row['contact_no'],
            'email' =>  $row['personal_email_address'],
            'sss' =>  $row['sss_no'],
            'tin' =>  $row['tin_no'],
            'philhealth' =>  $row['philhealth_no'],
            'pagibig' =>  $row['pag_ibig_id'],
            'pagibig_tracking' => $row['pag_ibig_tracking_number']
        ]);

        if(isset($row['contact_person']))
        {
            $applicant->families()->firstOrCreate([
                'name' => Str::title($row['contact_person']),
                ],[
                'relationship' => Str::title($row['contact_person_relation']),
                'address' => Str::title($row['contact_person_address']),
                'living' => 1,
                'emergency_contact' => 1
            ]);
        }
    }
}
