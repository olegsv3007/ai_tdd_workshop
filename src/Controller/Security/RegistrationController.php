<?php

namespace App\Controller\Security;

use App\DDD\User\Domain\Command\UserRegistrationCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('api/registration', name: 'registration', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        UserRegistrationCommand $command,
    ): JsonResponse {
        $user = $this->messageBus->dispatch($command)->last(HandledStamp::class)->getResult();

        return $this->json([
            'token' => $user->getTokens()->first()->getValue(),
            'email' => $user->getUserIdentifier(),
        ]);
    }
}
