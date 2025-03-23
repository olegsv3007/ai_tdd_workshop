<?php

namespace App\Application\Bus;

interface CommandBusContract
{
    /**
     * Dispatch a command without expecting a return value
     *
     * @param object $command The command to dispatch
     *
     * @return void
     */
    public function dispatch(object $command): void;
}
