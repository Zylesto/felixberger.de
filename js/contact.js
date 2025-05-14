document.addEventListener('DOMContentLoaded', () => {
    const openLink = document.getElementById('open-contact');
    const modal = document.getElementById('contact-modal');
    const closeBtn = document.querySelector('#contact-modal .close-modal');
    const form = document.getElementById('contact-form');
    const msg = document.getElementById('contact-message');

    if (openLink && modal) {
        openLink.addEventListener('click', e => {
            e.preventDefault();
            modal.style.display = 'flex';
        });
    }

    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => modal.style.display = 'none');
    }

    window.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        msg.textContent = '';

        if (form.dataset.sending === 'true') return;
        form.dataset.sending = 'true';

        grecaptcha.ready(function() {
            grecaptcha.execute('6Le4BB4rAAAAACYeaNpwBlBmwd-7qOYVjmtOR4_u', {
                    action: 'contact'
                })
                .then(function(token) {
                    if (!token) {
                        msg.style.color = '#c00';
                        msg.textContent = 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.';
                        form.dataset.sending = 'false';
                        return;
                    }

                    const data = new FormData(form);
                    data.append('g-recaptcha-response', token);

                    fetch(form.action, {
                            method: 'POST',
                            body: data
                        })
                        .then(async response => {
                            const text = await response.text();
                            try {
                                const json = JSON.parse(text);
                                msg.style.color = response.ok ? '#060' : '#c00';
                                msg.textContent = json.message;
                                if (response.ok) form.reset();
                            } catch (e) {
                                msg.style.color = '#c00';
                                msg.textContent = 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.';
                            }
                            form.dataset.sending = 'false';
                        })
                        .catch(err => {
                            console.error('Fetch-Error:', err);
                            msg.style.color = '#c00';
                            msg.textContent = 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.';
                            form.dataset.sending = 'false';
                        });
                });
        });
    });
});