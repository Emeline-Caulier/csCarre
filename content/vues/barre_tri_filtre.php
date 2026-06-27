<?php
// Requiert : $urlBase (URL de la page sans &tri ni &filtre), $tri (string), $filtrePromo (bool)
// Optionnel : $filtreNouveau (bool). Si défini, affiche le bouton Nouveautés.
$paramTri = $tri !== '' ? '&tri=' . $tri   : '';
$paramFiltre = $filtrePromo ? '&filtre=promo' : '';
$hBase = htmlspecialchars($urlBase);
?>
<div class="d-flex justify-content-end gap-2 mb-3">

    <div class="dropdown">
        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-arrow-down-up me-1"></i><?php
            if ($tri === 'prix_asc')       echo 'Prix croissant';
            elseif ($tri === 'prix_desc')  echo 'Prix décroissant';
            else                           echo 'Trier par prix';
            ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item <?= $tri === '' ? 'active' : '' ?>"
                   href="<?= $hBase . $paramFiltre ?>">
                    Sans tri
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= $tri === 'prix_asc' ? 'active' : '' ?>"
                   href="<?= $hBase . '&tri=prix_asc' . $paramFiltre ?>">
                    <i class="bi bi-sort-numeric-up me-1"></i>Prix croissant
                </a>
            </li>
            <li>
                <a class="dropdown-item <?= $tri === 'prix_desc' ? 'active' : '' ?>"
                   href="<?= $hBase . '&tri=prix_desc' . $paramFiltre ?>">
                    <i class="bi bi-sort-numeric-down-alt me-1"></i>Prix décroissant
                </a>
            </li>
        </ul>
    </div>

    <a href="<?= $hBase . $paramTri . ($filtrePromo ? '' : '&filtre=promo') ?>"
       class="btn btn-outline-secondary btn-sm <?= $filtrePromo ? 'active' : '' ?>">
        <i class="bi bi-tag me-1"></i>Promotions
    </a>

    <?php if (isset($filtreNouveau)): ?>
    <a href="<?= $hBase . $paramTri . ($filtreNouveau ? '' : '&filtre=nouveau') ?>"
       class="btn btn-outline-secondary btn-sm <?= $filtreNouveau ? 'active' : '' ?>">
        <i class="bi bi-stars me-1"></i>Nouveautés
    </a>
    <?php endif; ?>

</div>
