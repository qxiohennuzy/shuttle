// Handle Booking Management Form Submission
document.getElementById('booking-management-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    const bookingId = document.getElementById('booking-id').value;
    const status = document.getElementById('booking-status').value;

    alert(Booking Updated!\nBooking ID: ${bookingId}\nNew Status: ${status});
});

// Handle Route Management Form Submission
document.getElementById('route-management-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    const routeName = document.getElementById('route-name').value;
    const startPoint = document.getElementById('route-start').value;
    const endPoint = document.getElementById('route-end').value;

    alert(Route Added!\nRoute Name: ${routeName}\nStart Point: ${startPoint}\nEnd Point: ${endPoint});
});

// Handle User Management Form Submission
document.getElementById('user-management-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    const userId = document.getElementById('user-id').value;
    const role = document.getElementById('user-role').value;

    alert(User Role Updated!\nUser ID: ${userId}\nNew Role: ${role});
});