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
            '<button class="be-platinum-home-hero-media__play" type="button" aria-label="Avvia il video della palestra">' +
                '<span aria-hidden="true">▶</span><strong>Avvia video</strong>' +
            '</button>' +
            '<div class="be-platinum-home-hero-media__content">' +
                '<span class="be-platinum-home-hero-media__kicker">La palestra</span>' +
                '<strong class="be-platinum-home-hero-media__title">Spazi reali.<br>Risultati reali.</strong>' +
                '<span class="be-platinum-home-hero-media__meta">Viale Amedeo D’Aosta 3 · Palermo</span>' +
            '</div>'
        );
    }

    function markReady(mediaColumn) {
        mediaColumn.classList.add('be-platinum-home-hero-media--video-ready');
        mediaColumn.setAttribute('data-video-state', 'ready');
    }

    function markPlaying(mediaColumn) {
        markReady(mediaColumn);
        mediaColumn.classList.remove('be-platinum-home-hero-media--play-required');
        mediaColumn.setAttribute('data-video-state', 'playing');
    }

    function requirePlay(mediaColumn) {
        markReady(mediaColumn);
        mediaColumn.classList.add('be-platinum-home-hero-media--play-required');
        mediaColumn.setAttribute('data-video-state', 'awaiting-play');
    }

    function attemptPlayback(video, mediaColumn) {
        var promise;

        video.muted = true;
        video.defaultMuted = true;
        video.volume = 0;
        video.removeAttribute('controls');
        video.controls = false;

        try {
            promise = video.play();
        } catch (error) {
            requirePlay(mediaColumn);
            return;
        }

        if (promise && typeof promise.then === 'function') {
            promise.then(function () {
                markPlaying(mediaColumn);
            }).catch(function () {
                requirePlay(mediaColumn);
            });
        }
    }

    function addDirectVideo(mediaColumn) {
        var video;
        var playButton;
        var reducedMotion;
        var playbackTimer;

        if (!config.videoUrl) {
            mediaColumn.setAttribute('data-video-state', 'missing-direct-url');
            return;
        }

        video = mediaColumn.querySelector('.be-platinum-home-hero-media__video');
        if (video) {
            return;
        }

        Array.prototype.forEach.call(
            mediaColumn.querySelectorAll('.mejs-container, .wp-video'),
            function (legacyPlayer) {
                legacyPlayer.remove();
            }
        );

        video = document.createElement('video');
        video.className = 'be-platinum-home-hero-media__video';
        video.src = String(config.videoUrl);
        video.autoplay = true;
        video.muted = true;
        video.defaultMuted = true;
        video.loop = true;
        video.playsInline = true;
        video.preload = 'auto';
        video.controls = false;
        video.disablePictureInPicture = true;
        video.setAttribute('autoplay', '');
        video.setAttribute('muted', '');
        video.setAttribute('loop', '');
        video.setAttribute('playsinline', '');
        video.setAttribute('webkit-playsinline', '');
        video.setAttribute('disablepictureinpicture', '');
        video.setAttribute('aria-hidden', 'true');
        video.setAttribute('data-source', config.videoSource || 'direct');
        video.tabIndex = -1;

        if (config.posterUrl) {
            video.poster = String(config.posterUrl);
        }

        video.addEventListener('loadedmetadata', function () {
            markReady(mediaColumn);
        });

        video.addEventListener('loadeddata', function () {
            markReady(mediaColumn);
        });

        video.addEventListener('canplay', function () {
            markReady(mediaColumn);
        });

        video.addEventListener('playing', function () {
            window.clearTimeout(playbackTimer);
            markPlaying(mediaColumn);
        });

        video.addEventListener('timeupdate', function () {
            if (video.currentTime > 0.15) {
                markPlaying(mediaColumn);
            }
        });

        video.addEventListener('pause', function () {
            if (!video.ended && document.visibilityState === 'visible') {
                requirePlay(mediaColumn);
            }
        });

        video.addEventListener('error', function () {
            mediaColumn.classList.remove('be-platinum-home-hero-media--video-ready');
            mediaColumn.classList.remove('be-platinum-home-hero-media--play-required');
            mediaColumn.setAttribute('data-video-state', 'direct-video-error');
        });

        mediaColumn.insertBefore(video, mediaColumn.firstChild);
        video.load();

        playButton = mediaColumn.querySelector('.be-platinum-home-hero-media__play');
        if (playButton) {
            playButton.addEventListener('click', function () {
                attemptPlayback(video, mediaColumn);
            });
        }

        reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (reducedMotion) {
            requirePlay(mediaColumn);
        } else {
            attemptPlayback(video, mediaColumn);
        }

        playbackTimer = window.setTimeout(function () {
            if (video.paused || video.currentTime <= 0.05) {
                requirePlay(mediaColumn);
            }
        }, 3500);

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden && video.paused && !reducedMotion) {
                attemptPlayback(video, mediaColumn);
            }
        });
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
        addDirectVideo(mediaColumn);

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
