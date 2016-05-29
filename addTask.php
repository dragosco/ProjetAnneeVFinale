<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="row">
                    <div class="col-md-12">
                    <h4 class="modal-title">Add Task</h4>
                    </div>
                </div>
            </div>
            <form method="POST" action="insert.php">
            <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12"><input class="form-control" id="taskInput" type="text" name="nom" placeholder="Task name" required/></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                    <label for="leftTaskSelector">Predecessors</label>
                                    <select class="form-control" id="leftTaskSelector1" name="predecesseurs[]" multiple>
                                        <?php foreach ($project->listeTaches as $task) { if($task->nom !== "End") {?>
                                            <option value="<?php echo $task->id; } ?>"><?php echo $task->nom ?></option>
                                        <?php }?>
                                    </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="rightTaskSelector">Successors</label>
                                <select class="form-control" id="rightTaskSelector1" name="successeurs[]" multiple>
                                    <?php foreach ($project->listeTaches as $task) { if($task->nom !== "Start") { ?>
                                        <option value="<?php echo $task->id; } ?>"><?php echo $task->nom ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="resource">Resource</label>
                                <select class="form-control" id="resource" name="idRessource">
                                    <?php foreach ($project->listeRessources as $ressource) { ?>
                                        <option value="<?php echo $ressource->id ?>"><?php echo $ressource->nom .' (cj : ' . $ressource->cout . '€)' ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="formLabel" for="probaLawBtnGroup">Probability distribution</label>
                            </div>
                        </div>
                        <input type="hidden" id="lawName" name="nomLoi" value=""/>
                        <div class="row">
                            <div class="col-md-12 btn-group btn-group-justified" data-toggle="buttons" id="probaLawBtnGroup">
                                <div class="btn-group">
                                    <button type="button" id="btnLawUnif" class="btn btn-default btn-law" value="1"></button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" id="btnLawBeta" class="btn btn-default btn-law" value="2"></button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" id="btnLawTriang" class="btn btn-default btn-law" value="3"></button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" id="btnLawNorm" class="btn btn-default btn-law" value="4"></button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" id="btnLawNo" class="btn btn-default btn-law" value="5"></button>
                                </div>
                            </div>
                        </div>

                        <div class="law row" id="blk-1">
                            <div class="col-md-12">
                                <p class="distribution-name"><b>Uniform distribution</b></p>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="min" name="min_unif">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="max" name="max_unif">
                                </div>
                            </div>
                        </div>
                        <div class="law row" id="blk-2">
                            <div class="col-md-12">
                                <p class="distribution-name"><b>Beta distribution</b></p>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="min" name="min_beta">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="max" name="max_beta">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="v" name="v">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="w" name="w">
                                </div>
                            </div>
                        </div>
                        <div class="law row" id="blk-3">
                            <div class="col-md-12">
                                <p class="distribution-name"><b>Triangular distribution</b></p>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="min" name="min_triang">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="max" name="max_triang">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="c" name="c">
                                </div>
                            </div>
                        </div>
                        <div class="law row" id="blk-4">
                            <div class="col-md-12">
                                <p class="distribution-name"><b>Normal distribution</b></p>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="min" name="min_norm">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="max" name="max_norm">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="mu" name="mu">
                                </div>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="sigma" name="sigma">
                                </div>
                            </div>
                        </div>
                        <div class="law row" id="blk-5">
                            <div class="col-md-12">
                                <p class="distribution-name"><b>Constant</b></p>
                                <div class="form-group">
                                    <input type="number" step="0.1" class="form-control" placeholder="constant" name="valeur" />
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success pull-right" id="createTaskButton" name="submit">Validate</button>
                            </div>
                        </div>

                    </div>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
