// Comportements UI partagés client et admin :
// lignes de tableau cliquables, galerie produit, menu hamburger.

$(document).ready(function () {

    // Lignes de tableau cliquables : navigation au clic + accessibilité clavier
    // (Entrée ou Espace) pour les utilisateurs au clavier ou lecteur d'écran
    $(document).on('click', '.tr-clickable', function () {
        var href = $(this).data('href');
        if (href) { location.href = href; }
    });
    $(document).on('keydown', '.tr-clickable', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            var href = $(this).data('href');
            if (href) { location.href = href; }
        }
    });

    // Galerie produit : remplace la grande image par celle de la miniature cliquée
    $(document).on('click', '.galerie-img', function () {
        $('#photo-principale').attr('src', $(this).attr('src'));
        $('.galerie-img').removeClass('active');
        $(this).addClass('active'); // met en évidence la miniature active
    });

    // Menu hamburger (mobile) : alterne ouvert/fermé et change l'icône
    $('#navBurger').on('click', function () {
        var $nav = $('.nav-categories');
        var isOpen = $nav.hasClass('nav-open');
        $nav.toggleClass('nav-open');
        $(this).attr('aria-expanded', !isOpen); // accessibilité ARIA
        $(this).find('i').toggleClass('bi-list bi-x-lg');
    });

    // Ferme automatiquement le menu mobile dès qu'un lien de catégorie est cliqué
    $('.nav-categories a').on('click', function () {
        $('.nav-categories').removeClass('nav-open');
        $('#navBurger').attr('aria-expanded', false)
                      .find('i').removeClass('bi-x-lg').addClass('bi-list');
    });
});
