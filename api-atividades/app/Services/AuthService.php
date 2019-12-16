<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * Cria um novo usuário
     *
     * @param array $data
     * @return array
     */
    public function newUser(array $data): array
    {
        DB::beginTransaction();
        try {
            $usuario = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                ]);

            if (!$usuario->exists()) {
                throw new \Exception("Não foi possível criar o usuário.", 500);
            }

            DB::commit();

            $token = auth()->login($usuario);

            return [
                'success' => true,
                'data' => $this->respondWithToken($token),
                'code' => 201,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode() > 0 ? $e->getCode() : 500,
            ];
        }
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
                throw new \Exception("Não autorizado", 401);
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
                'code' => $e->getCode() > 0 ? $e->getCode() : 500,
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
                'code' => $e->getCode() > 0 ? $e->getCode() : 500,
            ];
        }
    }

    /**
     * @return array
     */
    public function refreshToken(): array
    {
        try {
            $newToken = auth()->refresh();

            return [
                'success' => true,
                'data' => $this->respondWithToken($newToken),
                'code' => 200,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode() > 0 ? $e->getCode() : 500,
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
}
