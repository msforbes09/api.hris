<?php

namespace App\Rules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\Rule;

class UniqueColumns implements Rule
{
    protected $model;
    protected $columns;

    public function __construct(Model $model = null, $columns = [])
    {
        $this->model = $model;
        $this->columns = $columns;
    }

    public function passes($attribute, $value)
    {
        $query = $this->model::withTrashed();

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
