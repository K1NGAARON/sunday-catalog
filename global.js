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

// Start checking once the DOM is loaded
document.addEventListener('DOMContentLoaded', waitForButtonAndUpdate);


// Add click event listener to the element with ID "scroll-to-accordion"
const scrollToAccordion = document.getElementById("scroll-to-accordion");

if (scrollToAccordion) {
    scrollToAccordion.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default scroll behavior (if any)

    // Scroll to the accordion wrapper
    const accordionWrapper = document.querySelector("#product-accordion-wrapper");
    if (accordionWrapper) {
        accordionWrapper.scrollIntoView({ behavior: "smooth" });

        const activeAccordion = document.querySelector('.wd-accordion-title.wd-active');
        const activeAccordionContent = document.querySelector('.wd-accordion-content.wd-entry-content.wd-active');

        activeAccordion.classList.remove("wd-active");
        activeAccordionContent.classList.remove("wd-active");
        activeAccordionContent.style.display = "none";

        const thirdAccordion = document.querySelectorAll('.wd-accordion-title')[2];
        const thirdAccordionContent = document.querySelectorAll('.wd-accordion-content.wd-entry-content')[2];
        thirdAccordion.classList.add("wd-active");
        thirdAccordionContent.classList.add("wd-active");
        
    } else {
        console.error("Accordion wrapper not found.");
    }
    });
} else {
    console.error("Element with ID 'scroll-to-accordion' not found.");
}