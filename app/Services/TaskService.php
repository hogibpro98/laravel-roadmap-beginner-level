<?php

namespace App\Services;

use App\Repositories\Task\TaskRepository;

class TaskService
{
    protected $repository;

    public function __construct(
        protected TaskRepository $taskRepository
    ) {
        $this->repository = $taskRepository;
    }

    public function getList()
    {
        return $this->repository->getList();
    }
}
