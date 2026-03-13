<h2>Gestion des catégories de produits</h2>
<?php

$types = new CategorieDAO($cnx);
$liste = $types->getAllCategories();
?>
<pre>
    <?php
    var_dump($liste); //var_dump affiche le contenu d'un objet
    ?>
</pre>
