// Section "Nouvelle collection" sur la page accueil_config.php (back-office).
// Quatre actions : éditer le nom de la section, ajouter un produit via dropdown,
// retirer un produit, et toggler "est_nouveau" depuis la vue tableau.

$(document).ready(function () {

    // Échappement HTML manuel pour les chaînes injectées via concat (.html()).
    // Indispensable car ncAjouterLigne construit du HTML brut à partir de données
    // serveur (nom_produit pourrait contenir des caractères spéciaux).
    function ncEscHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // Construit la nouvelle ligne <tr> et l'insère dans le tableau de la collection.
    // Retire d'abord la ligne "vide" si elle est présente.
    // Reçoit un objet produit (id, nom, prix, image_url) renvoyé par l'AJAX.
    function ncAjouterLigne(p) {
        $('#nc-ligne-vide').remove();
        var img = p.image_url
            ? '<img src="' + ncEscHtml(p.image_url) + '" width="50" height="50" class="rounded object-fit-cover shadow-sm">'
            : '<div class="bg-light rounded d-flex align-items-center justify-content-center text-muted dash-img-placeholder"><i class="bi bi-image"></i></div>';
        var prix = parseFloat(p.prix).toFixed(2).replace('.', ',') + ' €';
        var ligne = '<tr id="nc-ligne-' + p.id_produit + '">'
            + '<td>' + img + '</td>'
            + '<td class="fw-semibold">' + ncEscHtml(p.nom_produit) + '</td>'
            + '<td class="text-muted">' + prix + '</td>'
            + '<td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm btn-retirer-nc" data-id="' + p.id_produit + '"><i class="bi bi-trash3"></i> Retirer</button></td>'
            + '</tr>';
        $('#tbody-nouvelle-collection').append(ligne);
    }

    // Sauvegarde automatique du nom de la section "Nouvelle collection" au blur du champ
    $('#nc-nom-input').on('blur', function () {
        var nom = $(this).val().trim();
        if (nom === '') return;
        $.post('src/php/ajax/ajaxNouvelleCollection.php', { action: 'modifier_nom', nom: nom })
            .done(function (res) {
                if (res.success) afficherToast('Nom de la collection mis à jour.', 'success');
            });
    });

    // Ajout d'un produit à la collection via le <select> dropdown :
    // au succès, on ajoute la ligne dans le tableau ET on retire le produit du <select>
    // pour qu'il ne puisse pas être ajouté deux fois.
    $('#btn-ajouter-nc').on('click', function () {
        var id = $('#nc-select-produit').val();
        if (!id) return;
        $.post('src/php/ajax/ajaxNouvelleCollection.php', { action: 'ajouter', id_produit: id }, function (res) {
            if (res.success) {
                ncAjouterLigne(res.data);
                $('#nc-select-produit option[value="' + id + '"]').remove();
                $('#nc-select-produit').val('');
                afficherToast('Produit ajouté à la collection.', 'success');
            }
        });
    });

    // Retirer un produit : confirmation, AJAX, fadeOut de la ligne, et on remet le
    // produit dans le <select> pour permettre de le ré-ajouter facilement.
    $(document).on('click', '.btn-retirer-nc', function () {
        var $btn = $(this);
        var id = $btn.data('id');
        var nom = $btn.closest('tr').find('td:nth-child(2)').text().trim();
        var prix = $btn.closest('tr').find('td:nth-child(3)').text().trim();
        confirmerAction('Retirer ce produit de la collection ?', function () {
            $.post('src/php/ajax/ajaxNouvelleCollection.php', { action: 'supprimer', id_produit: id }, function (res) {
                if (res.success) {
                    $('#nc-ligne-' + id).fadeOut(300, function () {
                        $(this).remove();
                        if ($('#tbody-nouvelle-collection tr').length === 0) {
                            $('#tbody-nouvelle-collection').append('<tr id="nc-ligne-vide"><td colspan="4" class="text-center text-muted py-4">Aucun produit dans la collection pour le moment.</td></tr>');
                        }
                    });
                    $('#nc-select-produit').append('<option value="' + id + '">' + ncEscHtml(nom) + ' — ' + prix + '</option>');
                    afficherToast('Produit retiré de la collection.', 'warning');
                }
            });
        }, 'Retirer');
    });

    // Toggle "est_nouveau" depuis la vue tableau : checkbox synchronisée avec la BDD.
    // Si l'AJAX échoue, on annule visuellement le toggle (revient à l'état précédent).
    $(document).on('change', '.chk-est-nouveau', function () {
        var $chk = $(this);
        var id = $chk.data('id');
        var action = $chk.is(':checked') ? 'ajouter' : 'supprimer';
        $.post('src/php/ajax/ajaxNouvelleCollection.php', { action: action, id_produit: id }, function (res) {
            if (res.success) {
                var msg = action === 'ajouter' ? 'Produit ajouté à la nouveauté.' : 'Produit retiré de la nouveauté.';
                afficherToast(msg, action === 'ajouter' ? 'success' : 'warning');
            } else {
                $chk.prop('checked', !$chk.is(':checked'));
                afficherToast('Erreur lors de la mise à jour.', 'danger');
            }
        }, 'json').fail(function () {
            $chk.prop('checked', !$chk.is(':checked'));
            afficherToast('Erreur lors de la mise à jour.', 'danger');
        });
    });
});
