document.addEventListener('DOMContentLoaded', function() {
    // Get the selected date from the URL or use the default date as defined in the PHP
    const urlParams = new URLSearchParams(window.location.search);
    const selectedDate = urlParams.get('date') || defaultSelectedDate;

    // Function to set the 'selected' class on the appropriate date
    function setSelectedDate() {
        document.querySelectorAll('.date-selection .date').forEach(function(dateElement) {
            const date = dateElement.getAttribute('data-date');
            if (date === selectedDate) {
                dateElement.classList.add('selected');
            } else {
                dateElement.classList.remove('selected');
            }
        });
    }

    









    // Call the function to apply the correct selection
    setSelectedDate();

    document.querySelectorAll('.date-selection .date').forEach(function(dateElement) {
        dateElement.addEventListener('click', function() {
            // Remove 'selected' class from all dates
            document.querySelectorAll('.date-selection .date').forEach(function(el) {
                el.classList.remove('selected');
            });
            // Add 'selected' class to the clicked date
            this.classList.add('selected');
            
            // Update the URL with the selected date
            const newSelectedDate = this.getAttribute('data-date');
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('date', newSelectedDate);
            window.history.replaceState({}, '', currentUrl);
            
            // Reload the page with the new selected date
            window.location.reload();
        });
    });
});
