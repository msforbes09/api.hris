<?php

namespace App\Rules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\Rule;

class UniqueColumns implements Rule
{
    protected $id;
    protected $model;
    protected $columns;
    protected $hasTrash;

    public function __construct(Model $model = null, $columns = [], $hasTrash = false)
    {
        $this->id = $model ? $model->id : 0;
        $this->columns = $columns;
        $this->model = $hasTrash
            ? $model->withTrashed()
            : $model;
    }

    public function passes($attribute, $value)
    {
        $query = $this->model;

        foreach ($this->columns as $column) {
            $query = $query->where($column['name'], $column['value']);
        }

        return $query
            ->get()
            ->except($this->id)
            ->count() === 0;
    }

    public function message()
    {
        return 'The :attribute is already taken.';
    }
}
