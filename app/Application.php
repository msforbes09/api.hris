<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'date',
        'code',
        'branch_id',
        'client_id',
        'client_branch_id',
        'client_position_id',
        'interview_date',
        'interview_stat' ,
        'interview_remarks',
        'exam_date',
        'exam_stat',
        'exam_remarks',
        'medical_stat',
        'medical_remarks',
        'requirement_stat',
        'requirement_remarks',
        'status'
    ];

    protected $dates = [
        'date',
        'interview_date',
        'exam_date',
        'created_at',
        'updated_at'
    ];

    public function applicant()
    {
        return $this->hasOne('App\Applicant');
    }

    public function client()
    {
        return $this->hasOne('App\Client');
    }

    public function branch()
    {
        return $this->hasOne('App\ClientBranch');
    }

    public function position()
    {
        return $this->hasOne('App\ClientPosition');
    }
}
