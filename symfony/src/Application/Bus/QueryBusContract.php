<?php

namespace App\Application\Bus;

interface QueryBusContract
{
    /**
     * Dispatch a query and return the result directly
     *
     * @template T
     *
     * @param object $query The query to dispatch
     *
     * @return T The result from the query handler
     */
    public function query(object $query): mixed;
}
