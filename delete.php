<?php
require("models/Project.php");

$project = Project::getInstance();
$project->removeTask($_GET['id']);
header("Location: tasklist.php");

?>
