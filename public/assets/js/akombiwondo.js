
let $typeProduits     = $('#nzoe_accueil_nom');
let $typeSite        = $('#TypeSite');
let $typeLogo        = $('#TypeLogo');
let $typeApplication = $('#TypeApplication');
let $typeCartes      = $('#TypeCartes');
let $typeDesign      = $('#TypeDesign');

$typeProduits.change(function() {

    let $form = $(this).closest('form');
    let data = {};

    data[$typeProduits.attr('name')]       = $typeProduits.val();
    data[$typeSite.attr('name')]           = $typeSite.val();
    data[$typeLogo.attr('name')]           = $typeLogo.val();
    data[$typeApplication.attr('name')]    = $typeApplication.val();
    data[$typeCartes.attr('name')]         = $typeCartes.val();
    data[$typeDesign.attr('name')]         = $typeDesign.val();
    $.ajax({
        url : $form.attr('action'),
        type: $form.attr('method'),
        data : data,
        success: function(html) {
            let select = $typeProduits.val();
            switch (select) {
                case 1 :
                    alert(1);
                    $($typeSite).show();
                    $($typeLogo).hide();
                    $($typeApplication).hide();
                    $($typeCartes).hide();
                    $($typeDesign).hide();
                    break
                case 2 :
                    alert(2);
                    $($typeSite).hide();
                    $($typeLogo).show();
                    $($typeApplication).hide();
                    $($typeCartes).hide();
                    $($typeDesign).hide();
                    break
                case 3 :
                    alert(3);
                    $($typeSite).hide();
                    $($typeLogo).hide();
                    $($typeApplication).show();
                    $($typeCartes).hide();
                    $($typeDesign).hide();
                    break
                case 4 :
                    alert(4);
                    $($typeSite).hide();
                    $($typeLogo).hide();
                    $($typeApplication).hide();
                    $($typeCartes).show();
                    $($typeDesign).hide();
                    break
                case 5 :
                    alert(5);
                    $($typeSite).hide();
                    $($typeLogo).hide();
                    $($typeApplication).hide();
                    $($typeCartes).hide();
                    $($typeDesign).show();
                    break
            }
        },
        error: function () {
            alert("Bonsoir");
        },
        complete : function (resultat, statut) {

        }
    });
});