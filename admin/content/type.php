<h2>Gestion des catégories de produits</h2>
<?php

$types = new TypeDAO($cnx);
$liste = $types->getAllTypes();
?>
<pre>
    <?php
    var_dump($liste); //var_dump affiche le contenu d'un objet
    ?>
</pre>
