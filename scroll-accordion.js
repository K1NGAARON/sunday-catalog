document.addEventListener("DOMContentLoaded", function () {
    // Add click event listener to the element with ID "scroll-to-accordion"
    const scrollToAccordion = document.getElementById("scroll-to-accordion");
  
    if (scrollToAccordion) {
      scrollToAccordion.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default scroll behavior (if any)
  
        // Scroll to the accordion wrapper
        const accordionWrapper = document.querySelector(".product-accordion-wrapper");
        if (accordionWrapper) {
          accordionWrapper.scrollIntoView({ behavior: "smooth" });
  
          // Open the third accordion item
          const thirdAccordion = accordionWrapper.querySelector('[data-accordion-index="3"]');
          if (thirdAccordion) {
            // Add the active class to the title and content
            const title = thirdAccordion.querySelector(".wd-accordion-title");
            const content = thirdAccordion.querySelector(".wd-accordion-content");
  
            if (title && content) {
              // Close any currently open accordion items
              const activeItems = accordionWrapper.querySelectorAll(".wd-active");
              activeItems.forEach(item => item.classList.remove("wd-active"));
              accordionWrapper.querySelectorAll(".wd-accordion-content").forEach(content => content.style.display = "none");
  
              // Open the third accordion
              title.classList.add("wd-active");
              content.classList.add("wd-active");
              content.style.display = "block"; // Ensure content is visible
            }
          } else {
            console.error("Third accordion item not found.");
          }
        } else {
          console.error("Accordion wrapper not found.");
        }
      });
    } else {
      console.error("Element with ID 'scroll-to-accordion' not found.");
    }
  });