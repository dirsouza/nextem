<?php

declare(strict_types=1);

namespace App\Services;

class AuthService
{
    /**
     * @var array
     */
    private $statusCode;

    public function __construct()
    {
        $this->statusCode = config("statuscode");
    }

    /**
     * Autentica o usuário
     *
     * @param array $data
     * @return array
     */
    public function loginUser(array $data): array
    {
        try {
            $token = auth()->attempt([
                    'email' => $data['email'],
                    'password' => $data['password'],
                ]);

            if (!$token) {
                throw new \Exception("Usuário ou senha inválido", 401);
            }

            return [
                'success' => true,
                'data' => $this->respondWithToken($token),
                'code' => 200,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $this->valideStatusCode($e->getCode()) ? $e->getCode() : 500,
            ];
        }
    }

    /**
     * Desloga o usuário
     *
     * @return array
     */
    public function logoutUser(): array
    {
        try {
            auth()->logout();

            return [
                'success' => true,
                'message' => "Deslogado com sucesso!",
                'code' => 200,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $this->valideStatusCode($e->getCode()) ? $e->getCode() : 500,
            ];
        }
    }

    /**
     * Retorna os dados do token
     *
     * @param string $token
     * @return array
     */
    protected function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
    }

    /**
     * Valida de status code existe no array
     *
     * @param integer $code
     * @return boolean
     */
    private function valideStatusCode(int $code): bool
    {
        return array_key_exists($code, $this->statusCode);
    }
}
