<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Activity;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ActivityService
{
    /**
     * Retorna a lista de Tarefas
     *
     * @return array
     */
    public function getActivities(): array
    {
        try {
            $activities = Activity::with(['users', 'status'])->get();

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

    /**
     * Retorna a lista de Usuários
     *
     * @return array
     */
    public function getResponsible(): array
    {
        try {
            $responsible = User::all();

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

    /**
     * Retorna a lista de Status
     *
     * @return array
     */
    public function getStatus(): array
    {
        try {
            $status = Status::all();

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

    /**
     * Cria uma nova tarefa
     *
     * @param array $data
     * @return array
     */
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
        } catch (QueryException $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => "Não foi possível realizar o cadastro da tarefa 'SQLSTATE[{$e->getCode()}]'",
                'code' => 500
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

    /**
     * Atualiza um tarefa
     *
     * @param int $id
     * @param array $data
     * @return array
     */
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
                'code' => 200
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

    /**
     * Deleta uma tarefa
     *
     * @param int $id
     * @return array
     */
    public static function deleteActivity(int $id): array
    {
        DB::beginTransaction();
        try {
            $activity = Activity::find($id);

            if (!$activity->exists()) {
                throw new \Exception("Tarefa não encontrada.", 404);
            }

            $activity->users()->detach();

            $result = $activity->delete();

            if (!$result) {
                throw new \Exception("Não foi possível realizar a exclusão da tarefa.", 500);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Tarefa excluída com sucesso!",
                'code' => 200
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