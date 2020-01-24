<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ApplicantEmployment extends Model implements Auditing
{
    use \OwenIt\Auditing\Auditable;

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
