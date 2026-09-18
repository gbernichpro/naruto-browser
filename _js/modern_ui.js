(function () {
    'use strict';

    function createToastRegion() {
        var region = document.getElementById('game-toast-region');
        if (region) return region;

        region = document.createElement('div');
        region.id = 'game-toast-region';
        region.className = 'game-toast-region';
        region.setAttribute('aria-live', 'polite');
        region.setAttribute('aria-atomic', 'true');
        document.body.appendChild(region);
        return region;
    }

    window.gameToast = function (message, type) {
        if (!message) return;

        var toast = document.createElement('div');
        toast.className = 'game-toast game-toast--' + (type || 'info');
        toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

        var text = document.createElement('span');
        text.textContent = message;
        toast.appendChild(text);

        var close = document.createElement('button');
        close.type = 'button';
        close.className = 'game-toast__close';
        close.setAttribute('aria-label', 'Fechar aviso');
        close.textContent = '×';
        close.addEventListener('click', function () { toast.remove(); });
        toast.appendChild(close);

        createToastRegion().appendChild(toast);
        requestAnimationFrame(function () { toast.classList.add('is-visible'); });
        window.setTimeout(function () {
            toast.classList.remove('is-visible');
            window.setTimeout(function () { toast.remove(); }, 250);
        }, type === 'error' ? 6500 : 4200);
    };

    function setupSidebar() {
        var toggle = document.querySelector('.mobile-menu-toggle');
        var table = document.getElementById('game-sidebar-table');
        if (toggle && table) {
            toggle.addEventListener('click', function () {
                var open = table.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(open));
            });
        }

        var currentPage = new URLSearchParams(window.location.search).get('p') || 'home';
        document.querySelectorAll('.sidebar-link[href*="?p="]').forEach(function (link) {
            var page = new URL(link.href, window.location.href).searchParams.get('p');
            if (page === currentPage) link.classList.add('is-active');
        });

        document.querySelectorAll('.sidebar-title').forEach(function (title) {
            title.setAttribute('role', 'button');
            title.setAttribute('tabindex', '0');
            title.setAttribute('aria-expanded', 'true');

            function toggleSection() {
                var collapsed = title.classList.toggle('is-collapsed');
                title.setAttribute('aria-expanded', String(!collapsed));
                var sibling = title.nextElementSibling;
                while (sibling && sibling.classList.contains('sidebar-link-container')) {
                    sibling.hidden = collapsed;
                    sibling = sibling.nextElementSibling;
                }
            }

            title.addEventListener('click', toggleSection);
            title.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleSection();
                }
            });
        });
    }

    function setupLoadingFeedback() {
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function () {
                var submit = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submit) submit.classList.add('is-loading');
            });
        });
    }

    function setupContentReveal() {
        var cards = document.querySelectorAll('.modern-card, .box_top, .box_middle');
        if (!('IntersectionObserver' in window)) {
            cards.forEach(function (card) { card.classList.add('is-revealed'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });

        cards.forEach(function (card) { observer.observe(card); });
    }

    function setupLegacyDropdowns() {
        document.querySelectorAll('.menu > li > a').forEach(function (trigger) {
            var item = trigger.parentElement;
            var submenu = item ? item.querySelector(':scope > ul') : null;
            if (!submenu) return;

            trigger.setAttribute('aria-haspopup', 'true');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.addEventListener('click', function (event) {
                if (trigger.getAttribute('href') === '#') event.preventDefault();
                var open = !item.classList.contains('is-open');
                document.querySelectorAll('.menu > li.is-open').forEach(function (other) {
                    other.classList.remove('is-open');
                    var otherTrigger = other.querySelector(':scope > a');
                    if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
                });
                item.classList.toggle('is-open', open);
                trigger.setAttribute('aria-expanded', String(open));
            });
        });

        document.addEventListener('click', function (event) {
            if (event.target.closest('.menu')) return;
            document.querySelectorAll('.menu > li.is-open').forEach(function (item) {
                item.classList.remove('is-open');
                var trigger = item.querySelector(':scope > a');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
            });
        });
    }

    function optimizeImages() {
        document.querySelectorAll('img').forEach(function (image) {
            image.decoding = 'async';
            if (!image.closest('.modern-header') && !image.hasAttribute('loading')) {
                image.loading = 'lazy';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupSidebar();
        setupLoadingFeedback();
        setupContentReveal();
        setupLegacyDropdowns();
        optimizeImages();
    });
}());
