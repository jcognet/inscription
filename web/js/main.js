$(document).ready(function () {
    // Mise en place d'événements
    changeSort();
    alerteSiSuppression();
    changeSaison();
    addEventOpenClose();
});
// Gestion des erreurs ajax
$( document ).ajaxError(function( event, request, settings ) {
    console.log('*******Erreur*******');
    console.log(event);
    console.log(request);
    console.log(settings);
    console.log('********************');
});
// Lance l'ajax
function setAjaxWorking(divId) {
    var divAjax = $("#" + divId);
    var loader = $("#loader_ajax").clone();
    divAjax.addClass('ajax');
    loader.css('margin-left', divAjax.width()/2 + 'px');
    loader.css('margin-top', divAjax.height()/2 + 'px');
    loader.addClass('loader');
    loader.attr('id', 'loader_ajax_' + divId).show().prependTo("#" + divId);
}
// Arrête l'Ajax
function unsetAjaxWorking(divId) {
    $("#" + divId).removeClass('ajax');
    $("#loader_ajax_" + divId).remove();
}
// Gère les tris
function changeSort(){
    $('.sorted .asc').each(function(){
        $(this).append('<span class="glyphicon glyphicon-arrow-down"></span>');
    });
    $('.sorted .desc').each(function(){
        $(this).append('<span class="glyphicon glyphicon-arrow-up"></span>');
    });
}
// Gère l'alerte de suppression
function alerteSiSuppression(){
    $('.lnk_suppression').each(function(){
        $(this).click( function(e){
            if(!confirm('Voulez-vous vraiment supprimer cet élément ? ')){
                e.preventDefault();
                return false;
            }
        });
    });
}
// Gère le changement de saison dans l'application
function changeSaison(){
    $("#ddlSelectSaison").on("change", function(){
        var form =$("#ddlSelectSaison").closest('form');
        form.submit();
    });
}
// Gère la fermeture ouverture de zone
function addEventOpenClose() {
    $('.div_open_close').each(function(){
        var that = $(this);
        if(false == that.hasClass('visible')){
            that.hide();
        }
    });
    // Gestion du click
    $('.btn_open_close').on('click', function(e){
        var that = $(this);
        var zoneOpenClose = that.parent().find('.div_open_close').first();
        var etaitVisible = zoneOpenClose.is(':visible');
        zoneOpenClose.slideToggle();
        console.log(zoneOpenClose);
        e.stopImmediatePropagation();
        e.preventDefault();
    });
}

// Get parametre from url
function getParameterByName(name, url) {
    if (!url) {
        url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

// Create query string for paginator
function createQueryStringPaginatior(sort, direction, page) {
    var queryString = "?"
    if (!sort) {
        sort = '';
    } else {
        queryString = queryString + 'sort=' + sort + '&'
    }
    if (!direction) {
        direction = '';
    } else {
        queryString = queryString + 'direction=' + direction + '&'
    }
    if (!page) {
        page = '';
    } else {
        queryString = queryString + 'page=' + page + '&'
    }
    return queryString;
}