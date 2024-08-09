<?php

namespace App\Infrastructure\Task;

use App\Domain\Task\Entities\Task as DomainTask;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Models\Task as EloquentTask;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function save(DomainTask $task)
    {
        $eloquentTask = EloquentTask::find($task->getId()) ?? new EloquentTask();
        $eloquentTask->title = $task->getTitle();
        $eloquentTask->description = $task->getDescription();
        $eloquentTask->status = $task->getStatus();
        $eloquentTask->save();

        // Set the ID of the domain entity
        if (!$task->getId()) {
            $task->setId($eloquentTask->id);
        }
    }

    public function findAll()
    {
        $tasks = EloquentTask::all();
        return $tasks->map(function ($eloquentTask) {
            return $this->toDomainEntity($eloquentTask);
        });
    }

    public function findById($id)
    {
        $eloquentTask = EloquentTask::find($id);
        if ($eloquentTask) {
            return $this->toDomainEntity($eloquentTask);
        }

        return null;
    }

    private function toDomainEntity(EloquentTask $eloquentTask)
    {
        $task = new DomainTask($eloquentTask->title, $eloquentTask->description);
        $task->setId($eloquentTask->id);
        $task->setStatus($eloquentTask->status);
        return $task;
    }
}
