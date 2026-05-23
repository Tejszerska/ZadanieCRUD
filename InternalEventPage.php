<?php

require_once("Page.php");
require_once("InternalEvent.php");

class InternalEventPage extends Page
{
    private InternalEvent $model;

    protected function passTitle(): string
    {
        return "INTERNAL EVENT";
    }

    protected function passTableName(): string
    {
        return "internalevents";
    }

     public function getModel() : InternalEvent {
        return $this->model;
    }

    public function setModel(InternalEvent $model) {
        $this->model = $model;
    }

    protected function enterModelDataFromForm(): void {

    $this->setModel(new InternalEvent(
    intval($_POST['Id'] ?? 0),
    $_POST['Title'] ?? '',
    $_POST['Link'] ?? '',
    isset($_POST['IsPublic']),
    isset($_POST['IsCancelled']),
    $_POST['CreationDateTime'] ?? date('Y-m-d H:i:s'),
    $_POST['EditDateTime'] ?? date('Y-m-d H:i:s'),
    $_POST['PublishDateTime'] ?? '',
    $_POST['EventDateTime'] ?? '',
    $_POST['ShortDescription'] ?? '',
    $_POST['ContentHtml'] ?? '',
    $_POST['MetaDescription'] ?? '',
    $_POST['MetaTags'] ?? '',
    $_POST['Notes'] ?? '',
    isset($_POST['IsActive'])
));

    }

    protected function generateViewAll(): string
    {
        $db = $this->openConnection();

        $stmt = $db->prepare("SELECT * FROM InternalEvents");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $temp = '<div class="container"><div class="row">';

        foreach ($rows as $row) {
            $temp .= '
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">' . $row["Title"] . '</h5>
                        <p class="card-text">' . $row["ShortDescription"] . '</p>
                    </div>
                    <div class="card-footer">
                    <form method="POST" >
                    <input name="Id" value= " '.$row["Id"].' " type="hidden" >
                    <button class="btn btn-primary" value="'.self::EDIT_VIEW.'" name="'.self::ACTION.'" ">Edit</button>
                    <button class="btn btn-danger"  value="'.self::DELETE.'" name="'.self::ACTION.'"  value>Delete</button>
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

        $stmt = $db->prepare("SELECT * FROM ".$this->getTableName()." where Id= :Id");
        $stmt->bindValue(":Id", $_POST["Id"], PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return '
    <form method="POST" action="">
    <div class="container">
        <div class="row gy-3">
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round align-middle">label</i>
                        Title
                    </label>
                    <input name="Title" class="form-control validate" value="'.$row["Title"].'">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round align-middle">link</i>
                        Link
                    </label>
                    <input name="Link" class="form-control validate" value="'.$row["Link"].'">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="row">
                    <div class="col-auto">
                        <label class="form-check-label">
                            <i class="material-icons-round align-middle">block</i>
                            Private
                        </label>
                    </div>
                    <div class="form-switch form-check col-auto">
                        <input name="IsPublic" '. ($row["IsPublic"] ? "checked" : "") .' class="form-check-input validate" type="checkbox">
                        <label class="form-check-label">
                            Public
                            <i class="material-icons-round align-middle">public</i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="row">
                    <div class="col-auto">
                        <label class="form-check-label">
                            Cancelled
                            <i class="material-icons-round align-middle">cancel</i>
                        </label>
                    </div>
                    <div class="form-switch form-check col-auto">
                        <input name="IsCancelled" '. ($row["IsCancelled"] ? "checked" : "") .' class="form-check-input validate" type="checkbox">
                        <label class="form-check-label">
                            <i class="material-icons-round align-middle">public</i>
                            Active
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round palette-accent-text-color align-middle">event</i>
                        Event date
                    </label>
                    <input name="EventDateTime" class="form-control validate" type="date" value="'.date('Y-m-d', strtotime($row["EventDateTime"])).'">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round palette-accent-text-color align-middle">today</i>
                        Publish date
                    </label>
                    <input name="PublishDateTime" class="form-control validate" type="date" value="'.date('Y-m-d', strtotime($row["PublishDateTime"])).'">
                </div>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">description</i>
                    Short description
                </label>
                <textarea name="ShortDescription" class="form-control validate">'.$row["ShortDescription"].'</textarea>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">newspaper</i>
                    Content
                </label>
                <textarea name="ContentHTML" class="form-control validate">'.$row["ContentHTML"].'</textarea>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">feed</i>
                    Meta description
                </label>
                <textarea name="MetaDescription" class="form-control validate">'.$row["MetaDescription"].'</textarea>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">subtitles</i>
                    Meta tags
                </label>
                <textarea name="MetaTags" class="form-control validate">'.$row["MetaTags"].'</textarea>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">notes</i>
                    Notes
                </label>
                <textarea name="Notes" class="form-control validate">'.$row["Notes"].'</textarea>
            </div>
            <div class="col-sm-12">
                <!-- Czyste przekazanie ID bez spacji -->
                <input name="Id" value="'.$row["Id"].'" type="hidden">

                <button name="'.self::ACTION.'" value="'.self::EDIT.'" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</form>';
    }

    protected function generateViewAdd(): string
    {
        return '
    <form method="POST" action="">
        <div class="container">
        <div class="row gy-3">
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round align-middle">label</i>
                        Title
                    </label>
                    <input name="Title" class="form-control validate">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round align-middle">link</i>
                        Link
                    </label>
                    <input  name="Link" class="form-control validate">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="row">
                    <div class="col-auto">
                        <label class="form-check-label">
                            Public
                            <i class="material-icons-round align-middle">public</i>
                        </label>
                    </div>
                    <div class="form-switch form-check col-auto">
                        <input name="IsPublic" class="form-check-input validate" type="checkbox">
                        <label class="form-check-label">
                            <i class="material-icons-round align-middle">block</i>
                            Private
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="row">
                    <div class="col-auto">
                        <label class="form-check-label">
                            Cancelled
                            <i class="material-icons-round align-middle">cancel</i>
                        </label>
                    </div>
                    <div class="form-switch form-check col-auto">
                        <input name="isCancelled" class="form-check-input validate" type="checkbox">
                        <label class="form-check-label">
                            <i class="material-icons-round align-middle">public</i>
                            Active
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round palette-accent-text-color align-middle">event</i>
                        Event date
                    </label>
                    <input  name="EventDateTime" class="form-control validate" type="date">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xxl-4">
                <div class="input-group">
                    <label class="input-group-text">
                        <i class="material-icons-round palette-accent-text-color align-middle">today</i>
                        Publish date
                    </label>
                    <input name="PublishDateTime" class="form-control validate" type="date">
                </div>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">description</i>
                    Short description
                </label>
                <textarea name="ShortDescription" class="form-control validate"></textarea>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">newspaper</i>
                    Content
                </label>
                <textarea name="ContentHTML" class="form-control validate"></textarea>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">feed</i>
                    Meta description
                </label>
                <textarea name="MetaDescription" class="form-control validate"></textarea>
            </div>
            <div class="col-sm-12">
                <label class="form-label">
                    <i class="material-icons-round palette-accent-text-color align-middle">subtitles</i>
                    Meta tags
                </label>
                <textarea name="MetaTags" class="form-control validate"></textarea>
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

    protected function edit(): void 
{
    $db = $this->openConnection();
    
    $stmt = $db->prepare(
        'UPDATE ' . $this->getTableName() . ' 
        SET 
            Title = :title, 
            Link = :link, 
            IsPublic = :is_public, 
            IsCancelled = :is_cancelled, 
            EventDateTime = :event_date_time, 
            EditDateTime = NOW(), 
            PublishDateTime = :publish_date_time, 
            ShortDescription = :short_description, 
            ContentHTML = :content_html, 
            MetaDescription = :meta_description, 
            MetaTags = :meta_tags, 
            Notes = :notes, 
            IsActive = :isActive
        WHERE Id = :id'
    );

    $stmt->execute([
        ':title' => $this->getModel()->getTitle(),
        ':link'=> $this->getModel()->getLink(),
        ':is_public'=> $this->getModel()->getIsPublic(),
        ':is_cancelled'=> $this->getModel()->getIsCancelled(),
        ':event_date_time'=> $this->getModel()->getEventDateTime(),
        ':publish_date_time'=> $this->getModel()->getPublishDateTime(),
        ':short_description'=> $this->getModel()->getShortDescription(),
        ':content_html'=> $this->getModel()->getContentHtml(),
        ':meta_description'=> $this->getModel()->getMetaDescription(),
        ':meta_tags'=> $this->getModel()->getMetaTags(),
        ':notes'=> $this->getModel()->getNotes(),
        ':isActive'=> $this->getModel()->getIsActive(),
        ':id'=> $this->getModel()->getId()
    ]);
}
    protected function addNew(): void
    {
        $db = $this->openConnection();
        $stmt = $db->prepare(
            
            'INSERT INTO '. $this->getTableName() .' (
                Title, Link, IsPublic, IsCancelled, EventDateTime, 
                CreationDateTime, EditDateTime, PublishDateTime, 
                ShortDescription, ContentHTML, MetaDescription, 
                MetaTags, Notes, IsActive)
            VALUES 
            ( :title, :link, :is_public, :is_cancelled, :event_date_time,
              NOW(), NOW(), :publish_date_time,
              :short_description, :content_html, :meta_description, 
              :meta_tags, :notes, 1)'
        );

    $stmt->execute([
    ':title' => $this->getModel()->getTitle(),
    ':link' => $this->getModel()->getLink(),
    ':is_public' => $this->getModel()->getIsPublic() ? true : false,
    ':is_cancelled'  => $this->getModel()->getIsCancelled() ? true : false,
    ':event_date_time' => $this->getModel()->getEventDateTime(),
    ':publish_date_time' => $this->getModel()->getPublishDateTime(),
    ':short_description' => $this->getModel()->getShortDescription(),
    ':content_html' => $this->getModel()->getContentHtml(),
    ':meta_description' => $this->getModel()->getMetaDescription(),
    ':meta_tags' => $this->getModel()->getMetaTags(),
    ':notes'  => $this->getModel()->getNotes()
        ]);

    }
}
