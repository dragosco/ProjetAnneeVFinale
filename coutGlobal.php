<?php
  $simulateur = $project->getSimulateurByType('coutGlobal');
  $iteration = 10000;
  $intervalle = 200;
  $probabilite = 80;
  $charge = 2000;
  if(!is_null($simulateur)) {
    $iteration = $simulateur->nbEchantillons;
    $intervalle = $simulateur->largeurIntervalle;
    $probabilite = $simulateur->probabilite;
    $charge = $simulateur->charge;
  }
 ?>

<div class="form-group">
  <button id="titreCoutGlobal" class="btn btn-default btn-block" type="button">
    Coût global
  </button>
</div>
<div id="contenuCoutGlobal" class="contenu">
  <!-- <br> -->
  <div class="col-md-4">
  <div class="row">
    <input type="hidden" id="typeSimulateur_Cout" name="typeSimulateur" value="coutGlobal" />
      <div class="col-xs-4">
      <label for="iteration">Nombre d'itération</label>
      <input class="form-control" id="iteration_Cout" type="text" name="iterationCoutGlobal" value=<?php echo $iteration; ?> required >
    </div>
      <div class="col-xs-4">
      <label for="intervalle">Largeur de l'intervalle</label>
      <input class="form-control" id="intervalle_Cout" type="text" name="intervalle" value=<?php echo $intervalle; ?> required >
    </div>
      <div class="col-xs-4">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br><button class="btn btn-default btn-block btn-lg" type="submit" onclick="calculate(typeSimulateur_Cout.value, iteration_Cout.value, intervalle_Cout.value, probabiliteGivenProbability_Cout.value, chargeGivenCharge_Cout.value, 'containerCoutGlobal')">
          <span class="glyphicon glyphicon-stats"></span>
        </button>
      <!-- </form> -->
    </div>
  </div>
</div>

  <div class="col-md-4">
  <div class="row">
      <div class="col-xs-4">
      <label for="chargeGivenCharge">Coût d'entrée<br>(€)</label>
      <input class="form-control" id="chargeGivenCharge_Cout" type="text" name="chargeGivenCharge" value=<?php echo $charge; ?> />
    </div>
      <div class="col-xs-4">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br><button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateProbability(typeSimulateur_Cout.value, iteration_Cout.value, intervalle_Cout.value, probabiliteGivenProbability_Cout.value, chargeGivenCharge_Cout.value)">
          <span class="glyphicon glyphicon-arrow-right"></span>
        </button><br>
      <!-- </form> -->
    </div>
      <div class="col-xs-4">
      <label for="probabiliteGivenCharge">Probabilité calculée (%)</label>
      <output class="form-control" id="probabiliteGivenCharge_Cout" type="text" name="probabiliteGivenCharge" />
    </div>
  </div>
</div>


  <div class="col-md-4">
  <div class="row">
      <div class="col-xs-4">
      <label for="probabiliteGivenProbability">Probabilité d'entrée (%)</label>
      <input class="form-control" id="probabiliteGivenProbability_Cout" type="text" name="probabiliteGivenProbability" value=<?php echo $probabilite; ?> />
    </div>
      <div class="col-xs-4">
      <br><br><button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateCharge(typeSimulateur_Cout.value, iteration_Cout.value, intervalle_Cout.value, probabiliteGivenProbability_Cout.value, chargeGivenCharge_Cout.value)">
        <span class="glyphicon glyphicon-arrow-right"></span>
      </button>
    </div>
      <div class="col-xs-4">
      <label for="chargeGivenProbability">Coût calculé<br>(€)</label>
      <output class="form-control" id="chargeGivenProbability_Cout" type="text" name="chargeGivenProbability" />
    </div>
  </div>
</div>
  <div id="containerCoutGlobal"></div>
   <!-- style="height: 400px; width: 100%;" -->
</div>
