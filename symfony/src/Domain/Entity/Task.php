<?php

namespace App\Domain\Entity;

use App\Domain\Enum\TaskPriority;
use App\Domain\Enum\TaskStatus;
use App\Domain\Enum\TaskType;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Task
{
    private ?int $id = null;
    private string $title;
    private string $description;
    private TaskStatus $status;
    private TaskType $type;
    private TaskPriority $priority;
    private ?string $assignee = null;
    private string $reporter;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private ?float $estimatedHours = null;

    /**
     * @var Collection<int, TaskTag>
     */
    private Collection $tags;

    public function __construct(
        string $title,
        string $description,
        TaskStatus $status,
        TaskType $type,
        TaskPriority $priority,
        string $reporter,
        ?string $assignee = null,
        ?float $estimatedHours = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->type = $type;
        $this->priority = $priority;
        $this->reporter = $reporter;
        $this->assignee = $assignee;
        $this->estimatedHours = $estimatedHours;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->markAsUpdated();
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        $this->markAsUpdated();
        return $this;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): self
    {
        $this->status = $status;
        $this->markAsUpdated();
        return $this;
    }

    public function getType(): TaskType
    {
        return $this->type;
    }
    
    public function setType(TaskType $type): self
    {
        $this->type = $type;
        $this->markAsUpdated();
        return $this;
    }
    
    public function getPriority(): TaskPriority
    {
        return $this->priority;
    }
    
    public function setPriority(TaskPriority $priority): self
    {
        $this->priority = $priority;
        $this->markAsUpdated();
        return $this;
    }
    
    public function getAssignee(): ?string
    {
        return $this->assignee;
    }
    
    public function setAssignee(?string $assignee): self
    {
        $this->assignee = $assignee;
        $this->markAsUpdated();
        return $this;
    }

    public function getReporter(): string
    {
        return $this->reporter;
    }

    public function setReporter(string $reporter): self
    {
        $this->reporter = $reporter;
        $this->markAsUpdated();
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    public function getEstimatedHours(): ?float
    {
        return $this->estimatedHours;
    }
    
    public function setEstimatedHours(?float $estimatedHours): self
    {
        $this->estimatedHours = $estimatedHours;
        $this->markAsUpdated();
        return $this;
    }
    
    /**
     * @return Collection<int, TaskTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }
    public function preUpdate(): void
    {
        $this->markAsUpdated();
    }
    
    public function addTag(TaskTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setTask($this);
        }
        
        $this->markAsUpdated();
        return $this;
    }
    
    public function removeTag(TaskTag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            if ($tag->getTask() === $this) {
                $tag->setTask(null);
            }
        }
        
        $this->markAsUpdated();
        return $this;
    }

    private function markAsUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
    
    /**
     * @param Collection<int, TaskTag> $tags
     */
    public function setTags(Collection $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function clearTags(): self
    {
        foreach ($this->tags->toArray() as $tag) {
            $this->removeTag($tag);
        }

        $this->markAsUpdated();

        return $this;
    }
}
