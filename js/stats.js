$(document).ready(function() {
  $('#contenuChargeGlobale').hide();
  $('#contenuCoutGlobal').hide();
  $('#contenu_Marge').hide();

  $('#titreChargeGlobale').on('click', function(event) {
    $('#contenuChargeGlobale').slideToggle(); //'show'
    // $('#contenuChargeGlobale').toggle();
    // $('#contenuCoutGlobal').toggle();
  });
  $('#titreCoutGlobal').on('click', function(event) {
    $('#contenuCoutGlobal').slideToggle(); //'show'
    // $('#contenuChargeGlobale').toggle();
  });
  $('#titre_Marge').on('click', function(event) {
    $('#contenu_Marge').slideToggle(); //'show'
    // $('#contenuChargeGlobale').toggle();
  });
  $('#waitForGraph').hide();
});

// $('')

// jQuery(document).ready(function(){
//     jQuery('#hideshow').live('click', function(event) {
//          jQuery('#content').toggle('show');
//     });
// });

function calculate(typeSimulateur, iteration, intervalle, probabilite, charge, divId) {
  var parametres = {typeSimulateur: typeSimulateur, iteration: iteration, intervalle: intervalle,
     probabilite: probabilite, charge: charge};

  var nomChart = '';
  if(typeSimulateur=='chargeGlobale') {
    nomChart = 'Simulation de charge globale';
  } else if(typeSimulateur=='coutGlobal') {
    nomChart = 'Simulation de coût global';
  } else if(typeSimulateur=='margeFinanciere') {
    nomChart = 'Simulation de marge financière';
  }
  $('#waitForGraph').show();

  $.ajax({
      type: 'POST',
      url: "/ProjetAnnee/json/calculStat_json.php", //?iteration="+iteration+"&intervale="+intervale,
      dataType: 'json',
      data: parametres,
      // beforeSend: function() {
      //   alert(typeSimulateur);
      //     alert(iteration);
      //       alert(intervalle);
      // },
      success: function (data) {
        // alert("entrou");
        xAxis = data.xAxis;
        yAxis = data.yAxis;

        $('#waitForGraph').hide();
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
              name: 'Pourcentage par catégorie',
              data: yAxis
          }]
        });
      }
  });
};

function estimateProbability(typeSimulateur, iteration, intervalle, probabilite, charge) {
  var parametres = {typeSimulateur: typeSimulateur, iteration: iteration, intervalle: intervalle,
     probabilite: probabilite, charge: charge};

  var outputId = 'probabiliteGivenCharge';
  if(typeSimulateur=='chargeGlobale') {
    outputId += '_Charge';
  } else if(typeSimulateur=='coutGlobal') {
    outputId += '_Cout';
  } else if(typeSimulateur=='margeFinanciere') {
    outputId += '_Marge';
  }

  $.ajax({
    type: 'POST',
    url: "/ProjetAnnee/json/estimateProbabilityGivenCharge_json.php", //?iteration="+iteration+"&intervale="+intervale+"&charge="+charge,
    dataType: 'json',
    data: parametres,
    success: function (data) {
      probabilite = data.probabilite;
      $('#'+outputId).val(probabilite);
    }
  });
};

function estimateCharge(typeSimulateur, iteration, intervalle, probabilite, charge) {
  var parametres = {typeSimulateur: typeSimulateur, iteration: iteration, intervalle: intervalle,
     probabilite: probabilite, charge: charge};

  var outputId = 'chargeGivenProbability';
  if(typeSimulateur=='chargeGlobale') {
    outputId += '_Charge';
  } else if(typeSimulateur=='coutGlobal') {
    outputId += '_Cout';
  } else if(typeSimulateur=='margeFinanciere') {
    outputId += '_Marge';
  }

  $.ajax({
    type: 'POST',
    url: "/ProjetAnnee/json/estimateChargeGivenProbability_json.php", //?iteration="+iteration+"&intervale="+intervale+"&probabilite="+probabilite,
    dataType: 'json',
    data: parametres,
    success: function (data) {
      charge = data.charge;
      $('#'+outputId).val(charge);
    }
  });
};
