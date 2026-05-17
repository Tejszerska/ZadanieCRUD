<?php
include_once("Model.php");

class InternalEvent extends Model {
      
    private string $link;
    private bool $isPublic;
    private bool $isCancelled;
    private string $eventDateTime;
    private string $publishDateTime;
    private string $shortDescription;
    private string $contentHtml;
    private string $metaDescription;
    private string $metaTags;

    public function __construct(
        int $id,
        string $title,
        string $link,
        bool $isPublic,
        bool $isCancelled,
        string $creationDateTime,
        string $editDateTime,
        string $publishDateTime,
        string $eventDateTime,
        string $shortDescription,
        string $contentHtml,
        string $metaDescription,
        string $metaTags,
        string $notes,
        bool $isActive
    ) {
        parent::__construct($id, $title, $creationDateTime, $editDateTime, $notes, $isActive);
        $this->link = $link;
        $this->isPublic = $isPublic;
        $this->isCancelled = $isCancelled;
        $this->publishDateTime = $publishDateTime;
        $this->eventDateTime = $eventDateTime;
        $this->shortDescription = $shortDescription;
        $this->contentHtml = $contentHtml;
        $this->metaDescription = $metaDescription;
        $this->metaTags = $metaTags;
    }


    // GETTERY

    public function getLink() : string {
        return $this->link;
    }

    public function getIsPublic() : bool {
        return $this->isPublic;
    }

    public function getIsCancelled() : bool {
        return $this->isCancelled;
    }

    public function getPublishDateTime() : string {
        return $this->publishDateTime;
    }

    public function getEventDateTime() : string {
        return $this->eventDateTime;
    }

    public function getShortDescription() : string {
        return $this->shortDescription;
    }

    public function getContentHtml() : string {
        return $this->contentHtml;
    }

    public function getMetaDescription() : string {
        return $this->metaDescription;
    }

    public function getMetaTags() : string {
        return $this->metaTags;
    }

    // SETTERY

    public function setLink(string $link) {
        $this->link = $link;
    }

    public function setIsPublic(bool $isPublic) {
        $this->isPublic = $isPublic;
    }

    public function setIsCancelled(bool $isCancelled) {
        $this->isCancelled = $isCancelled;
    }

    public function setPublishDateTime(string $publishDateTime) {
        $this->publishDateTime = $publishDateTime;
    }

    public function setEventDateTime(string $eventDateTime) {
        $this->eventDateTime = $eventDateTime;
    }

    public function setShortDescription(string $shortDescription) {
        $this->shortDescription = $shortDescription;
    }

    public function setContentHtml(string $contentHtml) {
        $this->contentHtml = $contentHtml;
    }

    public function setMetaDescription(string $metaDescription) {
        $this->metaDescription = $metaDescription;
    }

    public function setMetaTags(string $metaTags) {
        $this->metaTags = $metaTags;
    }
}
?>