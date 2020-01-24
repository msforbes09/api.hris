<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicantFamily extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'relationship',
        'name',
        'address',
        'occupation',
        'birth_date',
        'living',
        'contact_no',
        'emergency_contact'
    ];

    protected $dates = [
        'birth_date'
    ];
}
