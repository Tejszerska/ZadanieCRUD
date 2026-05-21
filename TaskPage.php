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
                        <h5 class="card-title">' . $row["Title"] . '</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Wydarzenie: ' . $eventName . '</h6>
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
        return " ";
    }

    protected function generateViewAdd(): string
    {
        return '';
    }

    protected function edit(): void {
    }

    protected function addNew(): void {

    }

}