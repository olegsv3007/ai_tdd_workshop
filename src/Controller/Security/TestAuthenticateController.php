<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/test-authenticate', name: 'test_authenticate', methods: ['GET'])]
class TestAuthenticateController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return $this->json([
            'status' => Response::HTTP_OK,
        ]);
    }
}
