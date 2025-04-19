document.addEventListener('DOMContentLoaded', () => {
    const openLink = document.getElementById('open-contact');
    const modal = document.getElementById('contact-modal');
    const closeBtn = document.querySelector('#contact-modal .close-modal');
  
    if (openLink && modal) {
      openLink.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'flex';
      });
    }
  
    if (closeBtn && modal) {
      closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
      });
    }
  
    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  });  