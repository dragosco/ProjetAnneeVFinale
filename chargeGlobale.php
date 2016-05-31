<?php
  $simulateur = $project->getSimulateurByType('chargeGlobale');
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
  <button id="titreChargeGlobale" class="btn btn-default btn-block" type="button">
      Overall burden
  </button>
</div>
<div id="contenuChargeGlobale" class="contenu">
  <!-- <br> -->
  <div class="col-md-4">

  <div class="row">
      <div class="col-xs-4">
    <input type="hidden" id="typeSimulateur" name="typeSimulateur" value="chargeGlobale" />
  <div class="form-group">
      <label for="iteration">Number of iterations</label>
      <input class="form-control" id="iteration" type="text" name="iteration" value=<?php echo $iteration; ?> required >
    </div></div>
      <div class="col-xs-4">
  <div class="form-group">
      <label for="intervalle">Gap width</label>
      <input class="form-control" id="intervalle" type="text" name="intervalle" value=<?php echo $intervalle; ?> required >
    </div></div>
      <div class="col-xs-4">

  <div class="form-group">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="calculate(typeSimulateur.value, iteration.value, intervalle.value, probabiliteGivenProbability.value, chargeGivenCharge.value, 'containerChargeGlobale')">
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
      <label for="chargeGivenCharge">Input burden (d.h)</label>
      <input class="form-control" id="chargeGivenCharge" type="text" name="chargeGivenCharge" value=<?php echo $charge; ?> />
    </div>
  <div class="col-xs-4">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateProbability(typeSimulateur.value, iteration.value, intervalle.value, probabiliteGivenProbability.value, chargeGivenCharge.value)">
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
      <input class="form-control" id="probabiliteGivenProbability" type="text" name="probabiliteGivenProbability" value=<?php echo $probabilite; ?> />
    </div>
  </div>
    <div class="col-xs-4">
  <div class="form-group">
      <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateCharge(typeSimulateur.value, iteration.value, intervalle.value, probabiliteGivenProbability.value, chargeGivenCharge.value)">
        <span class="glyphicon glyphicon-arrow-right"></span>
      </button>
    </div></div>
      <div class="col-xs-4">

    <div class="form-group">
      <label for="chargeGivenProbability">Output burden (d.h)</label>
      <output class="form-control" id="chargeGivenProbability_Charge" type="text" name="chargeGivenProbability" />
    </div>
  </div>
</div>
</div>
  <div id="containerChargeGlobale"></div>
   <!-- style="height: 400px; width: 100%;" -->
   </div>
