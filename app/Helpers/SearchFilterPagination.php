<?php

namespace App\Helpers;

use Carbon\Carbon;

class SearchFilterPagination
{
    private $page;
    private $perPage;
    private $sortBy;
    private $sorting;
    private $keyword;
    private $filters;
    private $query;

    private function __construct($query)
    {
        $this->query = $query;
        $this->page = $this->getPage();
        $this->perPage = $this->getPerPage();
        $this->sortBy = $this->getSortBy();
        $this->sorting = $this->getSorting();
        $this->keyword = $this->getKeyword();
        $this->filters = $this->getFilters();
    }

    public static function paginate($query)
    {
        $instance = new self($query);

        return $instance->seachFilterThenPaginate();
    }

    protected function getKeyword()
    {
        return request('keyword');
    }

    protected function getPage()
    {
        return is_numeric(request('page')) ? request('page') : 1;
    }

    protected function getPerPage()
    {
        return is_numeric(request('per_page')) ? request('per_page') : 10;
    }

    protected function getSortBy()
    {
        return in_array(request('sortBy'), $this->query->getModel()->getFillable()) ? request('sort_by') : 'id';
    }

    protected function getSorting()
    {
        return is_numeric(request('sort')) ? request('sort') == 1 ? 'ASC' : 'DESC' : 'DESC';
    }

    protected function getFilters()
    {
        return json_decode(request('filters'), true);
    }

    protected function search()
    {
        return $this->query->where(function ($query) {
            if($this->keyword != null) $query->search(urldecode($this->keyword));
        });
    }

    protected function filter($query)
    {
        if ($this->filters != null)
        {
            foreach ($this->filters as $key => $value)
            {
                if ($key === 'date_range' && $this->query->getModel()->timestamps)
                {
                    $query->whereBetween($value[0], [
                        Carbon::parse($value[1]),
                        Carbon::parse($value[2])
                    ]);
                    continue;
                }

                $query->where($key, $value);
            }
        }

        return $query;
    }

    protected function applySorting($query)
    {
        return $query->orderBy($this->sortBy, $this->sorting);
    }

    protected function createPagination($query)
    {
        return $query->paginate($this->perPage);
    }

    protected function appendLinks($query)
    {
        return $query->appends([
            'page' => $this->page,
            'per_page' => $this->perPage,
            'sort_by' => $this->sortBy,
            'sorting' => $this->sorting,
            'keyword' => $this->keyword
        ]);
    }

    public function seachFilterThenPaginate()
    {
        return $this->appendLinks(
            $this->createPagination(
                $this->applySorting(
                    $this->filter(
                        $this->search()
                    )
                )
            )
        );
    }
}
