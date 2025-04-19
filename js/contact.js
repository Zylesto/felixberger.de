// Wait for the HTML document to be fully loaded and initialize modal and form elements
document.addEventListener('DOMContentLoaded', () => {
    const openLink = document.getElementById('open-contact');
    const modal = document.getElementById('contact-modal');
    const closeBtn = document.querySelector('#contact-modal .close-modal');
    const form = document.getElementById('contact-form');
  
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
  
    // Logic: Prepare response container for feedback messages
    const responseContainer = document.createElement('div');
    responseContainer.id = 'form-response';
    form.parentNode.insertBefore(responseContainer, form.nextSibling);
  
    form.addEventListener('submit', async e => {
      e.preventDefault();
      responseContainer.textContent = '';
  
      const formData = new FormData(form);
      try {
        const res = await fetch('../php/contact-handler.php', {
          method: 'POST',
          body: formData
        });
        const msg = await res.text();
        responseContainer.textContent = msg;
        if (res.ok) form.reset();
      } catch {
        responseContainer.textContent = 'Error sending message. Please try again.';
      }
    });
  });
  