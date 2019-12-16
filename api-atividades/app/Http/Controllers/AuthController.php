<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $response = $this->authService->newUser($request->all());

        return $this->responseAuthToken($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
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

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $response = $this->authService->refreshToken();

        return $this->responseAuthToken($response);
    }
}
