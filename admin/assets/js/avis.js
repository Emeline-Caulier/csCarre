// Ouvrir la photo de l'avis dans un nouvel onglet au clic
$(document).on('click', '.avis-photo-ouvrir', function () {
    window.open($(this).attr('src'));
});
