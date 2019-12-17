<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

trait ResponseTrait
{
    /**
     * Retorna os dados de autenticação com a header 'Authorization' caso exista token
     *
     * @param array $response
     * @return JsonResponse
     */
    protected function responseAuthToken(array $response): JsonResponse
    {
        if (isset($response['data']) && !empty($response['data'])) {
            $token = "{$response['data']['token_type']} {$response['data']['access_token']}";

            return response()
                ->json(Arr::except($response, 'code'))
                ->setStatusCode($response['code'])
                ->header('Authorization', $token);
        }

        return $this->response($response);
    }

    /**
     * Retorna os dados em response normal
     *
     * @param array $response
     * @return JsonResponse
     */
    protected function response(array $response): JsonResponse
    {
        return response()
            ->json(Arr::except($response, 'code'))
            ->setStatusCode($response['code']);
    }
}
