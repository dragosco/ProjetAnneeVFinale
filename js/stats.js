$(document).ready(function() {
  $('#contenu_Charge').hide();
  $('#contenu_Cout').hide();
  $('#contenu_Duree').hide();
  $('#contenu_Marge').hide();

  $('#titre_Charge').on('click', function(event) {
    $('#contenu_Charge').slideToggle();
  });
  $('#titre_Cout').on('click', function(event) {
    $('#contenu_Cout').slideToggle();
  });
  $('#titre_Duree').on('click', function(event) {
    $('#contenu_Duree').slideToggle();
  });
  $('#titre_Marge').on('click', function(event) {
    $('#contenu_Marge').slideToggle();
  });
});

//fonction qui dessine le graphique correspondant au type de simulateur passé par paramètre
function calculate(typeSimulateur, iteration, intervalle, probabilite, charge, divId) {
  var parametres = {typeSimulateur: typeSimulateur, iteration: iteration, intervalle: intervalle,
     probabilite: probabilite, charge: charge};

  var nomChart = '';
  if(typeSimulateur=='chargeGlobale') {
    nomChart = 'Overall burden simulation';
  } else if(typeSimulateur=='coutGlobal') {
    nomChart = 'Overall cost simulation';
  } else if(typeSimulateur=='dureeGlobale') {
    nomChart = 'Overall duration simulation';
  } else if(typeSimulateur=='margeFinanciere') {
    nomChart = 'Financial margin simulation';
  }

  $.ajax({
      type: 'POST',
      url: "/ProjetAnneeVFinale/json/calculStat_json.php",
      dataType: 'json',
      data: parametres,
      success: function (data) {
        xAxis = data.xAxis;
        yAxis = data.yAxis;

        $('#'+divId).highcharts({
          chart: {
            type: 'line'
          },
          title: {
            text: nomChart
          },
          subtitle: {
              text: null
          },
          plotOptions: {
            line: {
              dataLabels: {
                  enabled: true
              },
              enableMouseTracking: false
            }
          },
          xAxis: {
              categories: xAxis
          },
          yAxis: {
              title: {
                  text: null
              }
          },
          series: [{
              name: 'Category proportion',
              data: yAxis
          }]
        });
      }
  });
};

//fonction qui affiche le resultat du calcul de l'estimation d'une probabilité donné une entrée
//(charge, durée, coût)
function estimateProbability(typeSimulateur, iteration, intervalle, probabilite, charge) {
  var parametres = {typeSimulateur: typeSimulateur, iteration: iteration, intervalle: intervalle,
     probabilite: probabilite, charge: charge};

  var outputId = 'probabiliteGivenCharge';
  if(typeSimulateur=='chargeGlobale') {
    outputId += '_Charge';
  } else if(typeSimulateur=='coutGlobal') {
    outputId += '_Cout';
  } else if(typeSimulateur=='dureeGlobale') {
    outputId += '_Duree';
  } else if(typeSimulateur=='margeFinanciere') {
    outputId += '_Marge';
  }

  $.ajax({
    type: 'POST',
    url: "/ProjetAnneeVFinale/json/estimateProbabilityGivenCharge_json.php",
    dataType: 'json',
    data: parametres,
    success: function (data) {
      probabilite = data.probabilite;
      $('#'+outputId).val(probabilite);
    }
  });
};

//fonction qui affiche le resultat du calcul de l'estimation d'une sortie (charge, durée, coût)
//donné une probabilité
function estimateCharge(typeSimulateur, iteration, intervalle, probabilite, charge) {
  var parametres = {typeSimulateur: typeSimulateur, iteration: iteration, intervalle: intervalle,
     probabilite: probabilite, charge: charge};

  var outputId = 'chargeGivenProbability';
  if(typeSimulateur=='chargeGlobale') {
    outputId += '_Charge';
  } else if(typeSimulateur=='coutGlobal') {
    outputId += '_Cout';
  } else if(typeSimulateur=='dureeGlobale') {
    outputId += '_Duree';
  } else if(typeSimulateur=='margeFinanciere') {
    outputId += '_Marge';
  }

  $.ajax({
    type: 'POST',
    url: "/ProjetAnneeVFinale/json/estimateChargeGivenProbability_json.php",
    dataType: 'json',
    data: parametres,
    success: function (data) {
      charge = data.charge;
      $('#'+outputId).val(charge);
    }
  });
};
