<?php
require_once("InternalEventPage.php");
require_once("TaskPage.php");

$pageType = $_GET['page'] ?? 'events';

if ($pageType === 'tasks') {
    $mypage = new TaskPage();
} else {
    $mypage = new InternalEventPage();
}

$mypage->initialize();