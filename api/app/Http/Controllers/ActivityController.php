<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Services\ActivityService;
use Illuminate\Http\JsonResponse;

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

    /**
     * @return JsonResponse
     */
    public function activities(): JsonResponse
    {
        $response = $this->activityService->getActivities();

        return $this->response($response);
    }

    /**
     * @return JsonResponse
     */
    public function responsible(): JsonResponse
    {
        $response = $this->activityService->getResponsible();

        return $this->response($response);
    }

    /**
     * @return JsonResponse
     */
    public function status(): JsonResponse
    {
        $response = $this->activityService->getStatus();

        return $this->response($response);
    }

    /**
     * @param ActivityRequest $request
     * @return JsonResponse
     */
    public function create(ActivityRequest $request): JsonResponse
    {
        $response = $this->activityService->createActivity($request->all());

        return $this->response($response);
    }

    /**
     * @param int $id
     * @param ActivityRequest $request
     * @return JsonResponse
     */
    public function update(int $id, ActivityRequest $request)
    {
        $response = $this->activityService->updateActivity($id, $request->all());

        return $this->response($response);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        $response = $this->activityService->deleteActivity($id);

        return $this->response($response);
    }
}
