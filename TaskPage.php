<?php
require_once("Page.php");
require_once("Task.php");


class TaskPage extends Page {

private Task $model;

protected function passTitle(): string
    {
        return "TASK";
    }

    protected function passTableName(): string
    {
        return "tasks";
    }

     public function getModel() : Task {
        return $this->model;
    }

    public function setModel(Task $model) {
        $this->model = $model;
    }

    protected function enterModelDataFromForm(): void {
        $this->setModel(new Task (
            intval($_POST['Id'] ?? 0),
            $_POST['Title'] ?? '',
            isset($_POST['IsDone']),
            $_POST['StartDateTime'] ?? '',
            $_POST['Description'] ?? '',
            $_POST['Deadline'] ?? '',
            intval($_POST['InternalEventId'] ?? 0),
            $_POST['CreationDateTime'] ?? date('Y-m-d H:i:s'),
            $_POST['EditDateTime'] ?? date('Y-m-d H:i:s'),
            $_POST['Notes'] ?? '',
            isset($_POST['IsActive'])
            ));
    }

        protected function generateViewAll(): string
    {
        $db = $this->openConnection();
        $stmt = $db->prepare("
            SELECT t.*, ie.Title AS EventTitle 
            FROM " . $this->getTableName() . " t
            LEFT JOIN internalevents ie ON t.InternalEventId = ie.Id
        ");

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $temp = '<div class="container"><div class="row">';

        foreach ($rows as $row) {
            $eventName = $row["EventTitle"] ?? 'N.N.';
        $temp .= '
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-2">' . $row["Title"] . '</h5>
                        <h6 class="card-subtitle mb-3 text-muted">I.E. ' . $eventName . '</h6>
                        <p class="card-text">' . $row["Description"] . '</p>
                    </div>
                    <div class="card-footer">
                    <form method="POST" >
                    <input name="Id" value= " '.$row["Id"].' " type="hidden" >
                    <button class="btn btn-primary" value="'.self::EDIT_VIEW.'" name="'.self::ACTION.'">Edit</button>
                    <button class="btn btn-danger"  value="'.self::DELETE.'" name="'.self::ACTION.'">Delete</button>
                    </form>                       
                    </div>
                </div>
            </div>
            ';
        }

        $temp .= '</div></div>';

        return $temp;
    }

   protected function generateViewEdit(): string
    {
        $db = $this->openConnection();
        
        // 1. Pobieramy listę wydarzeń do selecta
        $stmt = $db->prepare("SELECT Id, Title FROM internalevents WHERE IsActive = 1");
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Pobieramy dane konkretnego zadania do edycji
        $stmt = $db->prepare("SELECT * FROM ".$this->getTableName()." where Id= :Id");
        $stmt->bindValue(":Id", $_POST["Id"], PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        // 3. Budujemy opcje selecta z użyciem prostego if/else (tzw. operatora trójargumentowego)
        $optionsHtml = '';
        foreach ($events as $event) {
            // Jeśli ID wydarzenia zgadza się z kluczem obcym zadania, dodaj słówko 'selected'
            $selected = ($event["Id"] == $row["InternalEventId"]) ? 'selected' : '';
            $optionsHtml .= '<option value="' . $event["Id"] . '" ' . $selected . '>' . $event["Title"] . '</option>';
        }

        // 4. Zwracamy uzupełniony formularz
        return '
    <form method="POST" action="">
        <div class="container">
        <div class="row gy-3">
            <div class="col-md-12 col-lg-6">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round align-middle">label</i>
                        Title
                    </label>
                    <input name="Title" class="form-control validate" value="'.$row["Title"].'">
                </div>
            </div>
            
            <div class="col-md-12 col-lg-6">
                <div class="input-group">
                    <label class="input-group-text" for="InternalEventId">
                        <i class="material-icons-round align-middle">bookmark</i>
                        Internal event
                    </label>
                    <select name="InternalEventId" id="InternalEventId" class="form-select">
                        ' . $optionsHtml . '
                    </select>
                </div>
            </div>   
            
            <div class="col-md-12 col-lg-5">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round palette-accent-text-color align-middle">today</i>
                        Start time
                    </label>
                    <input name="StartDateTime" class="form-control validate" type="date" value="'.date('Y-m-d', strtotime($row["StartDateTime"])).'">
                </div>
            </div>
            
            <div class="col-md-12 col-lg-5">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round palette-accent-text-color align-middle">event</i>
                        Deadline
                    </label>
                    <input name="Deadline" class="form-control validate" type="date" value="'.date('Y-m-d', strtotime($row["Deadline"])).'">
                </div>
            </div>
            
            <div class="col-md-12 col-lg-2">
                <div class="row">
                    <div class="col-auto">
                        <label class="form-check-label">                        
                            <i class="material-icons-round align-middle">check</i>
                            Done
                        </label>
                    </div>
                    <div class="form-switch form-check col-auto">
                        <input name="IsDone" '. ($row["IsDone"] ? "checked" : "") .' class="form-check-input validate" type="checkbox">                        
                    </div>
                </div>
            </div>
            
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">feed</i>
                    Description
                </label>
                <textarea name="Description" class="form-control validate">'.$row["Description"].'</textarea>
            </div>
            
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">notes</i>
                    Notes
                </label>
                <textarea name="Notes" class="form-control validate">'.$row["Notes"].'</textarea>
            </div> 
                   
            <div class="col-sm-12">
                <input name="Id" value="'.$row["Id"].'" type="hidden">
                
                <button name="'.self::ACTION.'" value="'.self::EDIT.'" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </form>';
    }

    protected function generateViewAdd(): string
    {
        $db = $this->openConnection();
        $stmt = $db->prepare("SELECT Id, Title FROM internalevents WHERE IsActive = 1");
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $optionsHtml = '';
        foreach ($events as $event) {
            $optionsHtml .= '<option value="' . $event["Id"] . '">' . $event["Title"] . '</option>';
        }

        return '
    <form method="POST" action="">
        <div class="container">
        <div class="row gy-3">
            <div class="col-md-12 col-lg-6">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round align-middle">label</i>
                        Title
                    </label>
                    <input name="Title" class="form-control validate">
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="input-group">
                    <label class="input-group-text" for="InternalEventId">
                        <i class="material-icons-round align-middle">bookmark</i>
                        Internal event
                    </label>
                    <select name="InternalEventId" id="InternalEventId" class="form-select">
                        ' . $optionsHtml . '
                    </select>
                </div>
            </div>   
            
            <div class="col-md-12 col-lg-5">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round palette-accent-text-color align-middle">today</i>
                        Start time
                    </label>
                    <input name="StartDateTime" class="form-control validate" type="date">
                </div>
            </div>
            <div class="col-md-12 col-lg-5">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round palette-accent-text-color align-middle">event</i>
                        Deadline
                    </label>
                    <input name="Deadline" class="form-control validate" type="date">
                </div>
            </div>
            <div class="col-md-12 col-lg-2">
                <div class="row">
                    <div class="col-auto">
                        <label class="form-check-label">                        
                            <i class="material-icons-round align-middle">check</i>
                            Done
                        </label>
                    </div>
                    <div class="form-switch form-check col-auto">
                        <input name="IsDone" class="form-check-input validate" type="checkbox">                        
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">feed</i>
                    Description
                </label>
                <textarea name="Description" class="form-control validate"></textarea>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">notes</i>
                    Notes
                </label>
                <textarea name="Notes" class="form-control validate"></textarea>
            </div> 
                   
            <div class="col-sm-12">
                <button name="'.self::ACTION.'" value="'.self::ADD_NEW.'" class="btn btn-primary">Create </button>
            </div>
        </div>
    </form>';
    }
    protected function edit(): void {
        $db = $this->openConnection();
    
        $stmt = $db->prepare(
            'UPDATE ' . $this->getTableName() . ' 
            SET 
             Title = :title, 
             IsDone = :is_done,
             StartDateTime = :start_date_time,
             Description = :description,
             Deadline = :deadline,
             InternalEventId = :internal_event_id,
             EditDateTime = NOW(),
             Notes = :notes
            WHERE Id = :id'
        );

        $stmt->execute([
            ':title' => $this->getModel()->getTitle(),
            ':is_done' => $this->getModel()->getIsDone() ? true : false,
            ':start_date_time' => $this->getModel()->getStartDateTime(),
            ':description' => $this->getModel()->getDescription(),
            ':deadline' => $this->getModel()->getDeadline(),
            ':internal_event_id'=> $this->getModel()->getInternalEventId(),
            ':notes'=> $this->getModel()->getNotes(),
            ':id'=> $this->getModel()->getId()
        ]);
    }

    protected function addNew(): void {
        
        $db = $this->openConnection();
        $stmt = $db->prepare(
            
            'INSERT INTO '. $this->getTableName() .' (
                Title, IsDone, StartDateTime, 
                Description, Deadline, InternalEventId, 
                CreationDateTime, EditDateTime, Notes, IsActive)
            VALUES 
            ( :title, :is_done, :start_date_time,
              :description, :deadline, :internal_event_id,
              NOW(), NOW(), :notes, 1)'
        );

    $stmt->execute([
    ':title' => $this->getModel()->getTitle(),
    ':is_done' => $this->getModel()->getIsDone() ? true : false,
    ':start_date_time' => $this->getModel()->getStartDateTime(),
    ':description' => $this->getModel()->getDescription(),
    ':deadline' => $this->getModel()->getDeadline(),
    ':internal_event_id'=> $this->getModel()->getInternalEventId(),
    ':notes'=> $this->getModel()->getNotes()
        ]);

    }

}