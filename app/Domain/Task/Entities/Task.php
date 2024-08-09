<?php

namespace App\Domain\Task\Entities;

class Task
{
    private $id;
    private $title;
    private $description;
    private $status;

    const STATUS_TODO = 'To Do';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_COMPLETED = 'Completed';

    public function __construct($title, $description)
    {
        $this->title = $title;
        $this->description = $description;
        $this->status = self::STATUS_TODO;
    }

    // Mark as in progress
    public function markAsInProgress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    // Mark as completed
    public function markAsCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
    }

    // Getters and Setters
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
