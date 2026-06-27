// Bascule vue cartes / vue tableau éditable (grille produits)

document.addEventListener('DOMContentLoaded', function () {

    var btnCartes  = document.getElementById('btn-vue-cartes');
    var btnTableau = document.getElementById('btn-vue-tableau');
    if (!btnCartes || !btnTableau) return;

    var vueCartes  = document.getElementById('vue-cartes');
    var vueTableau = document.getElementById('vue-tableau');

    btnTableau.addEventListener('click', function () {
        vueCartes.classList.add('d-none');
        vueTableau.classList.remove('d-none');
        btnTableau.classList.add('active');
        btnCartes.classList.remove('active');
    });

    btnCartes.addEventListener('click', function () {
        vueTableau.classList.add('d-none');
        vueCartes.classList.remove('d-none');
        btnCartes.classList.add('active');
        btnTableau.classList.remove('active');
    });

});

// Gestion des photos produit (upload + réordonnancement)

// Confirmation de suppression (catégorie, produit via lien)
$(document).on('click', '.btn-confirmer-suppression', function (e) {
    e.preventDefault();
    var href = this.href;
    confirmerAction('Supprimer définitivement ?', function () {
        window.location.href = href;
    }, 'Supprimer');
});

// Confirmation de suppression de photo
$(document).on('click', '.btn-confirmer-suppression-photo', function (e) {
    e.preventDefault();
    var href = this.href;
    confirmerAction('Supprimer cette photo ?', function () {
        window.location.href = href;
    }, 'Supprimer');
});

document.addEventListener('DOMContentLoaded', function () {

    // --- Helpers badge "★ Principale" ---

    // Ajoute le badge sur un élément d'aperçu
    function ajouterBadgePrincipale(el) {
        var badge = document.createElement('span');
        badge.className = 'badge bg-primary position-absolute bottom-0 start-0 badge-principale';
        badge.textContent = '★ Principale';
        el.appendChild(badge);
    }

    // Y a-t-il déjà des photos existantes en BDD pour ce produit ?
    // Si oui, les nouvelles photos vont en fin de liste (ordre = max+1) et ne
    // peuvent donc PAS devenir principale → on n'affiche pas de badge trompeur.
    var aDejaPhotosExistantes = !!document.querySelector('#sortable-images .apercu-sortable');

    // Retire tous les badges existants et remet le badge sur le premier élément de la zone.
    // Le badge n'est appliqué que si aucune photo existante n'est présente.
    function actualiserBadgePrincipale(zone) {
        zone.querySelectorAll('.badge-principale').forEach(function (b) { b.remove(); });
        if (aDejaPhotosExistantes) return;
        var premier = zone.querySelector('.apercu-photo');
        if (premier) ajouterBadgePrincipale(premier);
    }

    // --- Ajout de nouvelles photos (formulaire création / édition) ---

    var btnAjouter = document.getElementById('btn-ajouter-photo');
    var zonePhotos = document.getElementById('zone-photos');
    var cacheInputs = document.getElementById('inputs-photos-cache');
    var compteur = 0; // Sert à générer des IDs uniques pour chaque input fichier

    if (btnAjouter && zonePhotos && cacheInputs) {

        // Rend la zone de nouvelles photos réordonnables par glisser-déposer
        new Sortable(zonePhotos, {
            animation: 150,
            filter: '.btn-suppr-photo', // Le bouton supprimer ne déclenche pas le drag
            onEnd: function () {
                // Après le réordonnancement visuel, on réordonne les inputs cachés dans le même ordre
                // pour que PHP reçoive les fichiers dans le bon ordre (= ordre d'affichage)
                Array.from(zonePhotos.querySelectorAll('.apercu-photo')).forEach(function (div) {
                    cacheInputs.appendChild(document.getElementById(div.dataset.inputId));
                });
                actualiserBadgePrincipale(zonePhotos);
            }
        });

        // Chaque clic crée un nouvel <input type="file"> caché, l'ouvre immédiatement,
        // puis affiche un aperçu en cas de sélection
        btnAjouter.addEventListener('click', function () {
            var inputId = 'photo-input-' + (compteur++);

            // Création de l'input caché (non affiché, mais soumis avec le formulaire)
            var input = document.createElement('input');
            input.type = 'file';
            input.name = 'photos[]';
            input.id = inputId;
            input.accept = 'image/jpeg,image/png,image/webp,image/gif';
            cacheInputs.appendChild(input);

            input.addEventListener('change', function () {
                if (!this.files[0]) {
                    // L'utilisateur a annulé la sélection : on supprime l'input inutile
                    this.remove();
                    return;
                }

                var file = this.files[0];
                var capturedId = inputId; // Capture dans la closure pour le bouton supprimer
                // URL.createObjectURL() est instantané, contrairement à FileReader.readAsDataURL()
                var objectUrl = URL.createObjectURL(file);

                // Conteneur de l'aperçu (draggable via SortableJS)
                var div = document.createElement('div');
                div.className = 'apercu-photo position-relative apercu-sortable';
                div.dataset.inputId = capturedId;

                // Vignette de prévisualisation
                var img = document.createElement('img');
                img.src = objectUrl;
                img.className = 'img-apercu img-apercu-nodrag';
                img.onload = function () { URL.revokeObjectURL(objectUrl); }; // Libère la mémoire
                div.appendChild(img);

                // Bouton de suppression de cette photo
                var btnSuppr = document.createElement('button');
                btnSuppr.type = 'button';
                btnSuppr.className = 'btn btn-danger btn-suppr-photo btn-suppr-photo-small position-absolute top-0 end-0';
                btnSuppr.textContent = '×';
                btnSuppr.addEventListener('click', function () {
                    var inp = document.getElementById(capturedId);
                    if (inp) inp.remove(); // Supprime aussi l'input caché
                    div.remove();
                    actualiserBadgePrincipale(zonePhotos);
                });
                div.appendChild(btnSuppr);

                zonePhotos.appendChild(div);
                actualiserBadgePrincipale(zonePhotos);
            });

            input.click(); // Ouvre le sélecteur de fichier
        });
    }

    // --- Réordonnancement des photos existantes (formulaire d'édition uniquement) ---
    // L'ordre est persisté immédiatement via AJAX après chaque drag

    var sortableExistant = document.getElementById('sortable-images');
    if (sortableExistant) {
        new Sortable(sortableExistant, {
            animation: 150,
            filter: '.btn-suppr-img',
            onEnd: function () {
                // Met à jour le badge sur la nouvelle première image
                sortableExistant.querySelectorAll('.badge-principale').forEach(function (b) { b.remove(); });
                var premier = sortableExistant.querySelector('[data-id]');
                if (premier) ajouterBadgePrincipale(premier);

                // Envoie le nouvel ordre au serveur (tableau d'IDs dans l'ordre visuel)
                var ids = Array.from(sortableExistant.querySelectorAll('[data-id]'))
                    .map(function (el) { return el.dataset.id; });
                fetch('src/php/ajax/updateImageOrder.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ids: ids})
                });
            }
        });
    }

});

// Modals catégories et produits.
// Pré-remplissage via l'événement Bootstrap show.bs.modal (relatedTarget = bouton déclencheur).

$(document).ready(function () {

    // Galerie admin : change la photo principale au clic sur une miniature
    $(document).on('click', '.galerie-img', function () {
        $('#photoPrincipale').attr('src', $(this).attr('src'));
        $('.galerie-img').removeClass('active');
        $(this).addClass('active');
    });

    // Modal modifier catégorie
    $('#modalModifierCategorie').on('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        $('#input-edit-cat-id').val(btn.dataset.id);
        $('#input-edit-cat-nom').val(btn.dataset.nom);
    });

    // Modal supprimer catégorie (vide)
    $('#modalSupprimerCategorie').on('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        $('#modal-cat-nom').text(btn.dataset.nom);
        $('#link-cat-delete').attr('href', 'index_.php?page=produit.php&cat_delete=' + btn.dataset.id);
    });

    // Modal catégorie non supprimable (contient des produits)
    $('#modalCategorieNonSupprimable').on('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        var nb = parseInt(btn.dataset.nb);
        $('#modal-non-supprimable-message').text(
            'La catégorie "' + btn.dataset.nom + '" contient ' + nb +
            ' produit' + (nb > 1 ? 's' : '') +
            '. Veuillez d\'abord supprimer ou déplacer ces produits avant de pouvoir supprimer cette catégorie.'
        );
    });

    // Modal supprimer produit
    $('#modalSupprimerProduit').on('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        $('#modal-produit-nom').text(btn.dataset.nom);
        $('#link-produit-delete').attr('href',
            'index_.php?page=produit.php&delete=' + btn.dataset.id + '&id_cat=' + btn.dataset.cat
        );
    });

});
