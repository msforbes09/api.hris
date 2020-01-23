<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
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
        'sss',
        'tin',
        'philhealth',
        'pagibig',
    ];

    protected $dates = [
        'birth_date',
        'created_at',
        'updated_at'
    ];

    public function families()
    {
      return $this->hasMany('App\ApplicantFamily');
    }

    public function educations()
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
