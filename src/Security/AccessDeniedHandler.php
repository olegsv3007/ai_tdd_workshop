<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        return new JsonResponse([
            'error' => [
                'code' => $accessDeniedException->getCode(),
                'message' => $accessDeniedException->getMessage(),
            ],
        ], $accessDeniedException->getCode());
    }
}
