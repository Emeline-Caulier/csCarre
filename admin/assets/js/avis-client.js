/**
 * Modal de soumission d'un avis client depuis le dashboard compte.
 * Système d'étoiles cliquable + validation côté client + upload photo (max 5 Mo).
 * L'envoi final se fait par submit classique du <form> (pas d'AJAX).
 *
 * NB : un fichier admin/assets/js/avis.js existe déjà côté admin pour la modération.
 */

$(document).ready(function () {

    /**
     * Met à jour visuellement les étoiles : étoiles 1..note remplies, le reste vides.
     * Utilise les classes Bootstrap Icons : bi-star-fill (rempli) vs bi-star (vide).
     */
    function setRatingStars($container, note) {
        $container.find('.theme-star').each(function () {
            var v = parseInt($(this).data('value'), 10);
            $(this).toggleClass('bi-star-fill active', v <= note)
                   .toggleClass('bi-star', v > note);
        });
    }

    // Clic sur une étoile : remplit les étoiles 1..N et stocke la note dans l'input caché
    $(document).on('click', '.theme-rating-input .theme-star', function () {
        var $container = $(this).closest('.theme-rating-input');
        var note = parseInt($(this).data('value'), 10);
        setRatingStars($container, note);
        // Cherche le hidden input lié (id="avis-note") dans le même formulaire
        $container.closest('form').find('#avis-note').val(note);
    });

    // Accessibilité clavier (Entrée ou Espace)
    $(document).on('keydown', '.theme-rating-input .theme-star', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).trigger('click');
        }
    });

    // Ouverture du modal : injecte les ids + nom produit, réinitialise tout
    $(document).on('click', '.btn-ouvrir-avis', function () {
        $('#avis-id-commande').val($(this).data('id-commande'));
        $('#avis-id-produit').val($(this).data('id-produit'));
        $('#avis-nom-produit').text($(this).data('nom-produit'));
        $('#avis-note').val('');
        $('#avis-commentaire').val('');
        $('#avis-photo').val('');
        setRatingStars($('#modalAjoutAvis .theme-rating-input'), 0);
    });

    // Vérification immédiate de la taille de la photo (max 5 Mo)
    var TAILLE_MAX_PHOTO_AVIS = 5 * 1024 * 1024;
    $(document).on('change', '#avis-photo', function () {
        var fichier = this.files && this.files[0];
        if (fichier && fichier.size > TAILLE_MAX_PHOTO_AVIS) {
            var taille = (fichier.size / 1024 / 1024).toFixed(1);
            afficherToast('Photo trop volumineuse (' + taille + ' Mo). Maximum autorisé : 5 Mo.', 'danger');
            $(this).val(''); // vide la sélection
        }
    });

    // Validation soumission : refuse si aucune étoile sélectionnée
    // ou si la photo dépasse 5 Mo (double-check au cas où le change a échoué)
    $(document).on('submit', '#form-avis', function (e) {
        if (!parseInt($('#avis-note').val(), 10)) {
            e.preventDefault();
            afficherToast('Merci de sélectionner une note (1 à 5 étoiles).', 'danger');
            return;
        }
        var fichier = $('#avis-photo')[0].files[0];
        if (fichier && fichier.size > TAILLE_MAX_PHOTO_AVIS) {
            e.preventDefault();
            afficherToast('Photo trop volumineuse. Maximum autorisé : 5 Mo.', 'danger');
        }
    });
});
