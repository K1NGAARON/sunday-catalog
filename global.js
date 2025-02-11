document.addEventListener('DOMContentLoaded', function () {
    function waitForButtonAndUpdate() {
        const interval = setInterval(() => {
            const button = document.querySelector('.button.checkout.wc-forward');
            if (button) {
                button.textContent = 'Request Quote';
                console.log('Button found and updated:', button);
                
                // Stop the interval once the button is found and updated
                clearInterval(interval);
            }
        }, 100); // Check every 100 milliseconds
    }

    waitForButtonAndUpdate(); // Call the function after DOM is loaded

    // Add click event listener to the element with ID "scroll-to-accordion"
    const scrollToAccordion = document.getElementById("scroll-to-accordion");

    if (!scrollToAccordion) return;

    scrollToAccordion.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent default scroll behavior (if any)

        // Scroll to the accordion wrapper
        const accordionWrapper = document.querySelector("#product-accordion-wrapper");
        if (accordionWrapper) {
            accordionWrapper.scrollIntoView({ behavior: "smooth" });

            const activeAccordion = document.querySelector('.wd-accordion-title.wd-active');
            const activeAccordionContent = document.querySelector('.wd-accordion-content.wd-entry-content.wd-active');

            if (activeAccordion && activeAccordionContent) {
                activeAccordion.classList.remove("wd-active");
                activeAccordionContent.classList.remove("wd-active");
                activeAccordionContent.style.display = "none";
            }

            const accordions = document.querySelectorAll('.wd-accordion-title');
            const accordionContents = document.querySelectorAll('.wd-accordion-content.wd-entry-content');

            if (accordions.length >= 3 && accordionContents.length >= 3) {
                accordions[2].classList.add("wd-active");
                accordionContents[2].classList.add("wd-active");
            }
        }
    });
});