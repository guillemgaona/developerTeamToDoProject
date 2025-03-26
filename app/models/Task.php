<?php

class Task extends Model {
    private ?int $id;
    private string $title;
    private string $description;
    private Status $status;
    private ?DateTime $startTime;
    private ?DateTime $endTime;
    private ?int $userId;

    private string $jsonFile;
    private array $data = [];

    public function __construct(?int $id = null, string $title = " ", string $description = " ", Status $status = Status::pending, ?DateTime $startTime = null, ?DateTime $endTime = null, ?int $userID = null) {
        parent::__construct();
        $this->id = $id; 
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->userId = $userID;
        $this->jsonFile = ROOT_PATH . '/data/tasks.json';
        $this->data = $this->loadData();
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

    public function getStatus(): Status {
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

    public function setStatus(Status $status): void {
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

    public function loadData(): array {
        if (!file_exists($this->jsonFile)) {
            return [];
        }
        
        $json = file_get_contents($this->jsonFile);
        $data = json_decode($json, true);
        
        return $data ?? [];
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'startTime' => $this->startTime?->format('Y-m-d H:i:s'),
            'endTime' => $this->endTime?->format('Y-m-d H:i:s'),
            'userId' => $this->userId
        ];
    }

    public function fromArray($data): array {

        return [
            $data['id'] ?? null,
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['status'] ?? 'pending',
            $data['startTime'] ?? null,
            $data['endTime'] ?? null,
            $data['userId'] ?? null
        ];

    }

    public function fetchByStatus($status)
    {
        return array_filter($this->data, function($task) use ($status) {
            return $task['status'] === $status;
        });
    }

    protected function _saveData()
    {
        file_put_contents(
            $this->jsonFile,
            json_encode($this->data, JSON_PRETTY_PRINT)
        );
    }
    
    public function save($data = array()){
        if (isset($data['id'])) {
            foreach ($this->data as $key => $item) {
                if ($item['id'] == $data['id']) {
                    $this->data[$key] = array_merge($item, $data); 
                    $this->_saveData();
                    return $data['id'];
                }
            }
            return false;
        } 
        else {
            $data['id'] = uniqid();
            
            $this->data[] = $data;
            
            $this->_saveData();
            return $data['id'];
        }
    }
    

    public function fetchOne($id)
    {
        foreach ($this->data as $item) {
            if ($item['id'] == $id) { 
                return $item;
            }
        }
        
    }

    public function delete($id){
        
        foreach ($this->data as $key => $item) {
            if ($item['id'] == $id) {

                unset($this->data[$key]);
                
                $this->data = array_values($this->data);
                
                $this->_saveData();
                return true;
            }
        }
        
        return false;
    }

    public function fetchByTaskTitle($taskTitle){

        return array_filter($this->data, function($task) use ($taskTitle) {
            return $task['title'] === $taskTitle;
        });
    }

}

enum Status: string {
    case pending = 'pending';
    case in_progress = 'in_progress';
    case done = 'done';   
}