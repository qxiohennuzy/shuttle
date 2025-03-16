document.getElementById('booking-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior
    
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const passenger = document.getElementById('passenger').value;

    alert(`Booking Confirmed!\nFrom: ${from}\nTo: ${to}\nDate: ${date}\nTime: ${time}\nPassengers: ${passenger}`);
});