// Auto-complétion du formulaire client sur compte.php.
// Quand l'utilisateur quitte le champ "mot de passe", on vérifie côté serveur
// si le compte existe et on bascule l'UI entre mode "Connexion" et mode "Inscription".
// En mode connexion, on relâche le pattern HTML5 du mot de passe pour ne pas
// bloquer un client dont l'ancien mdp ne respecte pas le format actuel.

$(document).ready(function () {

    var patternMdp = $('#mot_de_passe').attr('pattern');
    var titleMdp = $('#mot_de_passe').attr('title');

    function passerEnModeConnexion(donneesClient) {
        // Pattern relâché pour qu'un ancien mdp non conforme au format actuel
        // n'empêche pas la connexion
        $('#mot_de_passe').removeAttr('pattern').removeAttr('title');
        if (donneesClient) {
            $('#nom_client').val(donneesClient.nom_client);
            $('#prenom_client').val(donneesClient.prenom_client);
            $('#id_client_cache').val(donneesClient.id_client);
            $('#rue').val(donneesClient.rue);
            $('#numero').val(donneesClient.numero);
            $('#code_postal').val(donneesClient.code_postal);
            $('#ville').val(donneesClient.ville);
            $('#pays').val(donneesClient.pays);
        }
        $('#submit_client').text("Me connecter");
    }

    function passerEnModeInscription() {
        if (patternMdp) $('#mot_de_passe').attr('pattern', patternMdp);
        if (titleMdp) $('#mot_de_passe').attr('title', titleMdp);
        $('#submit_client').text("S'inscrire");
    }

    // Déclenché à la perte de focus sur le champ mot de passe
    $('#mot_de_passe').blur(function () {
        var email = $('#email_client').val();
        var motDePasse = $(this).val();

        if (email === '' || motDePasse === '') {
            return;
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: { email: email, mot_de_passe: motDePasse },
            url: 'admin/src/php/ajax/ajaxCheckClient.php',
            success: function (data) {
                if (data && data.id_client) {
                    // Identifiants valides : on pré-remplit et on passe en mode connexion
                    passerEnModeConnexion(data);
                } else if (data && data.exists) {
                    // Email connu mais mdp incorrect : mode connexion sans pré-remplir
                    passerEnModeConnexion(null);
                } else {
                    // Email inconnu : mode inscription
                    passerEnModeInscription();
                }
            }
        });
    });
});
