<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $response = $this->authService->loginUser($request->all());

        return $this->responseAuthToken($response);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $response = $this->authService->logoutUser();

        return $this->responseAuthToken($response);
    }
}
