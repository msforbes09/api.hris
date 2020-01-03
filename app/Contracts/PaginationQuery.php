<?php

namespace App\Contracts;

class PaginationQuery
{
    public $allowedOrderByFilter = ['id'];

    public function getPaginationParams()
    {
        $perPage = is_numeric(request('per_page')) ? request('per_page') : 10;
        $orderBy = $this->filterOrderby(request('order_by'));
        $order = $this->filterOrder(request('order'));

        return (Object) [
            'perPage' => $perPage,
            'orderBy' => $orderBy,
            'order' => $order
        ];
    }

    protected function filterOrderby($orderBy)
    {
        $allowed = $this->allowedOrderByFilter;

        return in_array($orderBy, $allowed) ? $orderBy : $allowed[0];
    }

    protected function filterOrder($order)
    {
        $allowed = ['asc', 'desc'];

        return in_array($order, $allowed) ? $order : $allowed[0];
    }
}