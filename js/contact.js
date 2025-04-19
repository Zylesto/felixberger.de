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

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    msg.textContent = '';

    grecaptcha.ready(function () {
      grecaptcha.execute('6LcK2R0rAAAAACUpM8ocqvEE9JLBvLBFuIA1pvHO', { action: 'contact' })
        .then(function (token) {
          if (!token) {
            msg.style.color = '#c00';
            return msg.textContent = 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.';
          }

          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'g-recaptcha-response';
          input.value = token;
          form.appendChild(input);

          const data = new FormData(form);

          fetch(form.action, {
            method: 'POST',
            body: data
          })
            .then(response => {
              return response.text();
            })
            .then(text => {
              try {
                const json = JSON.parse(text);
                msg.style.color = '#060';
                msg.textContent = json.message;
                if (response.ok) form.reset();
              } catch (e) {
                msg.style.color = '#c00';
                msg.textContent = text;
              }
            })
            .catch(err => {
              console.error('Fetch-Error:', err);
              msg.style.color = '#c00';
              msg.textContent = 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.';
            });
        });
    });
  });
});
