<?php

namespace App\Infrastructure\Bus;

use App\Application\Bus\QueryBusContract;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class QueryBus implements QueryBusContract
{
    private MessageBusInterface $messageBus;
    
    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * {@inheritDoc}
     */
    public function query(object $query): mixed
    {
        $envelope = $this->messageBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        
        if (!$handledStamp instanceof HandledStamp) {
            throw new \RuntimeException('Query bus did not return a handled stamp.');
        }
        
        return $handledStamp->getResult();
    }
}
