// Modal détail message : pré-remplit avec les data-attributes du bouton cliqué.

document.querySelectorAll('.btn-voir-message').forEach(function (btn) {
    btn.addEventListener('click', function () {
        // textContent (et pas innerHTML) pour éviter toute injection XSS
        document.getElementById('modal-nom-email').textContent =
            this.dataset.nom + ' <' + this.dataset.email + '>';
        document.getElementById('modal-date').textContent = this.dataset.date;
        document.getElementById('modal-sujet').textContent = this.dataset.sujet;
        document.getElementById('modal-contenu').textContent = this.dataset.contenu;

        // Affiche la pièce jointe si présente, sinon on masque
        var photoWrapper = document.getElementById('modal-photo-wrapper');
        var photoImg = document.getElementById('modal-photo');
        if (this.dataset.photo) {
            photoImg.src = this.dataset.photo;
            photoWrapper.classList.remove('d-none');
        } else {
            photoWrapper.classList.add('d-none');
            photoImg.src = '';
        }
    });
});
