<?php

namespace App\Domain\Task\Repositories;

use App\Domain\Task\Entities\Task;

interface TaskRepositoryInterface
{
    public function save(Task $task);
    public function findAll();
    public function findById($id);
}
