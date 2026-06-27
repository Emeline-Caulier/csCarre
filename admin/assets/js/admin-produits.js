// Administration des produits (back-office) :
//   - édition inline contenteditable du tableau produits
//   - changement de catégorie via <select>
//   - affichage du formulaire d'ajout
//   - suppression d'un produit (vue cartes via modal Bootstrap, vue tableau via lien rapide)
//
// Édition inline : focus mémorise la valeur, blur envoie l'AJAX et met à jour
// la carte miroir (#carte-CHAMP-ID). Enter sauvegarde, Escape annule.

$(document).ready(function () {

    // Édition inline (contenteditable)

    // Enter = sauvegarde immédiate (déclenche blur).
    // Escape = annulation : restaure la valeur d'origine sans envoyer d'AJAX.
    // Sans cette interception, Enter insérerait un <div> dans la cellule (Chrome).
    $(document).on('keydown', 'td[contenteditable="true"]', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        }
        if (e.key === 'Escape') {
            e.preventDefault();
            var ancien = $(this).data('ancien');
            if (ancien !== undefined) {
                this.innerText = ancien;
            }
            $(this).blur();
        }
    });

    // Focus : on mémorise la valeur courante pour pouvoir l'annuler (Escape) ou
    // détecter un "no-op" au blur (pas d'AJAX si la valeur n'a pas changé).
    $(document).on('focus', 'td[contenteditable="true"]', function () {
        var texte = this.innerText.replace(/[\n\r]+/g, ' ').trim();
        $(this).data('ancien', texte);
    });

    $(document).on('blur', 'td[contenteditable="true"]', function () {
        let idProduit = $(this).attr('id');
        let champ = $(this).data('champ');
        let nouvelleValeur = this.innerText.replace(/[\n\r]+/g, ' ').trim();
        let ancienneValeur = $(this).data('ancien');
        if (ancienneValeur === undefined || nouvelleValeur === ancienneValeur) return;

        $.ajax({
            url: 'src/php/ajax/ajaxUpdateProduit.php',
            type: 'GET',
            data: {
                id_produit: idProduit,
                champ: champ,
                nouveau: nouvelleValeur
            },
            success: function(response) {
                let elementCarte = $('#carte-' + champ + '-' + idProduit);
                if (elementCarte.length) {
                    if (champ === 'stock') {
                        let stockVal = parseInt(nouvelleValeur);
                        elementCarte.removeClass('bg-success bg-warning bg-danger text-dark');

                        if (stockVal === 0) {
                            elementCarte.text('Rupture de stock').addClass('bg-danger');
                        } else if (stockVal <= 5) {
                            elementCarte.text('Stock faible : ' + stockVal).addClass('bg-warning text-dark');
                        } else {
                            elementCarte.text('En stock : ' + stockVal).addClass('bg-success');
                        }
                    }
                    // Cas du prix : on préserve la structure promo si présente
                    else if (champ === 'prix') {
                        let prixVal = parseFloat(String(nouvelleValeur).replace(',', '.'));
                        let prixFmt = prixVal.toFixed(2).replace('.', ',') + ' €';
                        // Si une promo est affichée (texte barré), on met à jour le prix barré
                        let spanBarre = elementCarte.find('.text-decoration-line-through');
                        if (spanBarre.length) {
                            spanBarre.text(prixFmt);
                        } else {
                            // Cas sans promo : on conserve le span stylé et on n'écrase que son texte
                            let spanPrix = elementCarte.find('.text-primary.fw-bold');
                            if (spanPrix.length) {
                                spanPrix.text(prixFmt);
                            } else {
                                elementCarte.html('<span class="text-primary fw-bold">' + prixFmt + '</span>');
                            }
                        }
                        // Synchronise les data-prix des boutons promo (carte + tableau)
                        $('[data-id="' + idProduit + '"].btn-ajouter-promo').attr('data-nom-prix', prixVal);
                        $('[data-id-produit="' + idProduit + '"].btn-retirer-promo').attr('data-prix', prixVal);
                    }
                    // Autres champs (nom produit, etc.) : on remplace juste le texte
                    else {
                        elementCarte.text(nouvelleValeur);
                    }
                }
            },
            error: function() {
                afficherToast('<i class="bi bi-exclamation-circle me-1"></i>Erreur lors de la mise à jour.', 'danger');
            }
        });
    });

    // Changement de catégorie via <select> dans une cellule : envoi AJAX
    $('td[data-champ] select').change(function () {
        var nouveau = $(this).val();
        var td = $(this).closest('td');
        var champ = td.data('champ');
        var id = td.attr('id');
        var param = 'champ=' + champ + '&nouveau=' + nouveau + '&id_produit=' + id;
        $.ajax({
            type: 'get',
            dataType: 'json',
            data: param,
            url: 'src/php/ajax/ajaxUpdateProduit.php',
            success: function (data) {
                console.log('Mise à jour : ' + data);
            }
        });
    });

    // Le formulaire d'ajout produit est masqué par défaut, révélé au clic sur "Insérer"
    $('#ajout_nouveau').hide();
    $('#inserer').click(function () {
        $('#ajout_nouveau').show();
    });

    // Suppression produit, vue cartes (modal Bootstrap)
    var _supprimerProduitId = null;
    var _supprimerProduitCarte = null;

    $(document).on('click', '[data-bs-target="#modalSupprimerProduit"]', function () {
        // Si on clique sur l'icône <i>, on remonte au bouton parent qui porte data-id
        var $btn = $(this).is('[data-id]') ? $(this) : $(this).closest('[data-id]');
        _supprimerProduitId = $btn.data('id');
        _supprimerProduitCarte = $btn.closest('.col-md-3, .col-sm-6'); // la colonne de la grille
        $('#modal-produit-nom').text($btn.data('nom'));
    });

    // Étape 2 : confirmation de la suppression dans la modal
    $(document).on('click', '#link-produit-delete', function (e) {
        e.preventDefault();
        bootstrap.Modal.getInstance(document.getElementById('modalSupprimerProduit')).hide();
        var id = _supprimerProduitId;
        var $carte = _supprimerProduitCarte;
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: { id_produit: id },
            url: 'src/php/ajax/ajaxDeleteProduit.php',
            success: function (data) {
                if (data) {
                    $carte.fadeOut('slow', function () { $(this).remove(); });
                } else {
                    afficherToast('Impossible de retirer ce produit.', 'error');
                }
            },
            error: function () {
                afficherToast('Erreur réseau lors du retrait du produit.', 'error');
            }
        });
    });

    // Suppression produit, vue tableau (lien rapide sans modal Bootstrap).
    // Confirmation via la modal générique, fadeOut sur le <tr>, puis appel AJAX.

    $(document).on('click', '.delete-produit', function (e) {
        e.preventDefault();
        var idProduit = $(this).data('id');
        var $tr = $(this).closest('tr');
        confirmerAction('Retirer ce produit de la vitrine ?', function () {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: { id_produit: idProduit },
                url: 'src/php/ajax/ajaxDeleteProduit.php',
                success: function (data) {
                    if (data) {
                        $tr.fadeOut('slow', function () { $(this).remove(); });
                    } else {
                        afficherToast('Impossible de retirer ce produit.', 'error');
                    }
                },
                error: function () {
                    afficherToast('Erreur réseau lors du retrait du produit.', 'error');
                }
            });
        }, 'Retirer');
    });
});
