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