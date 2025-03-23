<?php

namespace App\Infrastructure\Bus;

use App\Application\Bus\CommandBusContract;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusContract
{
    private MessageBusInterface $messageBus;
    
    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }
    
    /**
     * {@inheritDoc}
     */
    public function dispatch(object $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
