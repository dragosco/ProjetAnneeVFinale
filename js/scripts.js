$(document).ready(function() {
    $('.law').hide();

    $('select').change(function() {
        var id = $(this).find('option:selected').val();
        var nom = $(this).find('option:selected').text();

        $(this).parent().find('ul').append('<li class="list-group-item" value="'+ id +'"><span class="glyphicon glyphicon-remove" style="color:darkred; cursor: pointer;"></span>'+ nom +'</li>')
    });

    $('ul').on("click","span.predecesseur", function() {
        $(this).parent().remove();
    });

    $('ul').on("click","span.predecesseur", function() {
        $(this).parent().remove();
    });

    // var nbclicksDltPred = 0;
    // $('ul').on("click","span.predecesseur", function(){
    //         if (nbClicksDltPred === 0) {
    //             $('p.message.predecesseur').append('Click again to confirm delete');
    //         } else {
    //             var idTache = $(this).parent().attr('id');
    //             var idPredecesseur = $(this).parent().val();
    //             var params = {idTache: idTache, idPredecesseur: idPredecesseur};
    //
    //             $.ajax({
    //                 type: 'POST',
    //                 url: "/ProjetAnneeV2/update.php",
    //                 data: params,
    //                 dataType: 'json',
    //                 success: function (data) {
    //                 }
    //             });
    //
    //             $(this).parent().remove();
    //             $('p.message.predecesseur').empty();
    //         }
    //         nbClicksDltPred ++;
    //         setTimeout(function() {nbClicksDltPred = 0; $('p.message.predecesseur').empty();}, 2000);
    //     });
    //
    // var nbClicksDltSucc = 0;
    // $('ul').on("click","span.successeur", function(){
    //
    //         if (nbClicksDltSucc === 0) {
    //             $('p.message.successeur').append('Click again to confirm delete');
    //         } else {
    //             var idTache = $(this).parent().attr('id');
    //             var idSuccesseur = $(this).parent().val();
    //             var params = {idTache: idTache, idSuccesseur: idSuccesseur};
    //
    //             $.ajax({
    //                 type: 'POST',
    //                 url: "/ProjetAnneeV2/update.php",
    //                 data: params,
    //                 success: function (data) {
    //                 }
    //             });
    //
    //             $(this).parent().remove();
    //             $('p.message.successeur').empty();
    //         }
    //         nbClicksDltSucc ++;
    //         setTimeout(function() {nbClicksDltSucc = 0; $('p.message.successeur').empty();}, 2000);
    // });
    
    
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