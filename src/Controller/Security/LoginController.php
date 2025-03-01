<?php

namespace App\Controller\Security;

use App\DDD\User\Domain\Command\UserLoginCommand;
use App\DDD\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('api/login_check', name: 'login_check', methods: ['POST'])]
    public function loginCheck(#[CurrentUser] ?User $user): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'token' => $user->getTokens()->first()?->getValue(),
            'email' => $user->getUserIdentifier(),
        ]);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(#[MapRequestPayload] UserLoginCommand $command): JsonResponse
    {
        $user = $this->messageBus->dispatch($command)->last(HandledStamp::class)->getResult();

        return $this->json([
            'token' => $user->getTokens()->first()->getValue(),
            'email' => $user->getUserIdentifier(),
        ]);
    }
}
