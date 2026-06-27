// Utilitaires globaux : toast Bootstrap éphémère + modal de confirmation
// générique (qui remplace tous les confirm() natifs du navigateur).
// Chargé sur l'ensemble du site (client et admin).

function afficherToast(message, type) {
    var couleur = type === 'success' ? 'bg-success' : 'bg-danger';
    var styleBase = 'z-index:1100;' + (type === 'success' ? 'background-color:#00b4a6 !important;' : '');
    var $toast = $(
        '<div class="toast align-items-center text-white ' + couleur + ' border-0 position-fixed bottom-0 end-0 m-3" role="alert" style="' + styleBase + '">' +
        '<div class="d-flex"><div class="toast-body">' + message + '</div>' +
        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
        '</div></div>'
    );
    $('body').append($toast);
    var toast = new bootstrap.Toast($toast[0], { delay: 3500 });
    toast.show();
    $toast.on('hidden.bs.toast', function () { $(this).remove(); });
}

// Callback global stocke pour le bouton "Confirmer" de la modal
var _confirmCallback = null;

function confirmerAction(message, callback, labelBouton) {
    _confirmCallback = callback;
    $('#modal-confirm-message').text(message);
    $('#btn-modal-confirm').text(labelBouton || 'Confirmer');
    new bootstrap.Modal(document.getElementById('modalConfirmation')).show();
}

$(document).ready(function () {
    $(document).on('click', '#btn-modal-confirm', function () {
        bootstrap.Modal.getInstance(document.getElementById('modalConfirmation')).hide();
        if (typeof _confirmCallback === 'function') {
            _confirmCallback();
            _confirmCallback = null;
        }
    });
});
