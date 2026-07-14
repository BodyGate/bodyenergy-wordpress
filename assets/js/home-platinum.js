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

    function addExperienceSection(inner) {
        var heroRoot;
        var section;

        if (document.querySelector('.be-platinum-experience')) {
            return;
        }

        heroRoot = parentContainer(inner) || inner;
        if (!heroRoot || !heroRoot.parentNode) {
            return;
        }

        section = document.createElement('section');
        section.className = 'be-platinum-experience';
        section.setAttribute('aria-labelledby', 'be-platinum-experience-title');
        section.innerHTML =
            '<div class="be-platinum-experience__glow" aria-hidden="true"></div>' +
            '<div class="be-platinum-experience__inner">' +
                '<div class="be-platinum-experience__header">' +
                    '<div class="be-platinum-experience__heading">' +
                        '<span class="be-platinum-experience__eyebrow">L’esperienza Body Energy</span>' +
                        '<h2 id="be-platinum-experience-title">Tutto ciò che ti serve.<br><span>In un unico centro.</span></h2>' +
                    '</div>' +
                    '<p>Allenamento, attenzione e servizi pensati per accompagnarti davvero. Un ambiente completo, curato e accessibile, dove ogni persona può costruire il proprio percorso.</p>' +
                '</div>' +
                '<div class="be-platinum-experience__grid">' +
                    '<article class="be-platinum-experience-card">' +
                        '<div class="be-platinum-experience-card__top">' +
                            '<span class="be-platinum-experience-card__icon" aria-hidden="true">' +
                                '<svg viewBox="0 0 32 32" fill="none"><path d="M7 12v8M11 9v14M21 9v14M25 12v8M11 16h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>' +
                            '</span>' +
                            '<span class="be-platinum-experience-card__number">01</span>' +
                        '</div>' +
                        '<h3>Sala fitness completa</h3>' +
                        '<p>Attrezzature selezionate, spazi organizzati e tutto ciò che serve per allenarti con qualità.</p>' +
                        '<span class="be-platinum-experience-card__line" aria-hidden="true"></span>' +
                    '</article>' +
                    '<article class="be-platinum-experience-card">' +
                        '<div class="be-platinum-experience-card__top">' +
                            '<span class="be-platinum-experience-card__icon" aria-hidden="true">' +
                                '<svg viewBox="0 0 32 32" fill="none"><rect x="6.5" y="10" width="19" height="12" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M10 22v3M22 22v3M11 14h10M16 10V7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>' +
                            '</span>' +
                            '<span class="be-platinum-experience-card__number">02</span>' +
                        '</div>' +
                        '<h3>Pilates Reformer</h3>' +
                        '<p>Solo quattro postazioni per offrire più spazio, maggiore controllo e attenzione individuale.</p>' +
                        '<span class="be-platinum-experience-card__line" aria-hidden="true"></span>' +
                    '</article>' +
                    '<article class="be-platinum-experience-card">' +
                        '<div class="be-platinum-experience-card__top">' +
                            '<span class="be-platinum-experience-card__icon" aria-hidden="true">' +
                                '<svg viewBox="0 0 32 32" fill="none"><circle cx="16" cy="11" r="4" stroke="currentColor" stroke-width="1.7"/><path d="M8.5 25c.8-5 3.3-7.5 7.5-7.5s6.7 2.5 7.5 7.5M24 8.5l1.4 1.4L28 7.3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>' +
                            '</span>' +
                            '<span class="be-platinum-experience-card__number">03</span>' +
                        '</div>' +
                        '<h3>Percorsi personalizzati</h3>' +
                        '<p>Un team presente per costruire un allenamento coerente con obiettivi, livello ed esigenze.</p>' +
                        '<span class="be-platinum-experience-card__line" aria-hidden="true"></span>' +
                    '</article>' +
                    '<article class="be-platinum-experience-card be-platinum-experience-card--comfort">' +
                        '<div class="be-platinum-experience-card__top">' +
                            '<span class="be-platinum-experience-card__icon" aria-hidden="true">' +
                                '<svg viewBox="0 0 32 32" fill="none"><path d="M16 5v22M10.5 8.2 16 11.4l5.5-3.2M10.5 23.8 16 20.6l5.5 3.2M6.5 13l5.4 3-5.4 3M25.5 13l-5.4 3 5.4 3" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round"/></svg>' +
                            '</span>' +
                            '<span class="be-platinum-experience-card__number">04</span>' +
                        '</div>' +
                        '<h3>Comfort totale</h3>' +
                        '<p>Tutta la palestra è climatizzata, compresi gli spogliatoi, per offrirti comfort in ogni stagione.</p>' +
                        '<span class="be-platinum-experience-card__line" aria-hidden="true"></span>' +
                    '</article>' +
                '</div>' +
            '</div>';

        heroRoot.parentNode.insertBefore(section, heroRoot.nextSibling);
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
        addExperienceSection(inner);

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
