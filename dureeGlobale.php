<?php
  $simulateur = $project->getSimulateurByType('dureeGlobale');
  $iteration = 10000;
  $intervalle = 5;
  $probabilite = 80;
  $charge = 20;
  if(!is_null($simulateur)) {
    $iteration = $simulateur->nbEchantillons;
    $intervalle = $simulateur->largeurIntervalle;
    $probabilite = $simulateur->probabilite;
    $charge = $simulateur->charge;
  }
 ?>

<div class="form-group">
  <button id="titreDureeGlobale" class="btn btn-default btn-block" type="button">
    Overall duration
  </button>
</div>
<div id="contenuDureeGlobale" class="contenu">
  <!-- <br> -->
  <div class="col-md-4">
  <div class="row">
    <input type="hidden" id="typeSimulateur_Duree" name="typeSimulateur" value="dureeGlobale" />
      <div class="col-xs-4">
      <label for="iteration">Number of iterations</label>
      <input class="form-control" id="iteration_Duree" type="text" name="iterationDureeGlobale" value=<?php echo $iteration; ?> required >
    </div>
      <div class="col-xs-4">
      <label for="intervalle">Gap width</label>
      <input class="form-control" id="intervalle_Duree" type="text" name="intervalle" value=<?php echo $intervalle; ?> required >
    </div>
      <div class="col-xs-4">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br><button class="btn btn-default btn-block btn-lg" type="submit" onclick="calculate(typeSimulateur_Duree.value, iteration_Duree.value, intervalle_Duree.value, probabiliteGivenProbability_Duree.value, chargeGivenCharge_Duree.value, 'containerDureeGlobale')">
          <span class="glyphicon glyphicon-stats"></span>
        </button>
      <!-- </form> -->
    </div>
  </div>
</div>

  <div class="col-md-4">
  <div class="row">
      <div class="col-xs-4">
      <label for="chargeGivenCharge">Input duration (days)</label>
      <input class="form-control" id="chargeGivenCharge_Duree" type="text" name="chargeGivenCharge" value=<?php echo $charge; ?> />
    </div>
      <div class="col-xs-4">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br><button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateProbability(typeSimulateur_Duree.value, iteration_Duree.value, intervalle_Duree.value, probabiliteGivenProbability_Duree.value, chargeGivenCharge_Duree.value)">
          <span class="glyphicon glyphicon-arrow-right"></span>
        </button><br>
      <!-- </form> -->
    </div>
      <div class="col-xs-4">
      <label for="probabiliteGivenCharge">Output probability (%)</label>
      <output class="form-control" id="probabiliteGivenCharge_Duree" type="text" name="probabiliteGivenCharge" />
    </div>
  </div>
</div>


  <div class="col-md-4">
  <div class="row">
      <div class="col-xs-4">
      <label for="probabiliteGivenProbability">Input probability (%)</label>
      <input class="form-control" id="probabiliteGivenProbability_Duree" type="text" name="probabiliteGivenProbability" value=<?php echo $probabilite; ?> />
    </div>
      <div class="col-xs-4">
      <br><br><button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateCharge(typeSimulateur_Duree.value, iteration_Duree.value, intervalle_Duree.value, probabiliteGivenProbability_Duree.value, chargeGivenCharge_Duree.value)">
        <span class="glyphicon glyphicon-arrow-right"></span>
      </button>
    </div>
      <div class="col-xs-4">
      <label for="chargeGivenProbability">Output duration (days)</label>
      <output class="form-control" id="chargeGivenProbability_Duree" type="text" name="chargeGivenProbability" />
    </div>
  </div>
</div>
  <div id="containerDureeGlobale"></div>
   <!-- style="height: 400px; width: 100%;" -->
</div>
