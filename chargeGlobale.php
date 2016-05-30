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
    Charge globale
  </button>
</div>
<div id="contenuChargeGlobale" class="contenu">
  <!-- <br> -->
  <div class="col-md-4">

  <div class="row">
      <div class="col-xs-4">
    <input type="hidden" id="typeSimulateur" name="typeSimulateur" value="chargeGlobale" />
  <div class="form-group">
      <label for="iteration">Nombre d'itération</label>
      <input class="form-control" id="iteration" type="text" name="iteration" value=<?php echo $iteration; ?> required >
    </div></div>
      <div class="col-xs-4">
  <div class="form-group">
      <label for="intervalle">Largeur de l'intervalle</label>
      <input class="form-control" id="intervalle" type="text" name="intervalle" value=<?php echo $intervalle; ?> required >
    </div></div>
      <div class="col-xs-4">

  <div class="form-group">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="calculate(typeSimulateur.value, iteration.value, intervalle.value, probabiliteGivenProbability.value, chargeGivenCharge.value, 'containerChargeGlobale')">
          Générer
        </button>
      <!-- </form> -->
    </div>
  </div>
  </div>
</div>


    <div class="col-md-4">

  <div class="row">
  <div class="col-xs-4">
      <label for="chargeGivenCharge">Charge d'entrée (j.h)</label>
      <input class="form-control" id="chargeGivenCharge" type="text" name="chargeGivenCharge" value=<?php echo $charge; ?> />
    </div>
  <div class="col-xs-4">
      <!-- <form methode ="POST" action ="traitement.php"> -->
        <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateProbability(typeSimulateur.value, iteration.value, intervalle.value, probabiliteGivenProbability.value, chargeGivenCharge.value)">
          Calculer
        </button>
      <!-- </form> -->
    </div>
  <div class="col-xs-4">
      <label for="probabiliteGivenCharge">Probabilité calculée (%)</label>
      <output class="form-control" id="probabiliteGivenCharge_Charge" type="text" name="probabiliteGivenCharge" />
    </div>
  </div>
</div>

    <div class="col-md-4">

  <div class="row">
  <div class="col-xs-4">
  <div class="form-group">
      <label for="probabiliteGivenProbability">Probabilité d'entrée (%)</label>
      <input class="form-control" id="probabiliteGivenProbability" type="text" name="probabiliteGivenProbability" value=<?php echo $probabilite; ?> />
    </div>
  </div>
    <div class="col-xs-4">
  <div class="form-group">
      <br><br>
        <button class="btn btn-default btn-block btn-lg" type="submit" onclick="estimateCharge(typeSimulateur.value, iteration.value, intervalle.value, probabiliteGivenProbability.value, chargeGivenCharge.value)">
        Calculer
      </button>
    </div></div>
      <div class="col-xs-4">

    <div class="form-group">
      <label for="chargeGivenProbability">Charge calculée (j.h)</label>
      <output class="form-control" id="chargeGivenProbability_Charge" type="text" name="chargeGivenProbability" />
    </div>
  </div>
</div>
</div>
  <div id="containerChargeGlobale"></div>
   <!-- style="height: 400px; width: 100%;" -->
   </div>
