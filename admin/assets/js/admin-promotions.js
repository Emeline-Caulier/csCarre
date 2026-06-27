/**
 * Gestion AJAX des promotions (back-office).
 * - Modal "Mettre en promotion" depuis l'inventaire (ajout + retrait)
 * - Édition inline du tableau promotions de la vitrine (taux, dates)
 * - Formulaire d'ajout direct depuis accueil_config + suppression
 */

$(document).ready(function () {

    /** Affiche un message vide dans le tbody des promos quand toutes les lignes sont supprimées. */
    function verifierTableVide() {
        if ($('#tbody-promotions tr').length === 0) {
            $('#tbody-promotions').html('<tr><td colspan="7" class="text-center text-muted py-4">Aucune promotion en vitrine pour le moment.</td></tr>');
        }
    }

    /**
     * Construit et insère une nouvelle ligne <tr> dans le tableau des promos.
     * Utilisée après un ajout AJAX réussi pour afficher la promo sans rechargement.
     * @param {Object} data - Données renvoyées par ajaxPromotion.php (id, prix, taux, dates...).
     */
    function ajouterLignePromotion(data) {
        $('#tbody-promotions tr td[colspan]').closest('tr').remove();

        var prix = parseFloat(data.prix);
        var prixPromo = prix * (1 - data.taux_reduction);
        var pct = Math.round(data.taux_reduction * 100);
        var dateDebut = new Date(data.date_debut).toLocaleDateString('fr-FR');
        var dateFin = new Date(data.date_fin).toLocaleDateString('fr-FR');

        var img = data.image_url
            ? '<img src="' + data.image_url + '" alt="Produit" class="rounded object-fit-cover shadow-sm" width="50" height="50">'
            : '<div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width:50px;height:50px;"><i class="bi bi-image"></i></div>';

        var $tr = $(
            '<tr>' +
            '<td>' + img + '</td>' +
            '<td class="fw-semibold">' + data.nom_produit + '</td>' +
            '<td class="text-decoration-line-through text-muted">' + prix.toFixed(2).replace('.', ',') + ' €</td>' +
            '<td class="theme-inline-promo" data-champ="taux_reduction" data-id="' + data.id_promotion + '" data-prix="' + prix + '" data-valeur="' + data.taux_reduction + '" title="Cliquer pour modifier" style="cursor:pointer;"><span class="badge bg-danger">-' + pct + '%</span></td>' +
            '<td class="text-danger fw-bold" id="theme-promo-prix-' + data.id_promotion + '">' + prixPromo.toFixed(2).replace('.', ',') + ' €</td>' +
            '<td class="text-muted small">' +
            '<span class="theme-inline-promo d-block" data-champ="date_debut" data-id="' + data.id_promotion + '" data-valeur="' + data.date_debut + '" title="Cliquer pour modifier" style="cursor:pointer;">Du ' + dateDebut + '</span>' +
            '<span class="theme-inline-promo d-block" data-champ="date_fin" data-id="' + data.id_promotion + '" data-valeur="' + data.date_fin + '" title="Cliquer pour modifier" style="cursor:pointer;">au ' + dateFin + '</span>' +
            '</td>' +
            '<td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm btn-supprimer-promo" data-id="' + data.id_promotion + '"><i class="bi bi-trash3"></i> Retirer</button></td>' +
            '</tr>'
        );
        $tr.hide();
        $('#tbody-promotions').append($tr);
        $tr.fadeIn(400);
    }

    // Promotions depuis l'inventaire (page grille produits)

    // Ouvre la modal "Mettre en promotion" et la pré-remplit avec les infos du produit
    // ciblé (id et nom récupérés via data-attributes du bouton cliqué).
    $(document).on('click', '.btn-ajouter-promo', function () {
        var $btn = $(this);
        $('#modal-promo-id-produit').val($btn.data('id'));
        $('#modal-promo-nom').text($btn.data('nom'));
        $('#modal-promo-taux').val('');
        $('#modal-promo-debut').val('');
        $('#modal-promo-fin').val('');
        new bootstrap.Modal(document.getElementById('modalAjouterPromo')).show();
    });

    // Soumission de la modal "Mettre en promotion" : valide les champs, envoie en AJAX,
    // et au succès met à jour À LA FOIS la carte produit (vue grille) ET la cellule
    // promo du tableau (vue tableau) sans recharger la page.
    $(document).on('click', '#btn-confirmer-promo', function () {
        var idProduit = $('#modal-promo-id-produit').val();
        var taux = $('#modal-promo-taux').val();
        var debut = $('#modal-promo-debut').val();
        var fin = $('#modal-promo-fin').val();

        if (!taux || !debut || !fin) {
            afficherToast('<i class="bi bi-exclamation-triangle me-1"></i>Veuillez remplir tous les champs.', 'error');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Envoi...');

        $.ajax({
            type: 'POST',
            url: 'src/php/ajax/ajaxPromotion.php',
            data: { action_promo: 'ajouter', id_produit: idProduit, taux_reduction: taux, date_debut: debut, date_fin: fin },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalAjouterPromo')).hide();
                    var pct = Math.round(res.data.taux_reduction * 100);
                    var idPromo = res.data.id_promotion;
                    var nom = res.data.nom_produit;

                    var prixOriginal = res.data.prix;
                    var prixFloat = parseFloat(String(prixOriginal).replace(',', '.').replace(/[^0-9.]/g, ''));
                    var prixPromo = prixFloat * (1 - res.data.taux_reduction);
                    var htmlPrixPromo =
                        '<span class="text-decoration-line-through text-muted small">' + prixOriginal + ' €</span>' +
                        ' <span class="text-danger fw-bold ms-1">' + prixPromo.toFixed(2).replace('.', ',') + ' €</span>';
                    $('#carte-prix-' + idProduit).html(htmlPrixPromo);

                    var htmlCartePromo =
                        '<div class="d-flex align-items-center gap-2">' +
                        '<span class="badge bg-danger">-' + pct + '%</span>' +
                        '<button type="button" class="btn btn-outline-secondary btn-sm btn-retirer-promo"' +
                        ' data-id-promo="' + idPromo + '" data-id-produit="' + idProduit + '" data-nom="' + nom + '" data-prix="' + prixOriginal + '">' +
                        '<i class="bi bi-x-circle me-1"></i>Retirer la promotion</button></div>';

                    var htmlTableauPromo =
                        '<div class="d-flex align-items-center gap-1">' +
                        '<span class="badge bg-danger">-' + pct + '%</span>' +
                        '<button type="button" class="btn btn-outline-danger btn-sm btn-retirer-promo"' +
                        ' data-id-promo="' + idPromo + '" data-id-produit="' + idProduit + '" data-nom="' + nom + '">' +
                        '<i class="bi bi-x-lg"></i></button></div>';

                    $('#theme-promo-carte-' + idProduit).html(htmlCartePromo);
                    $('#theme-promo-tableau-' + idProduit).html(htmlTableauPromo);
                    afficherToast('<i class="bi bi-check-circle me-1"></i>' + res.message, 'success');
                } else {
                    afficherToast('<i class="bi bi-exclamation-triangle me-1"></i>' + res.message, 'error');
                }
            },
            error: function () {
                afficherToast('Erreur réseau, veuillez réessayer.', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i>Appliquer la promotion');
            }
        });
    });

    // Retire une promotion depuis l'inventaire : confirmation, AJAX, et inversement
    // de l'opération précédente (on remet le bouton "Mettre en promotion" et le prix nu).
    $(document).on('click', '.btn-retirer-promo', function () {
        var $btn = $(this);
        var idPromo = $btn.data('id-promo');
        var idProduit = $btn.data('id-produit');
        var nom = $btn.data('nom');

        confirmerAction('Retirer la promotion pour "' + nom + '" ?', function () {
            $btn.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: 'src/php/ajax/ajaxPromotion.php',
                data: { action_promo: 'supprimer', id_promotion: idPromo },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        var prixOriginal = $btn.data('prix');
                        if (prixOriginal) {
                            $('#carte-prix-' + idProduit).html('<span class="text-primary fw-bold">' + prixOriginal + ' €</span>');
                        }
                        var htmlAjouter =
                            '<button type="button" class="btn btn-outline-success btn-sm w-100 btn-ajouter-promo"' +
                            ' data-id="' + idProduit + '" data-nom="' + nom + '">' +
                            '<i class="bi bi-tag me-1"></i>Mettre en promotion</button>';
                        var htmlAjouterTableau =
                            '<button type="button" class="btn btn-outline-success btn-sm btn-ajouter-promo"' +
                            ' data-id="' + idProduit + '" data-nom="' + nom + '">' +
                            '<i class="bi bi-plus-lg"></i></button>';
                        $('#theme-promo-carte-' + idProduit).html(htmlAjouter);
                        $('#theme-promo-tableau-' + idProduit).html(htmlAjouterTableau);
                        afficherToast('<i class="bi bi-info-circle me-1"></i>' + res.message, 'success');
                    } else {
                        afficherToast('<i class="bi bi-exclamation-triangle me-1"></i>' + res.message, 'error');
                        $btn.prop('disabled', false);
                    }
                },
                error: function () {
                    afficherToast('Erreur réseau, veuillez réessayer.', 'error');
                    $btn.prop('disabled', false);
                }
            });
        }, 'Retirer');
    });

    // Édition inline du tableau promotions.
    // Clic sur cellule : on remplace par un input typé, le blur envoie l'AJAX,
    // en cas de succès l'affichage formaté est mis à jour, sinon on restaure
    // l'ancien HTML mémorisé dans data('ancien-html') pour annuler proprement.

    $(document).on('click', '.theme-inline-promo', function () {
        var $cell = $(this);
        // Empêche la double-édition si l'utilisateur reclique pendant qu'un input est actif
        if ($cell.find('input').length) return;

        var champ = $cell.data('champ');     // 'taux_reduction' | 'date_debut' | 'date_fin'
        var idPromo = $cell.data('id');
        var valeur = $cell.data('valeur');

        // Mémorise le contenu courant (badge ou date formatée) pour pouvoir l'annuler
        $cell.data('ancien-html', $cell.html());

        // Crée l'input adapté au type de champ : number pour le taux, date pour les dates
        var $input;
        if (champ === 'taux_reduction') {
            $input = $('<input type="number" step="0.01" min="0.01" max="0.99" class="form-control form-control-sm" style="width:80px;">').val(valeur);
        } else {
            $input = $('<input type="date" class="form-control form-control-sm" style="width:150px;">').val(valeur);
        }

        $cell.html($input);
        $input.focus();

        // Au blur de l'input : envoie l'AJAX, puis met à jour ou restaure
        $input.on('blur', function () {
            var nouvelleValeur = $(this).val();
            if (nouvelleValeur === String(valeur)) {
                $cell.html($cell.data('ancien-html'));
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'src/php/ajax/ajaxPromotion.php',
                data: { action_promo: 'modifier', id_promotion: idPromo, champ: champ, nouveau: nouvelleValeur },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        $cell.data('valeur', nouvelleValeur);
                        if (champ === 'taux_reduction') {
                            var pct = Math.round(parseFloat(nouvelleValeur) * 100);
                            $cell.html('<span class="badge bg-danger">-' + pct + '%</span>');
                            var prixPromo = parseFloat($cell.data('prix')) * (1 - parseFloat(nouvelleValeur));
                            $('#theme-promo-prix-' + idPromo).text(prixPromo.toFixed(2).replace('.', ',') + ' €');
                        } else {
                            var prefixe = champ === 'date_debut' ? 'Du ' : 'au ';
                            $cell.text(prefixe + new Date(nouvelleValeur).toLocaleDateString('fr-FR'));
                        }
                        afficherToast('<i class="bi bi-check-circle me-1"></i>Modification enregistrée.', 'success');
                    } else {
                        $cell.html($cell.data('ancien-html'));
                        afficherToast('<i class="bi bi-exclamation-triangle me-1"></i>' + res.message, 'error');
                    }
                },
                error: function () {
                    $cell.html($cell.data('ancien-html'));
                    afficherToast('Erreur réseau, veuillez réessayer.', 'error');
                }
            });
        });

        $input.on('keydown', function (e) {
            if (e.key === 'Enter') $(this).blur();
            if (e.key === 'Escape') $cell.html($cell.data('ancien-html'));
        });
    });

    // Formulaire d'ajout direct depuis accueil_config
    $(document).on('submit', '#form-ajout-promo', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Ajout...');

        $.ajax({
            type: 'POST',
            url: 'src/php/ajax/ajaxPromotion.php',
            data: $form.serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    afficherToast('<i class="bi bi-check-circle me-1"></i>' + res.message, 'success');
                    $form[0].reset();
                    ajouterLignePromotion(res.data);
                } else {
                    afficherToast('<i class="bi bi-exclamation-triangle me-1"></i>' + res.message, 'error');
                }
            },
            error: function () {
                afficherToast('Erreur réseau, veuillez réessayer.', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-plus-circle me-2"></i>Ajouter');
            }
        });
    });

    $(document).on('click', '.btn-supprimer-promo', function () {
        var $btn = $(this);
        var idPromo = $btn.data('id');
        var $tr = $btn.closest('tr');

        confirmerAction('Retirer cette promotion de la vitrine ?', function () {
            $btn.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: 'src/php/ajax/ajaxPromotion.php',
                data: { action_promo: 'supprimer', id_promotion: idPromo },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        $tr.fadeOut(400, function () {
                            $(this).remove();
                            verifierTableVide();
                        });
                        afficherToast('<i class="bi bi-info-circle me-1"></i>' + res.message, 'success');
                    } else {
                        afficherToast('<i class="bi bi-exclamation-triangle me-1"></i>' + res.message, 'error');
                        $btn.prop('disabled', false);
                    }
                },
                error: function () {
                    afficherToast('Erreur réseau, veuillez réessayer.', 'error');
                    $btn.prop('disabled', false);
                }
            });
        }, 'Retirer');
    });
});
