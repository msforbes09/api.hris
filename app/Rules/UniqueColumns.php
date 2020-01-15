<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueColumns implements Rule
{
    protected $model;
    protected $colums;

    public function __construct($model = NULL, $columns = [])
    {
        $this->model = $model;
        $this->columns = $columns;
    }
    public function passes($attribute, $value)
    {
        $query = $this->model;

        foreach ($this->columns as $column) {
            $query = $query->where($column['name'], $column['value']);
        }

        $query->where('id', '!=', $this->model->id ?? '');

        return $query->count() === 0;
    }
    public function message()
    {
        return 'The :attribute is already taken.';
    }
}
