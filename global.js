document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');

    function showPriceModal() {
        const modalToggle = document.querySelector('.info-toggle');
        const priceModal = document.querySelector('.price-disclaimer'); 

        // Use 'mouseenter' and 'mouseleave' instead of 'hover'
        modalToggle.addEventListener('mouseenter', function() {
            priceModal.classList.add('active'); 
        });

        modalToggle.addEventListener('mouseleave', function() {
            priceModal.classList.remove('active');
        });
    };

    showPriceModal();
});