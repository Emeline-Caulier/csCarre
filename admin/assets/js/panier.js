// Panier client (page panier.php et boutons "Acheter" partout sur le site).
// Trois écouteurs : +/- de quantité, suppression d'un article, ajout au panier.

// Clic sur les boutons "+" et "-" de quantité dans le panier.
// Récupère la quantité courante depuis l'input adjacent, la borne entre 1 et
// data-stock (stock max injecté par panier.php), puis envoie la nouvelle valeur
// au serveur. Recharge la page si succès, sinon réactive le bouton et affiche
// un toast d'erreur.
$(document).on('click', '.btn-qte-plus, .btn-qte-moins', function() {
    let btn = $(this);
    let idProduit = btn.data('id');
    let input = btn.siblings('.input-qte');
    let qteActuelle = parseInt(input.val());

    let nouvelleQte = btn.hasClass('btn-qte-plus') ? qteActuelle + 1 : qteActuelle - 1;
    if (nouvelleQte < 1) return;

    let stockMax = parseInt(input.data('stock'));
    if (!isNaN(stockMax) && nouvelleQte > stockMax) {
        afficherToast('<i class="bi bi-exclamation-circle me-1"></i>Stock maximum atteint (' + stockMax + ').', 'warning');
        return;
    }

    btn.prop('disabled', true);

    $.ajax({
        url: 'admin/src/php/ajax/ajax_update_panier.php',
        type: 'POST',
        dataType: 'json',
        data: {
            id_produit: idProduit,
            quantite: nouvelleQte
        },
        success: function(retour) {
            if (retour.success) {
                location.reload();
            } else {
                afficherToast('<i class="bi bi-exclamation-circle me-1"></i>' + retour.message, 'danger');
                btn.prop('disabled', false);
            }
        },
        error: function() {
            afficherToast('<i class="bi bi-exclamation-circle me-1"></i>Erreur de communication avec le serveur.', 'danger');
            btn.prop('disabled', false);
        }
    });
});

// Clic sur la corbeille d'une ligne du panier.
// Demande confirmation via la modal générique, puis supprime côté serveur et recharge.
$(document).on('click', '.btn-supprimer-panier', function() {
    let btn = $(this);
    let idProduit = btn.data('id');

    confirmerAction('Voulez-vous vraiment retirer cet article de votre panier ?', function() {
        btn.prop('disabled', true);

        $.ajax({
            url: 'admin/src/php/ajax/ajax_delete_panier.php',
            type: 'POST',
            dataType: 'json',
            data: { id_produit: idProduit },
            success: function(retour) {
                if (retour.success) {
                    location.reload();
                } else {
                    afficherToast('<i class="bi bi-exclamation-circle me-1"></i>' + retour.message, 'danger');
                    btn.prop('disabled', false);
                }
            },
            error: function() {
                afficherToast('<i class="bi bi-exclamation-circle me-1"></i>Erreur de communication avec le serveur.', 'danger');
                btn.prop('disabled', false);
            }
        });
    }, 'Retirer');
});

$(document).ready(function() {
    // Ajout au panier : boutons "Acheter" (page détail) ET boutons + des grilles
    // de catalogue (carrousel, vitrine, recherche). Quantité fixée à 1 par défaut ;
    // les boutons +/- du panier permettent ensuite d'ajuster.
    // Affiche un spinner sur le bouton pendant la requête, puis un toast.
    $(document).on('click', '.theme-btn-acheter, .btn-panier-pink-maquette', function(e) {
        e.preventDefault(); // Empêche la navigation si le bouton est un <a>

        let btn = $(this);
        let idProduit = btn.data('id');
        let quantite = 1;

        // Sécurité : si data-id manque, on n'envoie rien (problème de template)
        if (!idProduit) {
            console.error("ID produit introuvable sur le bouton cliqué.");
            return;
        }

        // Empêche les clics multiples pendant la requête AJAX
        btn.prop('disabled', true);
        let contenuOriginal = btn.html();
        btn.html('<i class="spinner-border spinner-border-sm"></i>');

        $.ajax({
            url: 'admin/src/php/ajax/ajax_ajout_panier.php',
            type: 'POST',
            dataType: 'json',
            data: {
                id_produit: idProduit,
                quantite: quantite
            },
            success: function(retour) {
                if (retour.success) {
                    afficherToast('<i class="bi bi-cart-check me-1"></i>' + retour.message, 'success');
                } else {
                    afficherToast('<i class="bi bi-exclamation-circle me-1"></i>' + retour.message, 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX panier :", status, error);
                afficherToast('<i class="bi bi-exclamation-circle me-1"></i>Erreur de communication avec le serveur.', 'danger');
            },
            complete: function() {
                btn.prop('disabled', false);
                btn.html(contenuOriginal);
            }
        });
    });
});
