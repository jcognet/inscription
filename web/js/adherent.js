var listeAdherentId = 'divListeAdherent'
var routeRecherche = 'app_adherent_recherche';
var formRechercheId = 'formRechercheAdherent';
var btnFormRechercheId = 'btnRechercherAdherent';
function rechercheAdherent(sort, direction, page) {
    setAjaxWorking(listeAdherentId);
    url = Routing.generate(routeRecherche)+createQueryStringPaginatior(sort, direction, page);
    $.ajax({
        url: url,
        type: 'POST',
        data: $('#'+formRechercheId).serialize(),
        cache: false,
        dataType: 'html',
        success: function (block_html, statut) { // success est toujours en place, bien sûr !
            $('#' + listeAdherentId).html(block_html);
            addEventSort();
            unsetAjaxWorking(listeAdherentId);
        },

        error: function (resultat, statut, erreur) {
            unsetAjaxWorking(listeAdherentId);
        }

    });
}
$(document).ready(function() {
    $('#'+btnFormRechercheId).on('click', function (e) {
        rechercheAdherent('', '', '');
        e.stopImmediatePropagation();
        e.preventDefault();
    });
    addEventSort();
});

function addEventSort() {

    $('table.fe_liste th a').on('click', function (e) {
        var lien = $(this);
        var sort = getParameterByName('sort', lien.attr('href'));
        var direction = getParameterByName('direction', lien.attr('href'));
        var page = getParameterByName('page', lien.attr('href'));

        rechercheAdherent(sort, direction, page);
        e.stopImmediatePropagation();
        e.preventDefault();
        return false;
    });
}