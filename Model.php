<?php

class Model {
    
    private int $id;
    private string $title;
    private string $creationDateTime;
    private string $editDateTime;
    private string $notes;
    private bool $isActive;

    public function __construct(
        int $id, 
        string $title, 
        string $creationDateTime, 
        string $editDateTime, 
        string $notes, 
        bool $isActive
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->creationDateTime = $creationDateTime;
        $this->editDateTime = $editDateTime;
        $this->notes = $notes;
        $this->isActive = $isActive;
    }

    // GETTERY

    public function getId() : int {
        return $this->id;
    }

    public function getTitle() : string {
        return $this->title;
    }

    public function getCreationDateTime() : string {
        return $this->creationDateTime;
    }

    public function getEditDateTime() : string {
        return $this->editDateTime;
    }

    public function getNotes() : string {
        return $this->notes;
    }

    public function getIsActive() : bool {
        return $this->isActive;
    }

    // SETTERY

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function setCreationDateTime(string $creationDateTime) {
        $this->creationDateTime = $creationDateTime;
    }

    public function setEditDateTime(string $editDateTime) {
        $this->editDateTime = $editDateTime;
    }

    public function setNotes(string $notes) {
        $this->notes = $notes;
    }

    public function setIsActive(bool $isActive) {
        $this->isActive = $isActive;
    }
}
?>