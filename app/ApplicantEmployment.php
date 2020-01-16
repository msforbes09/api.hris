<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicantEmployment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'company',
        'address',
        'date_from',
        'date_to',
        'position',
        'salary',
        'leaving_reason',
    ];
}
