// Recherche : dropdown live (AJAX). Utilisé par l'admin (clients/produits/commandes)
// et le site client (produits seuls).

function initSearch(config) {
    const input    = document.getElementById(config.inputId);
    const dropdown = document.getElementById(config.dropdownId);

    if (!input || !dropdown) return;

    const ICONS = {
        client:   'bi-person',
        produit:  'bi-gem',
        commande: 'bi-bag',
    };
    const COLORS = {
        client:   '#A66EAF',
        produit:  '#00b4a6',
        commande: '#e8559a',
    };
    const LABELS = {
        client:   'Client',
        produit:  'Produit',
        commande: 'Commande',
    };

    let timer       = null;
    let activeIndex = -1;

    // Ouvre / ferme le dropdown
    function showDropdown(items) {
        dropdown.innerHTML = '';
        activeIndex = -1;

        if (!items.length) {
            closeDropdown();
            return;
        }

        items.forEach(function (item, i) {
            const a = document.createElement('a');
            a.href = item.url;
            a.className = 'search-dropdown-item';
            a.dataset.index = i;
            a.innerHTML =
                '<span class="search-drop-icon" style="background:' + (COLORS[item.type] || '#ccc') + '">' +
                    '<i class="bi ' + (ICONS[item.type] || 'bi-search') + '"></i>' +
                '</span>' +
                '<span class="search-drop-text">' +
                    '<span class="search-drop-label">' + escHtml(item.label) + '</span>' +
                    (item.sub ? '<span class="search-drop-sub">' + escHtml(item.sub) + '</span>' : '') +
                '</span>' +
                '<span class="search-drop-type" style="color:' + (COLORS[item.type] || '#999') + '">' +
                    (LABELS[item.type] || item.type) +
                '</span>';

            a.addEventListener('mousedown', function (e) {
                e.preventDefault();
            });

            dropdown.appendChild(a);
        });

        if (config.allResultsUrl) {
            const footer = document.createElement('div');
            footer.className = 'search-dropdown-footer';
            const allLink = document.createElement('a');
            allLink.href = config.allResultsUrl(input.value.trim());
            allLink.className = 'search-dropdown-all';
            allLink.innerHTML = '<i class="bi bi-search me-1"></i>Voir tous les résultats';
            allLink.addEventListener('mousedown', function (e) { e.preventDefault(); });
            footer.appendChild(allLink);
            dropdown.appendChild(footer);
        }

        dropdown.hidden = false;
    }

    function closeDropdown() {
        dropdown.hidden = true;
        activeIndex = -1;
    }

    // Navigation clavier
    input.addEventListener('keydown', function (e) {
        const items = dropdown.querySelectorAll('.search-dropdown-item');
        if (dropdown.hidden || !items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = Math.min(activeIndex + 1, items.length - 1);
            updateActive(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = Math.max(activeIndex - 1, -1);
            updateActive(items);
        } else if (e.key === 'Enter' && activeIndex >= 0) {
            e.preventDefault();
            items[activeIndex].click();
        } else if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    function updateActive(items) {
        items.forEach(function (el, i) {
            el.classList.toggle('active', i === activeIndex);
        });
    }

    // Saisie utilisateur, avec debounce 280 ms
    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = input.value.trim();

        if (q.length < 2) {
            closeDropdown();
            return;
        }

        timer = setTimeout(function () {
            fetch(config.endpoint + '?q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(showDropdown)
                .catch(function () { closeDropdown(); });
        }, 280);
    });

    // Fermeture au clic en dehors du champ et du dropdown
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    input.addEventListener('focus', function () {
        if (input.value.trim().length >= 2 && !dropdown.hidden) return;
        if (input.value.trim().length >= 2) input.dispatchEvent(new Event('input'));
    });

    // Échappement HTML (anti-XSS sur les libellés affichés)
    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
}

// Recherche admin : clients + produits + commandes
initSearch({
    inputId:       'admin-search-input',
    dropdownId:    'admin-search-dropdown',
    endpoint:      'src/php/ajax/search.php',
    allResultsUrl: function (q) { return 'index_.php?page=recherche.php&q=' + encodeURIComponent(q); },
});

// Recherche client : produits seulement
initSearch({
    inputId:       'client-search-input',
    dropdownId:    'client-search-dropdown',
    endpoint:      'admin/src/php/ajax/search_client.php',
    allResultsUrl: function (q) { return 'index_.php?page=recherche.php&q=' + encodeURIComponent(q); },
});
