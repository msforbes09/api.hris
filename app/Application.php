<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Application extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'date',
        'code',
        'applicant_id',
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

    public function generateCode($branchId)
    {
        $branch = Branch::findOrFail($branchId);

        return $branch->code . '-' . Carbon::now()->timestamp;
    }
}
