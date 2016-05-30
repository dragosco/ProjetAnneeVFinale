<?php
require("models/Project.php");
session_start();
// Tentative d'instanciation de la classe
$project = Project::getInstance();

if(!isset($_SESSION['email'])){
    header("Location: login.php");
}
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gestion de projet</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
</head>

<body>
	<?php require 'navbar.php'; ?>

	<div class="row main">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Task List</h1>
            </div>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
					<div class="panel-heading">
						<a class="btn btn-link" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus"></span> Add Task</a>

					</div>
                    	<div class="panel-body">
                        <table class="table">
                            <thead>
                            	<tr>
								<th></th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Resource associated</th>
                                <th>Predecessors</th>
                                <th>Successors</th>
                              </tr>
                            </thead>
                            <tbody>
                            	<?php
                                    // On affiche chaque entrée une à une
									foreach ($project->listeTaches as $task) {
                                        if($task->nom !== "Start" && $task->nom !== "End") {
	                            ?>
	                            <tr>
								<td>
                                    <a class="btn btn-link" data-toggle="modal" data-target="#modalTache<?php echo $task->id;?>" >
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>

                                    <a class="btn btn-link" data-toggle="modal" data-target="#modalDeleteTache<?php echo $task->id;?>">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                    <div class="modal fade" id="modalDeleteTache<?php echo $task->id;?>" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="modal-title">Delete '<?php echo $task->nom; ?>'</h4>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        Are you sure you want to delete task '<?php echo $task->nom;?>' ?
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <a class="btn btn-danger pull-right" href="delete.php?id=<?php echo $task->id; ?>">YES</a>
                                                        <a class="btn btn-success pull-right" data-dismiss="modal" aria-label="Close">NO</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                    <div class="modal fade" id="modalTache<?php echo $task->id;?>" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="modal-title">Edit '<?php echo $task->nom; ?>'</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <form method="POST" action="update.php">
                                                                <label for="nm">Change name</label>
                                                                <input type="text" class="form-control" id="nm" name="nvnom" value="<?php echo $task->nom; ?>" />
                                                                <input type="hidden" name="id" value="<?php echo $task->id; ?>" />
                                                                <button type="submit" id="submitTaskChanges" class="btn btn-info pull-right">Update name</button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="row tache">
                                                        <div class="col-md-12">
                                                            <p class="message" style="color:darkred;">The predecessor and successor of a task must be different</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="selectorpreds">Add predecessor</label>
                                                            <br>
                                                            <select class="selectorpreds">
                                                                <option selected disabled>Choose...</option>
                                                                <?php foreach ($project->listeTaches as $t) { ?>
                                                                    <option value="<?php echo $t->id; ?>"><?php echo $t->nom; ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <br>
                                                            <label for="listepreds">Predecessors</label>
                                                            <ul class="list-group predecesseur" id="listepreds">
                                                            <?php
                                                            foreach($task->predecesseurs as $pred) { ?>
                                                                <li class="list-group-item predecesseur" value="<?php echo $pred->id; ?>"><span class="glyphicon glyphicon-remove predecesseur" style="color:darkred; cursor: pointer;"></span><?php echo $pred->nom; } ?></li>
                                                            </ul>

                                                            <button class="submitNvxPreds btn btn-info pull-right" id="<?php echo $task->id; ?>" >Update predecessors</button>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="selectorsuccs">Add successor</label>
                                                            <br>
                                                            <select class="selectorsuccs">
                                                                <option selected disabled>Choose...</option>
                                                                <?php foreach ($project->listeTaches as $t) { ?>
                                                                    <option value="<?php echo $t->id; ?>"><?php echo $t->nom; ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <br>
                                                            <label for="listesuccs">Successors</label>
                                                            <ul class="list-group successeur" id="listesuccs">
                                                            <?php
                                                            foreach($task->successeurs as $succ) { ?>
                                                                <li class="list-group-item successeur" id="<?php echo $task->id; ?>" value="<?php echo $succ->id; ?>"><span class="glyphicon glyphicon-remove successeur" style="color:darkred; cursor: pointer;"></span><?php echo $succ->nom; } ?></li>
                                                            </ul>

                                                            <button class="submitNvxSuccs btn btn-info pull-right" id="<?php echo $task->id; ?>" >Update successors</button>
                                                        </div>

                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <form class="updateLoi" method="POST" action="update.php" >
                                                            <input type="hidden" name="id" value="<?php echo $task->id; ?>" />
                                                            <div class="form-group col-md-12">
                                                                <label for="nvloi">Change associated distribution</label>
                                                                <select name="nvloi">
                                                                    <option value="uniforme" <?php if(get_class($task->loi) == 'LoiRand') { ?> selected <?php } ?> >Uniform distribution</option>
                                                                    <option value="beta" <?php if(get_class($task->loi) == 'LoiBeta') { ?> selected <?php } ?> >Beta distribution</option>
                                                                    <option value="triangulaire" <?php if(get_class($task->loi) == 'LoiTriangulaire') { ?> selected <?php } ?> >Triangular distribution</option>
                                                                    <option value="normale" <?php if(get_class($task->loi) == 'LoiNormaleTronquee') { ?> selected <?php } ?> >Normal/Gaussian distribution</option>
                                                                    <option value="sansLoi" <?php if(get_class($task->loi) == 'SansLoi') { ?> selected <?php } ?> >Constant</option>
                                                                </select>
                                                            </div>
                                                            <br>
                                                            <div class="form-group row params uniforme">

                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiRand') { echo $task->loi->valeurMin; } ?>" placeholder="min" name="min_unif">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiRand') { echo $task->loi->valeurMax; } ?>" placeholder="max" name="max_unif">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row params beta">
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiBeta') { echo $task->loi->valeurMin; } else { echo 'min'; } ?>" placeholder="min" name="min_beta">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiBeta') { echo $task->loi->valeurMax; } else { echo 'max'; } ?>" placeholder="max" name="max_beta">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiBeta') { echo $task->loi->v; } ?>" placeholder="v" name="v">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiBeta') { echo $task->loi->w; } ?>" placeholder="w" name="w">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row params triangulaire">
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiTriangulaire') { echo $task->loi->valeurMin; } ?>" placeholder="min" name="min_triang">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiTriangulaire') { echo $task->loi->valeurMax; } ?>" placeholder="max" name="max_triang">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiTriangulaire') { echo $task->loi->c; } ?>" placeholder="c" name="c">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row params normale">
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiNormaleTronquee') { echo $task->loi->valeurMin; } ?>" placeholder="min" name="min_norm">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiNormaleTronquee') { echo $task->loi->valeurMax; } ?>" placeholder="max" name="max_norm">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiNormaleTronquee') { echo $task->loi->mu; } ?>" placeholder="mu" name="mu">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'LoiNormaleTronquee') { echo $task->loi->sigma; } ?>" placeholder="sigma" name="sigma">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row params sansLoi">
                                                                <div class="form-group col-md-6">
                                                                    <input type="number" step="0.1" class="form-control" value="<?php if(get_class($task->loi) == 'SansLoi') { echo $task->loi->val; } ?>" placeholder="value" name="valeur" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-info pull-right" >Update distribution</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                    <br>




                                </td>
                                  <td><?php echo $task->id;?></td>
	                              <td><?php echo $task->nom;?></td>
	                              <td><?php echo $task->ressource->nom . ' (cj : ' . $task->ressource->cout . '€)'; ?></td>
                                  <td><?php
                                      $i = 0;
                                      foreach($task->predecesseurs as $pred) {
                                          if($i == 0) {echo $pred->nom;}
                                          else { echo ", " . $pred->nom;}
                                          $i++;
                                      } ?></td>
                                  <td><?php
                                      $i = 0;
                                      foreach($task->successeurs as $succ) {
                                          if($i == 0) echo $succ->nom ;
                                          else echo ", " . $succ->nom ;
                                          $i++;
                                    }  } } ?></td>
                                </tr>
                            </tbody>
                      </table>
                    </div>
					<?php require("addTask.php") ?>
                </div><!--/panel-->
            </div>
        </div><!--/.row-->
    </div><!--/.main-->
</body>
</html>
