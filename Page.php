<?php

require_once("Page.php");
abstract class Page
{

    protected const ACTION = "ACTION";
    protected const ADD_NEW = "ADD NEW";
    protected const EDIT = "EDIT";
    protected const EDIT_VIEW = "EDIT_VIEW";
    protected const CREATE_VIEW = "CREATE_VIEW";
    protected const DELETE = "DELETE";

    private string $title;
    private string $tableName;
    private string $action;

    public function __construct()
    {
        $this->title = $this->passTitle();
        $this->tableName = $this->passTableName();
        $this->action = self::ACTION;
    }

    abstract protected function passTitle(): string;

    abstract protected function passTableName(): string;

    public function getTitle()
    {
        return $this->title;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
    
    function generateHeader(): string
    {
        return '<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
  '
  . $this->getTitle() .
  '
    </title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
        rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>'
                . $this->getTitle() .
                '</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <form method="POST">
                    <a href="index.php?page=events" class="btn btn-warning">Internal Event)</a>
                    <a href="index.php?page=tasks" class="btn btn-warning">Tasks</a>
                    <button type="submit" name="'.self::ACTION.'" value="'.self::CREATE_VIEW.'" class="btn btn-primary"><strong>Create new </strong> '.$this->getTitle().'</button>
                    <button type="submit" name="'.self::ACTION.'" value="" class="btn btn-primary">All '.$this->getTitle().'S</button>
                </form>
            </div>
        </div>
    </div>
    <hr>';
    }

    function generateFooter()
    {
        return '    
    <script src="js/bootstrap.min.js"></script>
    </body>
    </html>
    ';
    }

    protected function openConnection()
    {
        return new PDO("mysql:host=localhost;dbname=phpadvanced", "root");
    }

    public function initialize(): void
    {
        echo $this->generateHeader();
        $action = $_POST[self::ACTION] ?? '';

        switch ($action) {
            default:
                echo $this->generateViewAll();
                break;
            case self::CREATE_VIEW:
                echo $this->generateViewAdd();
                break;
            case self::ADD_NEW:
                $this->enterModelDataFromForm();
                $this->addNew();
                header("Location: ".$_SERVER['PHP_SELF']);
                break;
            case self::EDIT_VIEW:
                echo $this->generateViewEdit();
                break;
            case self::EDIT:
                $this->enterModelDataFromForm();
                $this->edit();
                echo $this->generateViewAll();
                break;
            case self::DELETE:
                $this->delete();
                echo $this->generateViewAll();
                break;
        }
        echo $this->generateFooter();
    }

    public function delete(): void
    {
        $id = (int)($_POST['Id'] ?? 0);
        $dbo = $this->openConnection();

        $stmt = $dbo->prepare("DELETE FROM {$this->tableName} WHERE Id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    protected abstract function enterModelDataFromForm(): void;
    protected abstract function generateViewAll(): string;
    protected abstract function generateViewEdit(): string;
    protected abstract function generateViewAdd(): string;
    protected abstract function edit(): void;
    protected abstract function addNew(): void;



}
