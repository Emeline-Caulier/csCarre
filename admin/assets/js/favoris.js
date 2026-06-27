// Liste d'envie (favoris) côté client.
// Au chargement, on demande au serveur la liste des produits favoris pour la
// session/le client courant, puis on applique l'état "cœur plein" partout où
// le produit apparaît (grille, carrousel, page détail).
// Au clic sur un cœur, on inverse l'état côté serveur et on synchronise tous
// les boutons liés au même produit (même data-id).

$(document).ready(function () {

    function appliquerEtatFavoris(ids) {
        ids.forEach(function (id) {
            $('.btn-wishlist-icon[data-id="' + id + '"]')
                .addClass('active')
                .find('i').removeClass('bi-heart').addClass('bi-heart-fill');
        });
        var $btnDetail = $('#btn-favoris');
        if ($btnDetail.length && ids.indexOf(parseInt($btnDetail.data('id'))) !== -1) {
            $btnDetail.addClass('active').html('<i class="bi bi-heart-fill me-2"></i>Dans vos favoris');
        }
    }

    $.ajax({
        url: 'admin/src/php/ajax/ajax_etat_favoris.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data && data.ids) appliquerEtatFavoris(data.ids);
        }
    });

    function toggleFavori(idProduit) {
        $.ajax({
            url: 'admin/src/php/ajax/ajax_toggle_favori.php',
            type: 'POST',
            dataType: 'json',
            data: { id_produit: idProduit },
            success: function (retour) {
                if (!retour.success) return;
                var actif = retour.actif;
                $('.btn-wishlist-icon[data-id="' + idProduit + '"]').each(function () {
                    var $btn = $(this);
                    $btn.toggleClass('active', actif)
                        .find('i')
                        .toggleClass('bi-heart', !actif)
                        .toggleClass('bi-heart-fill', actif);

                    if (!actif) {
                        var $col = $btn.closest('[class^="col"]');
                        if ($col.length && $col.parent().is('#liste-envie-grille')) {
                            $col.fadeOut(300, function () {
                                $(this).remove();
                                if ($('#liste-envie-grille').children().length === 0) {
                                    $('#liste-envie-grille').closest('.p-4').html(
                                        '<div class="text-center py-5 theme-dashboard-bloc">' +
                                        '<i class="bi bi-heart-slash fs-1 d-block mb-3 text-muted"></i>' +
                                        '<p class="fw-semibold text-muted">Votre liste d\'envie est vide.</p>' +
                                        '<a href="index_.php?page=catalogue.php" class="btn btn-primary mt-3">Découvrir nos bijoux</a>' +
                                        '</div>'
                                    );
                                }
                            });
                        }
                    }
                });
                var $btnDetail = $('#btn-favoris');
                if ($btnDetail.length && $btnDetail.data('id') == idProduit) {
                    $btnDetail.toggleClass('active', actif)
                        .html(actif
                            ? '<i class="bi bi-heart-fill me-2"></i>Dans vos favoris'
                            : '<i class="bi bi-heart me-2"></i>Ajouter aux favoris');
                }
                afficherToast(retour.message, 'success');
            }
        });
    }

    $(document).on('click', '.btn-wishlist-icon', function () {
        var id = $(this).data('id');
        if (id) toggleFavori(id);
    });

    $(document).on('click', '#btn-favoris', function () {
        toggleFavori($(this).data('id'));
    });
});
