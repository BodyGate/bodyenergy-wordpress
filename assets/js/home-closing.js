(function () {
    'use strict';

    var config = window.BodyEnergyHomeClosing || {};

    function addLink(container, className, label, url) {
        var link;

        if (!container || !url) {
            return;
        }

        link = document.createElement('a');
        link.className = className;
        link.href = String(url);
        link.textContent = label;
        container.appendChild(link);
    }

    function addClosingSection() {
        var experience = document.querySelector('.be-platinum-experience');
        var section;
        var actions;
        var cardLink;
        var year;

        if (!experience || document.querySelector('.be-platinum-home-close')) {
            return Boolean(document.querySelector('.be-platinum-home-close'));
        }

        year = String(config.year || new Date().getFullYear());
        section = document.createElement('section');
        section.className = 'be-platinum-home-close';
        section.setAttribute('aria-labelledby', 'be-platinum-home-close-title');
        section.innerHTML =
            '<div class="be-platinum-home-close__inner">' +
                '<div class="be-platinum-home-close__copy">' +
                    '<span class="be-platinum-home-close__eyebrow">Body Energy ASD · Palermo</span>' +
                    '<h2 id="be-platinum-home-close-title">Il tuo percorso.<br><span>Il nostro impegno.</span></h2>' +
                    '<p class="be-platinum-home-close__lead">Scopri la palestra, il Pilates Reformer e i servizi Body Energy. Il nostro team è a disposizione per aiutarti a individuare il percorso più adatto alle tue esigenze.</p>' +
                    '<div class="be-platinum-home-close__actions"></div>' +
                '</div>' +
                '<aside class="be-platinum-home-close__card">' +
                    '<small>Viale Amedeo D’Aosta 3 · Palermo</small>' +
                    '<strong>Un centro completo.<br>Un contatto diretto.</strong>' +
                    '<p>Parla con la reception per ricevere informazioni sulla palestra e sulle attività disponibili.</p>' +
                    '<span class="be-platinum-home-close__card-link"></span>' +
                '</aside>' +
            '</div>' +
            '<div class="be-platinum-home-close__footer">' +
                '<span>© ' + year + ' Body Energy ASD</span>' +
                '<span>Viale Amedeo D’Aosta 3 · Palermo</span>' +
            '</div>';

        actions = section.querySelector('.be-platinum-home-close__actions');
        addLink(
            actions,
            'be-platinum-home-close__button be-platinum-home-close__button--primary',
            'Richiedi informazioni',
            config.contactsUrl
        );
        addLink(
            actions,
            'be-platinum-home-close__button be-platinum-home-close__button--secondary',
            'Scopri la palestra',
            config.gymUrl
        );

        cardLink = section.querySelector('.be-platinum-home-close__card-link');
        if (cardLink && config.contactsUrl) {
            cardLink.innerHTML = '<a href="' + String(config.contactsUrl).replace(/"/g, '&quot;') + '">Contatti e informazioni <b>→</b></a>';
        }

        experience.parentNode.insertBefore(section, experience.nextSibling);
        return true;
    }

    function boot() {
        var attempts = 0;
        var interval = window.setInterval(function () {
            attempts += 1;
            if (addClosingSection() || attempts >= 50) {
                window.clearInterval(interval);
            }
        }, 250);
        var observer = new MutationObserver(function () {
            addClosingSection();
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
