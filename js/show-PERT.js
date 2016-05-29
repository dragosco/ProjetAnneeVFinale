$(document).ready(function() {
    $('#waitForDiagram').show();
    $.ajax({
        type: 'GET',
        url: "/ProjetAnneeVFinale/json/tasklist_json.php",
        dataType: 'json',
        success: function (data) {
            $('#waitForDiagram').hide();
            data.forEach(function (item) {
                var id = item.id;
                var nom = item.nom;


                if(id === "1") {

                    var start = extreme.clone();
                        start.position(10, bounds.get('size').height/2-50);
                        start.attr('text/text', nom);
                    graph.addCell(start);

                } else if(id === "2") {

                    var end = extreme.clone();
                        end.position(bounds.get('size').width-110, bounds.get('size').height/2-50);
                        end.attr('text/text', nom);
                    graph.addCell(end);

                } else {

                    var taskRect = rect.clone();
                        taskRect.attr('text/text', nom);
                    if(nom.length > 12) {
                        taskRect.resize(nom.length*10, 60);
                    }
                    graph.addCell(taskRect);
                }
                
            });

            data.forEach(function (item) {

                var tache = getCellByText(item.nom);
                var predecesseurs = item.predecesseurs;
                if(typeof predecesseurs !== 'undefined' && predecesseurs.length > 0) {
                    predecesseurs.forEach(function (pred) {

                        var predTache = getCellByText(pred);
                        var link = lien.clone();
                        link.set('source', { id: predTache.id });
                        link.set('target', { id: tache.id });
                        graph.addCell(link);
                        bounds.embed(link);
                    });
                }
            });

            reorganizeGraphPositions();
        }
    });

    // $('#saveGraph').click(function() {
    //
    //     var jsonGraph = JSON.stringify(graph.toJSON());
    //
    //     console.log(jsonGraph);
    //     $.ajax({
    //         type: "POST",
    //         url: "/ProjetAnnee/PERT_json.php",
    //         data: {myData:jsonGraph},
    //         success: function(data){
    //             alert('SUCCESS');
    //         },
    //         error: function(e){
    //             console.log("Erreur");
    //         }
    //     });
    // });
});

