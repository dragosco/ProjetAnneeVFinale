<?php
require("models/Project.php");

// Tentative d'instanciation de la classe
$project = Project::getInstance();

?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion de projet</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/joint.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/tasklist-edit.js"></script>
</head>

<body>
<!--navbar-->
<?php
require 'navbar.php';
?>
<div class="col-sm-3 col-lg-2 sidebar">
    <div class="col-lg-12">
        <h1 class="page-header">log</h1>
    </div>
    <div id="console"></div>
</div>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Edit Task List</h1>
        </div>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p><a href="tasklist_edit.php" style="text-decoration: none;"><span class="glyphicon glyphicon-refresh"></span> Reload</a></p>
                    <table class="table" id="tableEdition" style="color: #5e5e5e;">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>1st predecessor</th>
                            <th>2nd predecessor</th>
                            <th>1st successor</th>
                            <th>2nd successor</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($project->listeTaches as $task) { ?>
                        <tr>
                            <td><p class="nom"><?php echo $task->nom;?></p></td>
                            <td>
                                <select class="precs1" id="prec1OfTask<?php echo $task->id;?>">
                                    <option <?php if($task->precedent1 === '') { ?> selected="selected" <?php } ?> value="">none</option>
                                    <option <?php if($task->precedent1 === 'Start') { ?> selected="selected" <?php } ?> value="Start">Start</option>
                                    <?php foreach ($project->listeTaches as $t) {
                                        if ($task->id !== $t->id) { ?>
                                            <option <?php if($task->precedent1 == $t->nom) {?> selected="selected" <?php } ?> value="<?php echo $t->nom;?>"><?php echo $t->nom?></option>
                                        <?php }?>
                                    <?php } ?>
                                </select>
                                <div class="initial"><b>Initial: </b><span class="init"><?php echo $task->precedent1 ?></span></div>
                                <div class="previous"><b>Previous: </b><span class='prev'><?php echo $task->precedent1 ?></span></div>
                            </td>
                            <td>
                                <select class="precs2" id="prec2OfTask<?php echo $task->id;?>">
                                    <option <?php if($task->precedent2 === '') { ?> selected="selected" <?php } ?> value="">none</option>
                                    <option <?php if($task->precedent2 === 'Start') { ?> selected="selected" <?php } ?> value="Start">Start</option>
                                    <?php foreach ($project->listeTaches as $t) {
                                        if ($task->id !== $t->id) { ?>
                                            <option <?php if($task->precedent2 == $t->nom) {?> selected="selected" <?php } ?> value="<?php echo $t->nom;?>"><?php echo $t->nom?></option>
                                        <?php }?>
                                    <?php } ?>
                                </select>
                                <div class="initial"><b>Initial:</b><span class="init"><?php echo $task->precedent2 ?></span></div>
                                <div class="previous"><b>Previous: </b><span class='prev'><?php echo $task->precedent2 ?></span></div>
                            </td>
                            <td>
                                <select class="suivs1" id="suiv1OfTask<?php echo $task->id;?>">
                                    <option <?php if($task->suivant1 === '') { ?> selected="selected" <?php } ?> value="">none</option>
                                    <?php foreach ($project->listeTaches as $t) {
                                        if ($task->id !== $t->id) { ?>
                                            <option <?php if($task->suivant1 == $t->nom) {?> selected="selected" <?php } ?> value="<?php echo $t->nom;?>"><?php echo $t->nom?></option>
                                        <?php }?>
                                    <?php } ?>
                                    <option <?php if($task->suivant1 === 'End') { ?> selected="selected" <?php } ?> value="End">End</option>
                                </select>
                                <div class="initial"><b>Initial:</b><span class="init"><?php echo $task->suivant1 ?></span></div>
                                <div class="previous"><b>Previous: </b><span class='prev'><?php echo $task->suivant1 ?></span></div>
                            </td>
                            <td>
                                <select class="suivs2" id="suiv2OfTask<?php echo $task->id;?>">
                                    <option <?php if($task->suivant2 === '') { ?> selected="selected" <?php } ?> value="">none</option>
                                    <?php foreach ($project->listeTaches as $t) {
                                        if ($task->id !== $t->id) { ?>
                                            <option <?php if($task->suivant2 == $t->nom) {?> selected="selected" <?php } ?> value="<?php echo $t->nom;?>"><?php echo $t->nom?></option>
                                        <?php }?>
                                    <?php } ?>
                                    <option <?php if($task->suivant2 === 'End') { ?> selected="selected" <?php } ?> value="End">End</option>
                                </select>
                                <div class="initial"><b>Initial:</b><span class="init"><?php echo $task->suivant2 ?></span></div>
                                <div class="previous"><b>Previous: </b><span class='prev'><?php echo $task->suivant2 ?></span></div>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <form id="validateTableForm" method="POST" action="update.php">
                        <button class="btn btn-success pull-right" type="submit">Validate changes</button>
                    </form>

                </div>
            </div>
        </div>
    </div><!--/.row-->
</div><!--/.main-->
</body>
</html>
