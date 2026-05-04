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
        var drawer = document.querySelector('[data-nav-drawer]');
        var backdrop = document.querySelector('[data-nav-backdrop]');
        if (!toggles.length || !panels.length || !drawer || !backdrop) return;

        function closeAll() {
            toggles.forEach(function (toggle) {
                toggle.classList.remove('is-active');
                toggle.setAttribute('aria-expanded', 'false');
            });
            panels.forEach(function (panel) {
                panel.classList.remove('is-open');
            });
            drawer.hidden = true;
            backdrop.hidden = true;
            document.body.classList.remove('nav-is-open');
        }

        function openPanel(panelId) {
            closeAll();
            var toggle = document.querySelector('[data-nav-toggle="' + panelId + '"]');
            var panel = document.querySelector('[data-nav-panel="' + panelId + '"]');
            if (!toggle || !panel) return;
            toggle.classList.add('is-active');
            toggle.setAttribute('aria-expanded', 'true');
            panel.classList.add('is-open');
            drawer.hidden = false;
            backdrop.hidden = false;
            document.body.classList.add('nav-is-open');
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
            if (drawer.contains(target)) clickedInside = true;
            if (backdrop.contains(target)) {
                closeAll();
                return;
            }
            if (!clickedInside) {
                closeAll();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeAll();
            }
        });

        drawer.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                closeAll();
            });
        });

        drawer.querySelector('[data-nav-close]').addEventListener('click', closeAll);
    }

    function setupThemeAndLanguage() {
        var themeToggle = document.querySelector('[data-theme-toggle]');
        var langToggle = document.querySelector('[data-lang-toggle]');
        var root = document.documentElement;
        var theme = window.localStorage.getItem('rysureads-theme') || 'light';
        var lang = window.localStorage.getItem('rysureads-lang') || 'en';
        var translations = {
            en: {
                'header.login': 'Login',
                'header.register': 'Register',
                'header.logout': 'Logout',
                'header.theme': 'Light',
                'header.lang': '中文',
                'nav.home': 'Home',
                'nav.products': 'Products',
                'nav.search': 'Search',
                'nav.contact': 'Contact',
                'drawer.home': 'Home',
                'drawer.brand': 'RysuReads',
                'drawer.overview': 'Overview',
                'drawer.featured': 'Featured selections',
                'drawer.searchTitles': 'Search titles',
                'drawer.products': 'Products',
                'drawer.browseCatalog': 'Browse the catalog',
                'drawer.allProducts': 'All products',
                'drawer.addItem': 'Add item',
                'drawer.search': 'Search',
                'drawer.topRated': 'Top rated titles',
                'drawer.contact': 'Contact',
                'drawer.contactDetails': 'Contact details',
                'home.eyebrow': 'Online bookstore',
                'home.title': 'Welcome to RysuReads.',
                'home.copy': 'Browse books, categories, and store locations in a clean, timeless online bookstore experience.',
                'home.featuredLabel': 'Featured paths',
                'home.featuredTitle': 'Books for every reading mood',
                'home.featuredCopy': 'Explore categories, item details, and store availability in one place.',
                'home.readLabel': 'Read with ease',
                'home.readCopy': 'Balanced spacing and classic typography keep the catalog easy to scan on any screen.',
                'home.searchHeading': 'Search the catalog',
                'home.searchCopy': 'Search the online bookstore by title, category, or keyword.',
                'home.browse': 'Browse the catalog',
                'home.searchBtn': 'Search titles',
                'home.openSearch': 'Open search',
                'home.featuredHeading': 'Featured selections',
                'search.title': 'Search titles',
                'search.copy': 'Type a title, category, or keyword and the catalog updates as you search.',
                'search.placeholder': 'Type a product name, category, or keyword',
                'search.open': 'Open search',
                'products.title': 'Browse the full catalog',
                'products.copy': 'Sort by name, price, or rating, then open any item to view details and store availability.',
                'products.add': 'Add item',
                'products.category': 'Category',
                'products.sort': 'Sort by',
                'contact.eyebrow': 'Get in touch',
                'contact.title': 'Contact the reading room',
                'contact.copy': 'Use the details below for support, location questions, or account help.',
                'contact.name': 'Jay',
                'contact.desc': 'Online bookstore contact and community links.',
                'contact.email': 'surya.23007@mhs.unesa.ac.id',
                'contact.phone': '+62 81263436187',
                'contact.linkedin': 'LinkedIn',
                'contact.instagram': 'Instagram',
                'footer.copy': 'Copyright 2026',
                'footer.products': 'Products',
                'footer.search': 'Search',
                'footer.sitemap': 'Sitemap'
            },
            zh: {
                'header.login': '登录',
                'header.register': '注册',
                'header.logout': '退出',
                'header.theme': '浅色',
                'header.lang': 'EN',
                'nav.home': '首页',
                'nav.products': '商品',
                'nav.search': '搜索',
                'nav.contact': '联系',
                'drawer.home': '首页',
                'drawer.brand': 'RysuReads',
                'drawer.overview': '概览',
                'drawer.featured': '精选内容',
                'drawer.searchTitles': '搜索书名',
                'drawer.products': '商品',
                'drawer.browseCatalog': '浏览目录',
                'drawer.allProducts': '全部商品',
                'drawer.addItem': '添加商品',
                'drawer.search': '搜索',
                'drawer.topRated': '高评分书目',
                'drawer.contact': '联系',
                'drawer.contactDetails': '联系信息',
                'home.eyebrow': '在线书店',
                'home.title': '欢迎来到 RysuReads。',
                'home.copy': '在简洁、经典的在线书店体验中浏览书籍、分类和门店信息。',
                'home.featuredLabel': '精选路径',
                'home.featuredTitle': '适合每种阅读心情的书籍',
                'home.featuredCopy': '在一个地方查看分类、详情和门店可用性。',
                'home.readLabel': '轻松阅读',
                'home.readCopy': '留白和经典字体让目录在任何设备上都易于浏览。',
                'home.searchHeading': '搜索目录',
                'home.searchCopy': '按书名、分类或关键词搜索在线书店。',
                'home.browse': '浏览目录',
                'home.searchBtn': '搜索书目',
                'home.openSearch': '打开搜索',
                'home.featuredHeading': '精选内容',
                'search.title': '搜索书目',
                'search.copy': '输入书名、分类或关键词，目录会实时更新。',
                'search.placeholder': '输入商品名、分类或关键词',
                'search.open': '打开搜索',
                'products.title': '浏览完整目录',
                'products.copy': '可按名称、价格或评分排序，然后查看详情和门店库存。',
                'products.add': '添加商品',
                'products.category': '分类',
                'products.sort': '排序',
                'contact.eyebrow': '联系我们',
                'contact.title': '联系阅读空间',
                'contact.copy': '通过下面的信息获取支持、位置或账号帮助。',
                'contact.name': 'Jay',
                'contact.desc': '在线书店联系与社交链接。',
                'contact.email': 'surya.23007@mhs.unesa.ac.id',
                'contact.phone': '+62 81263436187',
                'contact.linkedin': '领英',
                'contact.instagram': 'Instagram',
                'footer.copy': '版权所有 2026',
                'footer.products': '商品',
                'footer.search': '搜索',
                'footer.sitemap': '站点地图'
            }
        };

        function applyTheme(nextTheme) {
            theme = nextTheme === 'dark' ? 'dark' : 'light';
            root.setAttribute('data-theme', theme);
            window.localStorage.setItem('rysureads-theme', theme);
            if (themeToggle) {
                themeToggle.textContent = lang === 'zh'
                    ? (theme === 'dark' ? '浅色' : '深色')
                    : (theme === 'dark' ? 'Light' : 'Dark');
            }
        }

        function applyLanguage(nextLang) {
            lang = nextLang === 'zh' ? 'zh' : 'en';
            root.setAttribute('data-lang', lang);
            root.lang = lang;
            window.localStorage.setItem('rysureads-lang', lang);
            if (langToggle) {
                langToggle.textContent = lang === 'zh' ? 'EN' : '中文';
            }
            if (themeToggle) {
                themeToggle.textContent = lang === 'zh'
                    ? (theme === 'dark' ? '浅色' : '深色')
                    : (theme === 'dark' ? 'Light' : 'Dark');
            }

            Array.prototype.slice.call(document.querySelectorAll('[data-i18n]')).forEach(function (node) {
                var key = node.getAttribute('data-i18n');
                var value = translations[lang][key];
                if (value) {
                    node.textContent = value;
                }
            });

            Array.prototype.slice.call(document.querySelectorAll('[data-i18n-placeholder]')).forEach(function (node) {
                var key2 = node.getAttribute('data-i18n-placeholder');
                var value2 = translations[lang][key2];
                if (value2) {
                    node.setAttribute('placeholder', value2);
                }
            });
        }

        applyTheme(theme);
        applyLanguage(lang);

        if (themeToggle) {
            themeToggle.addEventListener('click', function () {
                applyTheme(theme === 'dark' ? 'light' : 'dark');
            });
        }

        if (langToggle) {
            langToggle.addEventListener('click', function () {
                applyLanguage(lang === 'zh' ? 'en' : 'zh');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupAjaxSearch();
        setupCatalogFilters();
        setupHeaderPanels();
        setupThemeAndLanguage();
    });

    window.showMessage = showMessage;
})();
