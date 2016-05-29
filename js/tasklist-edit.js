$(document).ready(function() {
    var table = [];

    var previous;

    $("select").on('focus', function () {

        previous = this.value;
    }).change(function() {
        $(this).parent().find(".previous").empty();
        $(this).parent().find(".previous").append("<b>Previous: </b><span class='prev'>"+ previous + "</span>");
        previous = this.value;
    });

    $('#tableEdition').change(function() {
        table = [];
        var errors = [];
        $('#console').empty();

        $('#tableEdition > tbody  > tr').each(function() {


            var nom = $(this).find("p.nom").text();
            var precs1 = $(this).find("select.precs1");
            var precs2 = $(this).find("select.precs2");
            var suivs1 = $(this).find("select.suivs1");
            var suivs2 = $(this).find("select.suivs2");


            var initprecs1 = precs1.parent().find('.init').text();
            var initprecs2 = precs2.parent().find('.init').text();
            var initsuivs1 = suivs1.parent().find('.init').text();
            var initsuivs2 = suivs2.parent().find('.init').text();

            if(precs1.val() !== initprecs1) {
                if(initprecs1 !== "Start" && initprecs1 !== "") {
                    $('#tableEdition > tbody  > tr').each(function() {
                        var tnom = $(this).find("p.nom").text();
                        var tsuivs1 = $(this).find("select.suivs1");
                        var tsuivs2 = $(this).find("select.suivs2");
                        if(initprecs1 === tnom) {
                            if(tsuivs1.val() === nom) {
                                tsuivs1.val("");
                            }
                            if(tsuivs2.val() === nom) {
                                tsuivs2.val("");
                            }
                        }
                    });
                }
                if(precs1.val() !== "Start" && precs1.val() !== "") {
                    
                    $('#tableEdition > tbody  > tr').each(function() {
                        var tnom = $(this).find("p.nom").text();
                        var tsuivs1 = $(this).find("select.suivs1");
                        var tsuivs2 = $(this).find("select.suivs2");
                        if(precs1.val() === tnom) {
                            console.log(tsuivs2 + "  " + nom);
                            if(tsuivs1.val() === "" || tsuivs1.val() === nom) {
                                tsuivs1.val(nom);
                            } else if(tsuivs2.val() === "" || tsuivs1.val() === nom) {

                                tsuivs2.val(nom);
                            } else {
                                errors.push(true);
                                $('#console').append("<p style='color:red'>Conflict in '" + tnom + "' --> '" + nom + "' has been modified, but '" + tnom + "' has already two successors</p>");
                            }
                        }
                    });
                }

            }
            if(precs2.val() !== initprecs2) {
                if(initprecs2 !== "Start" && initprecs2 !== "") {
                    $('#tableEdition > tbody  > tr').each(function() {
                        var tnom = $(this).find("p.nom").text();
                        var tsuivs1 = $(this).find("select.suivs1");
                        var tsuivs2 = $(this).find("select.suivs2");
                        if(initprecs2 === tnom) {
                            if(tsuivs1.val() === nom) {
                                tsuivs1.val("");
                            }
                            if(tsuivs2.val() === nom) {
                                tsuivs2.val("");
                            }
                        }
                    });
                }
                if(precs2.val() !== "Start" && precs2.val() !== "") {
                    $('#tableEdition > tbody  > tr').each(function() {
                        var tnom = $(this).find("p.nom").text();
                        var tsuivs1 = $(this).find("select.suivs1");
                        var tsuivs2 = $(this).find("select.suivs2");
                        if(precs2.val() === tnom) {
                            if(tsuivs1.val() === "" || tsuivs1.val() === nom) {
                                tsuivs1.val(nom);
                            } else if(tsuivs2.val() === "" || tsuivs1.val() === nom) {
                                tsuivs2.val(nom);
                            } else {
                                errors.push(true);
                                $('#console').append("<p style='color:red'>Conflict in '" + tnom + "' --> '" + nom + "' has been modified, but '" + tnom + "' has already two successors</p>");
                            }
                        }
                    });
                }
            }
            if(suivs1.val() !== initsuivs1) {
                if(initsuivs1 !== "End" && initsuivs1 !== "") {
                    $('#tableEdition > tbody  > tr').each(function() {
                        var tnom = $(this).find("p.nom").text();
                        var tprecs1 = $(this).find("select.precs1");
                        var tprecs2 = $(this).find("select.precs2");
                        if(initsuivs1 === tnom) {
                            if(tprecs1.val() === nom) {
                                tprecs1.val("");
                            }
                            if(tprecs2.val() === nom) {
                                tprecs2.val("");
                            }
                        }
                    });
                }
                if(suivs1.val() !== "End" && suivs1.val() !== "") {
                    $('#tableEdition > tbody  > tr').each(function() {
                        var tnom = $(this).find("p.nom").text();
                        var tprecs1 = $(this).find("select.precs1");
                        var tprecs2 = $(this).find("select.precs2");
                        if(suivs1.val() === tnom) {
                            if(tprecs1.val() === "" || tprecs1.val() === nom) {
                                tprecs1.val(nom);
                            } else if(tprecs2.val() === "" || tprecs2.val() === nom) {
                                tprecs2.val(nom);
                            } else {
                                errors.push(true);
                                $('#console').append("<p style='color:red'>Conflict in '" + tnom + "' --> '" + nom + "' has been modified, but '" + tnom + "' has already two predecessors</p>");
                            }
                        }
                    });
                }
            }
            if(suivs2.val() !== initsuivs2) {
                if(initsuivs2 !== "End" && initsuivs2 !== "") {
                    $('#tableEdition > tbody  > tr').each(function() {
                        var tnom = $(this).find("p.nom").text();
                        var tprecs1 = $(this).find("select.precs1");
                        var tprecs2 = $(this).find("select.precs2");
                        if(initsuivs2 === tnom) {
                            if(tprecs1.val() === nom) {
                                tprecs1.val("");
                            }
                            if(tprecs2.val() === nom) {
                                tprecs2.val("");
                            }
                        }
                    });
                }
                if(suivs2.val() !== "End" && suivs2.val() !== "") {
                    $('#tableEdition > tbody  > tr').each(function() {
                        var tnom = $(this).find("p.nom").text();
                        var tprecs1 = $(this).find("select.precs1");
                        var tprecs2 = $(this).find("select.precs2");
                        if(suivs2.val() === tnom) {
                            if(tprecs1.val() === "" || tprecs1.val() === nom) {
                                tprecs1.val(nom);
                            } else if(tprecs2.val() === "" || tprecs2.val() === nom) {
                                tprecs2.val(nom);
                            } else {
                                errors.push(true);
                                $('#console').append("<p style='color:red'>Conflict in '" + tnom + "' --> '" + nom + "' has been modified, but '" + tnom + "' has already two predecessors</p>");
                            }
                        }
                    });
                }
            }


            if(precs1.val() === precs2.val()) {
                errors.push(true);
                if(precs1.val() === "") {
                    $('#console').append("<p style='color:red'>Conflict in '" + nom + "' --> both predecessors are null</p>");
                } else {
                    $('#console').append("<p style='color:red'>Conflict in '" + nom + "' --> predecessors must have different values</p>");
                }

            } else {
                errors.push(false);
            }
            if(suivs1.val() === suivs2.val()) {
                errors.push(true);
                if(suivs1.val() === "") {
                    $('#console').append("<p style='color:red'>Conflict in '" + nom + "' --> both successors are null</p>");
                } else {
                    $('#console').append("<p style='color:red'>Conflict in '" + nom + "' --> successors must have different values</p>");
                }
            } else {
                errors.push(false);
            }
            if(precs1.val() !== '' && precs1.val() === suivs1.val()) {
                errors.push(true);
                $('#console').append("<p style='color:red'>Conflict in '" + nom + "' --> predecessor and successor must have different values</p>");
            } else {
                errors.push(false);
            }
            if(precs1.val() !== '' && precs1.val() === suivs2.val()) {
                errors.push(true);
                $('#console').append("<p style='color:red'>Conflict in '" + nom + "' --> predecessor and successor must have different values</p>");
            } else {
                errors.push(false);
            }
            if(precs2.val() !== '' && precs2.val() === suivs1.val()) {
                errors.push(true);
                $('#console').append("<p style='color:red'>Conflict in '" + nom + "' --> predecessor and successor must have different values</p>");
            } else {
                errors.push(false);
            }
            if(precs2.val() !== '' && precs2.val() === suivs2.val()) {
                errors.push(true);
                $('#console').append("<p style='color:red'>Conflict in '" + nom + "' --> predecessor and successor must have different values</p>");
            } else {
                errors.push(false);
            }


            var tache = {nom:nom, precedent1:precs1.val(), precedent2:precs2.val(), suivant1:suivs1.val(), suivant2:suivs2.val()};
            table.push(tache);
        });



        //On compte le nombre d'erreurs
        var countErrs = 0;
        errors.forEach(function(err) {
            if (err === true) {
                countErrs++;
            }
        });
        //On disable le bouton de validation tant qu'il y a des erreurs
        if (countErrs !== 0) {
            $('#validateTableButton').prop("disabled", true);
        } else {
            $('#validateTableButton').prop("disabled", false);
            $('#console').append("<p style='color:green'>No conflict</p>");
        }

    });


    $('#validateTableForm').submit(function() {
        console.log(JSON.stringify(table));
        $.ajax({
            type: "POST",
            url: "/ProjetAnnee/update.php",
            data: {json: JSON.stringify(table)},
            success: function(data){alert("good");},
            fail: function(errMsg) {
                alert("hey");
            }
        });
    });

});

