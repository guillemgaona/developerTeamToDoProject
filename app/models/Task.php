<?php

class Task extends Model {
    private ?int $id;
    private string $title;
    private string $description;
    private bool $status;
    private ?DateTime $startTime;
    private ?DateTime $endTime;
    private ?int $userId;

    private $jsonFile = ROOT_PATH . '/data/tasks.json';

    public function __construct(?int $id = null, string $title = " ", string $description = " ", bool $status = false, ?DateTime $startTime = null, ?DateTime $endTime = null, ?int $userID = null) {
        parent::__construct();
        $this->id = $id; 
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->userId = $userID;
    }

    public function getId(): int {
       return $this->id;
    }

    public function getTitle(): string {
       return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getStatus(): bool {
        return $this->status;
    }

    public function getStartTime(): DateTime {
        return $this->startTime;
    }

    public function getEndTime(): ?DateTime {
        return $this->endTime;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setStatus(bool $status): void {
        $this->status = $status;
    }

    public function setStartTime(DateTime $startTime): void {
        $this->startTime = $startTime;
    }

    public function setEndTime(DateTime $endTime): void {
        $this->endTime = $endTime;
    }

    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'userId' => $this->userId
        ];
    }

    public function fromArray($data): array {

        return [
            $data['id'] ?? null,
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['status'] ?? 'false',
            $data['startTime'] ?? null,
            $data['endTime'] ?? null,
            $data['userId'] ?? null
        ];

    }

    public function getAllTasks(): array {
        $json = file_get_contents(self::$jsonFile);
        $jsonDecoded = json_decode($json, true);

        if (empty($jsonDecoded)) {
            $jsonDecoded = [];
        }
        return $jsonDecoded;
    }

    public function saveTask(): void {
        $tasks = self::getAllTasks();
        
        // Check if task exists
        $index = -1;
        foreach ($tasks as $i => $task) {
            if ($task['id'] === $this->id) {
                $index = $i;
                break;
            }
        }
        
        if ($index >= 0) {
            // Update existing task
            $tasks[$index] = $this->toArray();
        } else {
            // Add new task
            $tasks[] = $this->toArray();
        }
        
        $this->saveToJson($tasks);
       
    }

    protected function saveToJson($data)
    {
        file_put_contents(
            $this->jsonFile,
            json_encode($data, JSON_PRETTY_PRINT)
        );
    }
}