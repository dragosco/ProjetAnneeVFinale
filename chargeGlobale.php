<?php
  $simulateur = $project->getSimulateurByType(SimulateurEnum::ChargeGlobale);
  $iteration = 10000;
  $intervalle = 5;
  $probabilite = 80;
  $charge = 22;
  if(!is_null($simulateur)) {
    $iteration = $simulateur->nbEchantillons;
    $intervalle = $simulateur->largeurIntervalle;
    $probabilite = $simulateur->probabilite;
    $charge = $simulateur->charge;
  }
 ?>

<div class="form-group">
  <button id="titre_Charge" class="btn btn-default btn-block" type="button">
      Overall burden
  </button>
</div>
<div id="contenu_Charge" class="contenu">
  <!-- <br> -->
  <div class="col-md-4">

  <div class="row">
      <div class="col-xs-4">
    <input type="hidden" id="typeSimulateur_Charge" name="typeSimulateur" value=<?php echo SimulateurEnum::ChargeGlobale; ?> />
  <div class="form-group">
      <label for="iteration">Number of iterations</label>
      <input class="form-control" id="iteration_Charge" type="text" name="iteration" value=<?php echo $iteration; ?> required >
    </div></div>
      <div class="col-xs-4">
    <div class="form-group">
      <br>
      <label for="intervalle">Gap width</label>
      <input class="form-control" id="intervalle_Charge" type="text" name="intervalle" value=<?php echo $intervalle; ?> required >
    </div></div>
      <div class="col-xs-4">

  <div class="form-group">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="calculate(typeSimulateur_Charge.value, iteration_Charge.value, intervalle_Charge.value, probabiliteGivenProbability_Charge.value, chargeGivenCharge_Charge.value, 'container_Charge')">
          <span class="glyphicon glyphicon-stats"></span>
        </button>
      <!-- </form> -->
    </div>
  </div>
  </div>
</div>


    <div class="col-md-4">

  <div class="row">
  <div class="col-xs-4">
      <label for="chargeGivenCharge">Input burden (d.m)</label>
      <input class="form-control" id="chargeGivenCharge_Charge" type="text" name="chargeGivenCharge" value=<?php echo $charge; ?> />
    </div>
  <div class="col-xs-4">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateProbability(typeSimulateur_Charge.value, iteration_Charge.value, intervalle_Charge.value, probabiliteGivenProbability_Charge.value, chargeGivenCharge_Charge.value)">
          <span class="glyphicon glyphicon-arrow-right"></span>
        </button>
      <!-- </form> -->
    </div>
  <div class="col-xs-4">
      <label for="probabiliteGivenCharge">Output probability (%)</label>
      <output class="form-control" id="probabiliteGivenCharge_Charge" type="text" name="probabiliteGivenCharge" />
    </div>
  </div>
</div>

    <div class="col-md-4">

  <div class="row">
  <div class="col-xs-4">
  <div class="form-group">
      <label for="probabiliteGivenProbability">Input probability (%)</label>
      <input class="form-control" id="probabiliteGivenProbability_Charge" type="text" name="probabiliteGivenProbability" value=<?php echo $probabilite; ?> />
    </div>
  </div>
    <div class="col-xs-4">
  <div class="form-group">
      <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateCharge(typeSimulateur_Charge.value, iteration_Charge.value, intervalle_Charge.value, probabiliteGivenProbability_Charge.value, chargeGivenCharge_Charge.value)">
        <span class="glyphicon glyphicon-arrow-right"></span>
      </button>
    </div></div>
      <div class="col-xs-4">

    <div class="form-group">
      <label for="chargeGivenProbability">Output burden (d.m)</label>
      <output class="form-control" id="chargeGivenProbability_Charge" type="text" name="chargeGivenProbability" />
    </div>
  </div>
</div>
</div>
  <div id="container_Charge"></div>
   <!-- style="height: 400px; width: 100%;" -->
   </div>
