<?php

namespace App;

use App\Helpers\FullTextSearch;
use Illuminate\Support\Collection;
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

    protected $casts = [
        'birth_date' => 'date:Y-m-d',
    ];

    protected $searchable = [
        'first_name',
        'middle_name',
        'last_name'
    ];

    public function getFullNameAttribute()
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

    public function scopeSortedPagination($query)
    {
        $rowsPerPage = is_numeric(request('rowsPerPage')) ? request('rowsPerPage') : 10 ;
        $sort = is_numeric(request('sort')) ? request('sort') == 1 ? 'ASC' : 'DESC' : 'DESC';
        $sortBy = in_array(request('sortBy'), $this->getFillable()) ? request('sortBy') : 'id';

        return $query->where(function ($subQuery) {
            if (request('search'))
                $subQuery->search(urldecode(request('search')));
            })
            ->orderBy($sortBy, $sort)
            ->paginate($rowsPerPage);
    }

    public function scopeLevenshteinSearch($query)
    {
        $results = $query->selectRaw('*,
            (levenshtein(?, `last_name`) + levenshtein(?, `first_name`) + levenshtein(?, `middle_name`)) as match_diff',
            [request('last_name'), request('first_name'), request('middle_name')])
            ->orderBy('match_diff')
            ->limit(4)
            ->get();

        $exactMatch = $results->where('match_diff', 0)->first();

        $otherMatches = $results->where('id', '<>', $exactMatch ? $exactMatch->id : null)
            ->where('match_diff', '<', 4);

        return [
            'exactMatch' => $exactMatch,
            'otherMatches' => $otherMatches
        ];
    }
}
