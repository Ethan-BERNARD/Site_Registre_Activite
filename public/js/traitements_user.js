/**
 * GESTION DU TABLEAU DES TRAITEMENTS - USER
 * - Recherche AJAX avec debounce
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ========== RECHERCHE AJAX ==========
    const searchInput = document.getElementById('searchInput');
    let debounceTimer;

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(debounceTimer);
            let q = this.value;

            debounceTimer = setTimeout(() => {
                fetch(baseUrl + "/user/searchAjax?q=" + encodeURIComponent(q) + "&_=" + Date.now())
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('tbodyTraitements').innerHTML = html;
                    });
            }, 300);
        });
    }
});
