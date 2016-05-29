$(document).ready(function() {
    $('.law').hide();
    $('p.message').hide();

    $('div.tache').hover(function() {

        var listePredecesseurs = $(this).find('ul.successeur');
        var listeSuccesseurs = $(this).find('ul.predecesseur');

        var erreur = false;
        listePredecesseurs.find('li').each(function() {
            var idSucc = $(this).val();
            listeSuccesseurs.find('li').each(function() {
                var idPred = $(this).val();
                if (idSucc === idPred) {
                    console.log('erreur');
                    erreur = true;
                }
            });
        });

        if(erreur) {
            $(this).find('p.message').show();
            $(this).find('button.submitNvxPreds').prop('disabled', true);
            $(this).find('button.submitNvxSuccs').prop('disabled', true);
        } else {
            $(this).find('p.message').hide();
            $(this).find('button.submitNvxPreds').prop('disabled', false);
            $(this).find('button.submitNvxSuccs').prop('disabled', false);
        }

    });


    //Ajout du prédécesseur selectionné à la liste
    $('select.selectorpreds').change(function() {
        var id = $(this).find('option:selected').val();
        var nom = $(this).find('option:selected').text();

        //On évite de rajouter le même élément
        if ($(this).parent().find('#listepreds').has('li[value=' + id +']').length === 0) {
            $(this).parent().find('#listepreds').append('<li class="list-group-item" value="'+ id +'"><span class="glyphicon glyphicon-remove predecesseur" style="color:darkred; cursor: pointer;"></span>'+ nom +'</li>');
        };

    });

    //Ajout du successeur selectionné à la liste
    $('select.selectorsuccs').change(function() {
        var id = $(this).find('option:selected').val();
        var nom = $(this).find('option:selected').text();

        //On évite de rajouter le même élément
        if ($(this).parent().find('#listesuccs').has('li[value=' + id +']').length === 0) {
            $(this).parent().find('#listesuccs').append('<li class="list-group-item" value="'+ id +'"><span class="glyphicon glyphicon-remove successeur" style="color:darkred; cursor: pointer;"></span>'+ nom +'</li>');
        };
    });

    //Suppression d'un prédécesseur lors du click sur X
    $('ul').on("click","span.predecesseur", function() {
        $(this).parent().remove();
    });

    //Suppression d'un successeur lors du click sur X
    $('ul').on("click","span.successeur", function() {
        $(this).parent().remove();
    });

    //Envoi de la nouvelle liste des prédécesseurs à update.php
    $('button.submitNvxPreds').click(function() {
        var idTache = $(this).attr('id');
        var listePreds = [];

        $(this).parent().find('#listepreds').find('li').each(function(li) {
            listePreds.push($(this).val());
        });

        $.ajax({
            type: "POST",
            url: "/ProjetAnneeVFinale/update.php",
            data: {idTache:idTache, listePredecesseurs:JSON.stringify(listePreds)},
            success: function(data){location.reload(true);}
        });
    });

    //Envoi de la nouvelle liste des successeurs à update.php
    $('button.submitNvxSuccs').click(function() {
        var idTache = $(this).attr('id');
        var listeSuccs = [];

        $(this).parent().find('#listesuccs').find('li').each(function(li) {
            listeSuccs.push($(this).val());
        });
        
        $.ajax({
            type: "POST",
            url: "/ProjetAnneeVFinale/update.php",
            data: {idTache:idTache, listeSuccesseurs:JSON.stringify(listeSuccs)},
            success: function(data){location.reload(true);}
        });
    });

    //Synchronisation des deux selecteurs de la page d'ajout de nouvelle tâche:
    //quand on select une option dans un selecteur, l'option disparaît de l'autre
    $('#leftTaskSelector').change(function() {
        $(this).find('option:selected').each(function() {
            var id = $(this).val();
            $('#rightTaskSelector').find('option[value=' + id + ']').hide();
        });
        $(this).find('option:not(:selected)').each(function() {
            var id = $(this).val();
            $('#rightTaskSelector').find('option[value=' + id + ']').show();
        });
    });
    $('#rightTaskSelector').change(function() {
        $(this).find('option:selected').each(function() {
            var id = $(this).val();
            $('#leftTaskSelector').find('option[value=' + id + ']').hide();
        });
        $(this).find('option:not(:selected)').each(function() {
            var id = $(this).val();
            $('#leftTaskSelector').find('option[value=' + id + ']').show();
        });
    });


});

$(document).on('click', '.btn-group button', function (e) {
    $('.law').hide();
    $('.btn-law.active').removeClass('active');
    $('#blk-'+$(this).val()).slideToggle();
    if($(this).val() === "1") {
        $('#lawName').val("uniforme");
    } else if($(this).val() === "2") {
        $('#lawName').val("beta");
    } else if($(this).val() === "3") {
        $('#lawName').val("triangulaire");
    } else if($(this).val() === "4") {
        $('#lawName').val("normale");
    } else if($(this).val() === "5") {
        $('#lawName').val("sansLoi");
    }
});