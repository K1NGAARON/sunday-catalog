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


document.addEventListener("DOMContentLoaded", function () {
    // Add click event listener to the element with ID "scroll-to-accordion"
    const scrollToAccordion = document.getElementById("scroll-to-accordion");
  
    if (scrollToAccordion) {
      scrollToAccordion.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default scroll behavior (if any)
  
        // Scroll to the accordion wrapper
        const accordionWrapper = document.querySelector("#product-accordion-wrapper");
        if (accordionWrapper) {
          accordionWrapper.scrollIntoView({ behavior: "smooth" });
  
            // Close active accordion
            // Open third accordion

        } else {
          console.error("Accordion wrapper not found.");
        }
      });
    } else {
      console.error("Element with ID 'scroll-to-accordion' not found.");
    }
  });
  