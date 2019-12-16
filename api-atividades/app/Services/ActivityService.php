<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Activity;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActivityService
{
    public function getActivities(): array
    {
        try {
            $activities = Activity::with(['users', 'status'])->get();

            if ($activities->isEmpty()) {
                throw new \Exception("Não foram encontradas Tarefas.", 404);
            }

            return [
                'success' => true,
                'data' => $activities,
                'code' => 200
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode() > 0 ? $e->getCode() : 500
            ];
        }
    }

    public function getResponsible(): array
    {
        try {
            $responsible = User::all();

            if ($responsible->isEmpty()) {
                throw new \Exception("Não foram encontrados Responsáveis.", 404);
            }

            return [
                'success' => true,
                'data' => $responsible,
                'code' => 200
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode() > 0 ? $e->getCode() : 500
            ];
        }
    }

    public function getStatus(): array
    {
        try {
            $status = Status::all();

            if ($status->isEmpty()) {
                throw new \Exception("Não foram encontrados Status.", 404);
            }

            return [
                'success' => true,
                'data' => $status,
                'code' => 200
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode() > 0 ? $e->getCode() : 500
            ];
        }
    }

    public function createActivity(array $data): array
    {
        DB::beginTransaction();
        try {
            $activity = Activity::create([
                    "activity" => $data["activity"],
                    "status_id" => $data["status"],
                    "deadline" => $data["deadline"]
                ]);

            if (!$activity->exists()) {
                throw new \Exception("Não foi possível realizar o cadastro da tarefa.", 500);
            }

            $activity->users()->attach($data["responsible"]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Tarefa criada com sucesso!",
                'code' => 201
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode() > 0 ? $e->getCode() : 500
            ];
        }
    }

    public static function updateActivity(int $id, array $data): array
    {
        DB::beginTransaction();
        try {
            $activity = Activity::find($id);

            if (!$activity->exists()) {
                throw new \Exception("Tarefa não encontrada.", 404);
            }

            $result = $activity->update([
                "activity" => $data["activity"],
                "status_id" => $data["status"],
                "deadline" => $data["deadline"]
            ]);

            if (!$result) {
                throw new \Exception("Não foi possível realizar a atualização da tarefa.", 500);
            }

            $activity->users()->sync($data["responsible"]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Tarefa atualizada com sucesso!",
                'code' => 201
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode() > 0 ? $e->getCode() : 500
            ];
        }
    }
}