<?php

namespace App;

use App\Helpers\FullTextSearch;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Applicant extends Model implements Auditable
{
    use FullTextSearch, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'nick_name',
        'current_address',
        'permanent_address',
        'birth_date',
        'birth_place',
        'gender',
        'height',
        'weight',
        'civil_status',
        'tax_code',
        'citizenship',
        'religion',
        'contact_no',
        'email',
        'crn',
        'sss',
        'tin',
        'philhealth',
        'pagibig',
        'pagibig_tracking',
    ];

    protected $dates = [
        'birth_date',
        'created_at',
        'updated_at'
    ];

    protected $searchable = [
        'first_name',
        'middle_name',
        'last_name'
    ];

    public function fullname()
    {
        return $this->last_name .', '. $this->first_name .' '. $this->middle_name;
    }

    public function families()
    {
      return $this->hasMany('App\ApplicantFamily');
    }

    public function education()
    {
      return $this->hasMany('App\ApplicantEducation');
    }

    public function employments()
    {
      return $this->hasMany('App\ApplicantEmployment');
    }

    public function applications()
    {
      return $this->hasMany('App\Application');
    }
}
