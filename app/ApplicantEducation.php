<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicantEducation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'level',
        'school',
        'year_from',
        'year_to',
        'details',
    ];
}
