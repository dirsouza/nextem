<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ActivityController extends Controller
{
    use ResponseTrait;

    /**
     * @var ActivityService
     */
    private $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function activities(): JsonResponse
    {
        $response = $this->activityService->getActivities();

        return response()
            ->json(Arr::except($response, 'code'))
            ->setStatusCode($response['code']);
    }

    public function responsible(): JsonResponse
    {
        $response = $this->activityService->getResponsible();

        return response()
            ->json(Arr::except($response, 'code'))
            ->setStatusCode($response['code']);
    }

    public function status(): JsonResponse
    {
        $response = $this->activityService->getStatus();

        return response()
            ->json(Arr::except($response, 'code'))
            ->setStatusCode($response['code']);
    }

    public function create(Request $request): JsonResponse
    {
        $response = $this->activityService->createActivity($request->all());

        return response()
            ->json(Arr::except($response, 'code'))
            ->setStatusCode($response['code']);
    }

    public function update(int $id, Request $request)
    {
        $response = $this->activityService->updateActivity($id, $request->all());

        return response()
            ->json(Arr::except($response, 'code'))
            ->setStatusCode($response['code']);
    }
}
