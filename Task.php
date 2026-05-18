<?php 
include_once("Model.php");
class Task extends Model {
    private bool $isDone;
    private string $startDateTime;
    private string $description;
    private string $deadline;
    private int $internalEventId;

public function __construct(
        int $id,
        string $title,
        bool $isDone,
        string $startDateTime,
        string $description,
        string $deadline,
        int $internalEventId,
         string $creationDateTime,
        string $editDateTime,
        string $notes,
        bool $isActive
    ) {
        parent::__construct($id, $title, $creationDateTime, $editDateTime, $notes, $isActive);
        $this->isDone = $isDone;
        $this->startDateTime = $startDateTime;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->internalEventId = $internalEventId;
    }

    public function getIsDone() : bool {
        return $this->isDone;
    }
    public function setIsDone(bool $isDone) {
        $this->isDone = $isDone;
    }


    public function getInternalEventId() : int {
        return $this->internalEventId;
    }
    public function setInternalEventId(int $internalEventId) {
        $this->internalEventId = $internalEventId;
    }


    public function getStartDateTime() : string {
        return $this->startDateTime;
    }
    public function setStartDateTime(string $startDateTime) {
        $this->startDateTime = $startDateTime;
    }

    
        public function getDescription() : string {
        return $this->description;
    }
    public function setDescription(string $description) {
        $this->description = $description;
    }

    
        public function getDeadline() : string {
        return $this->deadline;
    }
    public function setDeadline(string $deadline) {
        $this->deadline = $deadline;
    }
}