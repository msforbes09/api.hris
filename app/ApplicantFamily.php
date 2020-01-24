<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ApplicantFamily extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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
