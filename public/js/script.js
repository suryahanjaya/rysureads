(function () {
    function showMessage(message) {
        var existing = document.querySelector('.custom-toast');
        if (existing) existing.remove();

        var toast = document.createElement('div');
        toast.className = 'custom-toast';
        toast.innerHTML = '<div class="toast-content">' + message + '</div>';
        document.body.appendChild(toast);

        window.setTimeout(function () {
            toast.classList.add('show');
        }, 10);

        window.setTimeout(function () {
            toast.classList.remove('show');
            window.setTimeout(function () {
                toast.remove();
            }, 300);
        }, 2800);
    }

    function renderEmpty(stateText) {
        return '<div class="empty-state">' + stateText + '</div>';
    }

    function setupAjaxSearch() {
        var input = document.querySelector('[data-ajax-search]');
        var results = document.getElementById('searchResults');
        if (!input || !results) return;

        var debounceTimer = null;

        function runSearch() {
            var keyword = input.value.trim();
            results.innerHTML = '<div class="loading-state">Searching...</div>';

            fetch('/search-items?q=' + encodeURIComponent(keyword))
                .then(function (response) { return response.text(); })
                .then(function (html) {
                    results.innerHTML = html || renderEmpty('No matching items found.');
                })
                .catch(function () {
                    results.innerHTML = renderEmpty('Search temporarily unavailable.');
                });
        }

        input.addEventListener('input', function () {
            window.clearTimeout(debounceTimer);
            debounceTimer = window.setTimeout(runSearch, 250);
        });

        runSearch();
    }

    function setupCatalogFilters() {
        var categorySelect = document.querySelector('[data-category-filter]');
        var sortSelect = document.querySelector('[data-sort-filter]');
        if (!categorySelect && !sortSelect) return;

        function go() {
            var category = categorySelect ? categorySelect.value : '';
            var sort = sortSelect ? sortSelect.value : 'newest';
            var target = category ? '/products/category/' + encodeURIComponent(category) : '/products';
            if (sort && sort !== 'newest') {
                target += '?sort=' + encodeURIComponent(sort);
            }
            window.location.href = target;
        }

        if (categorySelect) {
            categorySelect.addEventListener('change', go);
        }
        if (sortSelect) {
            sortSelect.addEventListener('change', go);
        }
    }

    function setupHeaderPanels() {
        var toggles = Array.prototype.slice.call(document.querySelectorAll('[data-nav-toggle]'));
        var panels = Array.prototype.slice.call(document.querySelectorAll('[data-nav-panel]'));
        if (!toggles.length || !panels.length) return;

        function closeAll() {
            toggles.forEach(function (toggle) {
                toggle.classList.remove('is-active');
                toggle.setAttribute('aria-expanded', 'false');
            });
            panels.forEach(function (panel) {
                panel.classList.remove('is-open');
            });
        }

        function openPanel(panelId) {
            closeAll();
            var toggle = document.querySelector('[data-nav-toggle="' + panelId + '"]');
            var panel = document.querySelector('[data-nav-panel="' + panelId + '"]');
            if (!toggle || !panel) return;
            toggle.classList.add('is-active');
            toggle.setAttribute('aria-expanded', 'true');
            panel.classList.add('is-open');
        }

        toggles.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                var panelId = toggle.getAttribute('data-nav-toggle');
                var expanded = toggle.getAttribute('aria-expanded') === 'true';
                if (expanded) {
                    closeAll();
                    return;
                }
                openPanel(panelId);
            });
        });

        document.addEventListener('click', function (event) {
            var target = event.target;
            var clickedInside = false;
            toggles.forEach(function (toggle) {
                if (toggle.contains(target)) clickedInside = true;
            });
            panels.forEach(function (panel) {
                if (panel.contains(target)) clickedInside = true;
            });
            if (!clickedInside) {
                closeAll();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeAll();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupAjaxSearch();
        setupCatalogFilters();
        setupHeaderPanels();
    });

    window.showMessage = showMessage;
})();
