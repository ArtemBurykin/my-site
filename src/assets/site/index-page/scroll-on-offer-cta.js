export const addScrollOnOfferCtaClick = function() {
    document.getElementById('offer-cta').addEventListener('click', () => {
        const contactForm = document.getElementById('contact-form');
        contactForm.scrollIntoView({behavior: 'smooth'});
    });
};
