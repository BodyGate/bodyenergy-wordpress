(function () {
    'use strict';

    var config = window.BodyEnergyPlatinumHome || {};
    var containerSelector = '[data-element_type="container"], .e-con';
    var markerText = 'BODY ENERGY ASD · PALERMO';

    function normalize(value) {
        return String(value || '').replace(/\s+/g, ' ').trim();
    }

    function closestContainer(element) {
        return element ? element.closest(containerSelector) : null;
    }

    function parentContainer(element) {
        var parent = element ? element.parentElement : null;

        while (parent) {
            if (parent.matches && parent.matches(containerSelector)) {
                return parent;
            }
            parent = parent.parentElement;
        }

        return null;
    }

    function directContainers(element) {
        if (!element) {
            return [];
        }

        return Array.prototype.filter.call(
            element.querySelectorAll(containerSelector),
            function (candidate) {
                return parentContainer(candidate) === element;
            }
        );
    }

    function findMarker() {
        var headings = document.querySelectorAll('.elementor-heading-title');
        var index;

        for (index = 0; index < headings.length; index += 1) {
            if (normalize(headings[index].textContent) === markerText) {
                return headings[index];
            }
        }

        return null;
    }

    function findMediaColumn(inner, copyColumn) {
        var candidates = directContainers(inner).filter(function (candidate) {
            return candidate !== copyColumn;
        });

        candidates.sort(function (left, right) {
            var leftText = normalize(left.textContent).length;
            var rightText = normalize(right.textContent).length;
            var leftArea = left.getBoundingClientRect().width * left.getBoundingClientRect().height;
            var rightArea = right.getBoundingClientRect().width * right.getBoundingClientRect().height;

            if (leftText !== rightText) {
                return leftText - rightText;
            }

            return rightArea - leftArea;
        });

        return candidates.length ? candidates[0] : null;
    }

    function addContent(mediaColumn) {
        if (mediaColumn.querySelector('.be-platinum-home-hero-media__content')) {
            return;
        }

        mediaColumn.insertAdjacentHTML(
            'beforeend',
            '<span class="be-platinum-home-hero-media__eyebrow">Body Energy Experience</span>' +
            '<div class="be-platinum-home-hero-media__content">' +
                '<span class="be-platinum-home-hero-media__kicker">La palestra</span>' +
                '<strong class="be-platinum-home-hero-media__title">Spazi reali.<br>Risultati reali.</strong>' +
                '<span class="be-platinum-home-hero-media__meta">Viale Amedeo D’Aosta 3 · Palermo</span>' +
            '</div>'
        );
    }

    function removeLegacyPlayers(mediaColumn) {
        Array.prototype.forEach.call(
            mediaColumn.querySelectorAll(
                '.be-platinum-home-hero-media__video, .elementor-widget-video, .wp-video, .mejs-container'
            ),
            function (player) {
                player.remove();
            }
        );
    }

    function addVideoPressPlayer(mediaColumn) {
        var iframe;

        if (!config.videoPressUrl) {
            mediaColumn.setAttribute('data-video-state', 'missing-videopress-url');
            return;
        }

        iframe = mediaColumn.querySelector('.be-platinum-home-hero-media__videopress');
        if (iframe) {
            return;
        }

        removeLegacyPlayers(mediaColumn);

        iframe = document.createElement('iframe');
        iframe.className = 'be-platinum-home-hero-media__videopress';
        iframe.src = String(config.videoPressUrl);
        iframe.title = 'Video della palestra Body Energy ASD Palermo';
        iframe.allow = 'autoplay; fullscreen; picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.loading = 'eager';
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('scrolling', 'no');
        iframe.setAttribute('aria-hidden', 'true');
        iframe.tabIndex = -1;

        iframe.addEventListener('load', function () {
            mediaColumn.classList.add('be-platinum-home-hero-media--videopress-ready');
            mediaColumn.setAttribute('data-video-state', 'videopress-loaded');
        });

        iframe.addEventListener('error', function () {
            mediaColumn.classList.remove('be-platinum-home-hero-media--videopress-ready');
            mediaColumn.setAttribute('data-video-state', 'videopress-error');
        });

        mediaColumn.insertBefore(iframe, mediaColumn.firstChild);
    }

    function enhanceHero() {
        var marker = findMarker();
        var copyColumn;
        var inner;
        var mediaColumn;

        if (!marker) {
            return false;
        }

        copyColumn = closestContainer(marker);
        inner = parentContainer(copyColumn);

        if (!copyColumn || !inner) {
            return false;
        }

        mediaColumn = findMediaColumn(inner, copyColumn);

        if (!mediaColumn) {
            return false;
        }

        inner.classList.add('be-platinum-home-hero-inner');
        copyColumn.classList.add('be-platinum-home-hero-copy');
        mediaColumn.classList.add('be-platinum-home-hero-media');
        mediaColumn.setAttribute('aria-label', 'Video della sala attrezzi Body Energy ASD Palermo');

        if (config.imageUrl) {
            mediaColumn.style.setProperty(
                '--be-platinum-home-image',
                'url("' + String(config.imageUrl).replace(/"/g, '\\"') + '")'
            );
        }

        addContent(mediaColumn);
        addVideoPressPlayer(mediaColumn);

        return true;
    }

    function boot() {
        var attempts = 0;
        var interval = window.setInterval(function () {
            attempts += 1;
            if (enhanceHero() || attempts >= 50) {
                window.clearInterval(interval);
            }
        }, 250);
        var observer = new MutationObserver(function () {
            enhanceHero();
        });

        observer.observe(document.documentElement, {
            childList: true,
            subtree: true
        });

        window.setTimeout(function () {
            observer.disconnect();
        }, 15000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
}());
