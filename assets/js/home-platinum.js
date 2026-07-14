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

    function addVideo(mediaColumn) {
        var video;
        var source;
        var playPromise;
        var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!config.videoUrl || reducedMotion || mediaColumn.querySelector('.be-platinum-home-hero-media__video')) {
            return;
        }

        video = document.createElement('video');
        video.className = 'be-platinum-home-hero-media__video';
        video.autoplay = true;
        video.muted = true;
        video.defaultMuted = true;
        video.loop = true;
        video.playsInline = true;
        video.preload = 'metadata';
        video.setAttribute('muted', '');
        video.setAttribute('playsinline', '');
        video.setAttribute('webkit-playsinline', '');
        video.setAttribute('aria-hidden', 'true');
        video.tabIndex = -1;

        if (config.posterUrl) {
            video.poster = config.posterUrl;
        }

        source = document.createElement('source');
        source.src = config.videoUrl;
        source.type = config.videoMime || 'video/mp4';
        video.appendChild(source);

        video.addEventListener('canplay', function () {
            mediaColumn.classList.add('be-platinum-home-hero-media--video-ready');
        }, { once: true });

        video.addEventListener('error', function () {
            video.remove();
            mediaColumn.classList.remove('be-platinum-home-hero-media--video-ready');
        }, { once: true });

        mediaColumn.insertBefore(video, mediaColumn.firstChild);
        playPromise = video.play();

        if (playPromise && typeof playPromise.catch === 'function') {
            playPromise.catch(function () {
                mediaColumn.classList.remove('be-platinum-home-hero-media--video-ready');
            });
        }
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
        mediaColumn.setAttribute('role', 'img');
        mediaColumn.setAttribute('aria-label', 'Sala attrezzi Body Energy ASD Palermo');

        if (config.imageUrl) {
            mediaColumn.style.setProperty(
                '--be-platinum-home-image',
                'url("' + String(config.imageUrl).replace(/"/g, '\\"') + '")'
            );
        }

        addVideo(mediaColumn);
        addContent(mediaColumn);

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
