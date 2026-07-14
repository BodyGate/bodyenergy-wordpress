(function () {
    'use strict';

    var config = window.BodyEnergyPlatinumHome || {};
    var containerSelector = '[data-element_type="container"], .e-con';
    var markerText = 'BODY ENERGY ASD · PALERMO';
    var candidatePromise = null;

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

    function absoluteUrl(value) {
        try {
            return new URL(String(value || ''), window.location.origin).href;
        } catch (error) {
            return '';
        }
    }

    function pushCandidate(list, value) {
        var url = absoluteUrl(value);

        if (!url || list.indexOf(url) !== -1) {
            return;
        }

        if (!/\.(mp4|m4v|webm|ogv|ogg|mov)(?:[?#].*)?$/i.test(url)) {
            return;
        }

        list.push(url);
    }

    function candidatesFromHtml(html) {
        var list = [];
        var parser = new DOMParser();
        var documentCopy = parser.parseFromString(html, 'text/html');
        var nodes = documentCopy.querySelectorAll('video, video source, source[type^="video/"]');
        var attributes = ['src', 'data-src', 'data-lazy-src', 'data-orig-src', 'data-video-src'];
        var regex = /https?:\\?\/\\?\/[^"'\s<>]+?\.(?:mp4|m4v|webm|ogv|ogg|mov)(?:\?[^"'\s<>]*)?/gi;
        var matches = String(html || '').match(regex) || [];

        Array.prototype.forEach.call(nodes, function (node) {
            attributes.forEach(function (attribute) {
                if (node.getAttribute(attribute)) {
                    pushCandidate(list, node.getAttribute(attribute));
                }
            });
        });

        matches.forEach(function (match) {
            pushCandidate(list, match.replace(/\\\//g, '/'));
        });

        return list;
    }

    function resolveVideoCandidates() {
        var initial = [];
        var homeUrl = window.location.origin + '/';

        if (candidatePromise) {
            return candidatePromise;
        }

        pushCandidate(initial, config.videoUrl);

        candidatePromise = window.fetch(homeUrl, {
            credentials: 'same-origin',
            cache: 'no-store',
            headers: {
                'X-Requested-With': 'BodyEnergyPlatinumHome'
            }
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Home video source unavailable');
            }
            return response.text();
        }).then(function (html) {
            candidatesFromHtml(html).forEach(function (url) {
                pushCandidate(initial, url);
            });
            return initial;
        }).catch(function () {
            return initial;
        });

        return candidatePromise;
    }

    function markVideoReady(mediaColumn, video) {
        mediaColumn.classList.add('be-platinum-home-hero-media--video-ready');
        mediaColumn.setAttribute('data-video-state', 'playing');
        video.style.opacity = '1';
    }

    function attemptPlayback(video, mediaColumn) {
        var promise;

        video.muted = true;
        video.defaultMuted = true;
        video.volume = 0;

        try {
            promise = video.play();
        } catch (error) {
            promise = null;
        }

        if (promise && typeof promise.then === 'function') {
            promise.then(function () {
                markVideoReady(mediaColumn, video);
            }).catch(function () {
                mediaColumn.setAttribute('data-video-state', 'awaiting-interaction');
            });
        }
    }

    function tryVideoCandidate(mediaColumn, candidates, index) {
        var video;
        var timeout;
        var finished = false;
        var candidate = candidates[index];

        if (!candidate) {
            mediaColumn.setAttribute('data-video-state', 'fallback-image');
            return;
        }

        Array.prototype.forEach.call(
            mediaColumn.querySelectorAll('.be-platinum-home-hero-media__video'),
            function (existing) {
                existing.remove();
            }
        );

        mediaColumn.classList.remove('be-platinum-home-hero-media--video-ready');
        mediaColumn.setAttribute('data-video-state', 'loading');

        video = document.createElement('video');
        video.className = 'be-platinum-home-hero-media__video';
        video.autoplay = true;
        video.muted = true;
        video.defaultMuted = true;
        video.loop = true;
        video.playsInline = true;
        video.preload = 'auto';
        video.controls = false;
        video.disablePictureInPicture = true;
        video.setAttribute('muted', '');
        video.setAttribute('autoplay', '');
        video.setAttribute('loop', '');
        video.setAttribute('playsinline', '');
        video.setAttribute('webkit-playsinline', '');
        video.setAttribute('aria-hidden', 'true');
        video.setAttribute('src', candidate);
        video.tabIndex = -1;

        if (config.posterUrl) {
            video.poster = config.posterUrl;
        }

        function ready() {
            if (finished) {
                return;
            }
            window.clearTimeout(timeout);
            markVideoReady(mediaColumn, video);
            attemptPlayback(video, mediaColumn);
        }

        function failed() {
            if (finished) {
                return;
            }
            finished = true;
            window.clearTimeout(timeout);
            video.remove();
            tryVideoCandidate(mediaColumn, candidates, index + 1);
        }

        video.addEventListener('loadeddata', ready, { once: true });
        video.addEventListener('canplay', ready, { once: true });
        video.addEventListener('playing', ready);
        video.addEventListener('error', failed, { once: true });
        video.addEventListener('stalled', function () {
            if (video.readyState < 2) {
                failed();
            }
        }, { once: true });

        mediaColumn.insertBefore(video, mediaColumn.firstChild);
        video.load();
        attemptPlayback(video, mediaColumn);

        timeout = window.setTimeout(function () {
            if (video.readyState < 2) {
                failed();
            } else {
                ready();
            }
        }, 9000);

        document.addEventListener('pointerdown', function retryAfterInteraction() {
            if (document.contains(video) && video.paused) {
                attemptPlayback(video, mediaColumn);
            }
        }, { once: true, passive: true });
    }

    function ensureVideo(mediaColumn) {
        var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (reducedMotion || mediaColumn.getAttribute('data-video-resolving') === 'true') {
            return;
        }

        mediaColumn.setAttribute('data-video-resolving', 'true');

        resolveVideoCandidates().then(function (candidates) {
            mediaColumn.removeAttribute('data-video-resolving');

            if (!candidates.length) {
                mediaColumn.setAttribute('data-video-state', 'no-source');
                return;
            }

            tryVideoCandidate(mediaColumn, candidates, 0);
        });
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

        ensureVideo(mediaColumn);
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

        document.addEventListener('visibilitychange', function () {
            var video = document.querySelector('.be-platinum-home-hero-media__video');
            var mediaColumn = document.querySelector('.be-platinum-home-hero-media');

            if (!document.hidden && video && mediaColumn && video.paused) {
                attemptPlayback(video, mediaColumn);
            }
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
