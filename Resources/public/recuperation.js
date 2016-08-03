$("#livres").on('click',function(){
    affListe("Livre")
});
$("#mangas").on('click',function(){
    affListe("Manga")
});
$("#jeux").on('click',function(){
    affListe("Jeu")
});
$("#films").on('click',function(){
    affListe("Film")
});
$("#series").on('click',function(){
    affListe("Serie")
});
$("#animes").on('click',function(){
    affListe("Anime")
});
$("#comics").on('click',function(){
    affListe("Comic")
});
$("#bd").on('click',function(){
    affListe("BD");
});
$("#saveListe").on('click',function(){
    sauvegarde();
});


function affListe(type)
{
    type_en_cours = type;
    $.ajax({
        type: "GET",
        url: "http://old.perso.dev/app_dev.php/api/extract/" + type,
        cache: false,
        success: function (data) {
            donneesBrutes = data;
            $("#saveListe").prop('disabled', false);
            $("#tableRecup tr").remove();
            tab = JSON.parse(donneesBrutes);
            for (var i = 0; i < tab.length; i++) {
                var ajout = "<tr>";
                if(tab[i]['nom'])
                {
                    ajout += "<td>" + tab[i]['nom'] + "</td>";
                }
                if(tab[i]['date'])
                {
                    var date = new Date(tab[i]['date_parution']);
                    date = date.getDate() + "/" + date.getMonth() + "/" + date.getFullYear();
                    ajout += "<td>" + date + "</td>";
                }
                if(tab[i]['fiche'])
                {
                    ajout += "<td>" + tab[i]['fiche'] + "</td>";
                }
                else {
                    ajout += "<td></td>";
                }
                if(tab[i]['note'])
                {
                    ajout += "<td>" + tab[i]['note'] + " / 10</td>";
                }
                if(tab[i]['last_consomme'] != undefined)
                {
                    ajout += "<td>" + tab[i]['last_consomme'] + "</td>";
                }
                if(tab[i]['nb_connu'] != undefined)
                {
                    ajout += "<td>" + tab[i]['nb_connu'] + "</td>";
                }
                if(tab[i]['possede'] != undefined)
                {
                    ajout += "<td>" + tab[i]['possede'] + "</td>";
                }
                if(tab[i]['consomme'] != undefined)
                {
                    ajout += "<td>" + tab[i]['consomme'] + "</td>";
                }
                if(tab[i]['fini'] != undefined)
                {
                    ajout += "<td>" + tab[i]['fini'] + "</td>";
                }
                if(tab[i]['streaming'] != undefined)
                {
                    ajout += "<td>" + tab[i]['streaming'] + "</td>";
                }
                if(tab[i]['num_tome'] != undefined)
                {
                    ajout += "<td>" + tab[i]['num_tome'] + "</td>";
                }
                ajout += "</tr>";
                $("#tableRecup").append(ajout);
            }
        }
    });
    return false;
}
