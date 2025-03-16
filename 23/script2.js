// Initialize total counts in local storage if they do not exist
if (!localStorage.getItem('totalBusCount')) {
    localStorage.setItem('totalBusCount', '0');
}
if (!localStorage.getItem('totalBusesIn')) {
    localStorage.setItem('totalBusesIn', '0');
}
if (!localStorage.getItem('totalBusesOut')) {
    localStorage.setItem('totalBusesOut', '0');
}
if (!localStorage.getItem('totalBookings')) {
    localStorage.setItem('totalBookings', '0');
}
if (!localStorage.getItem('totalUsers')) {
    localStorage.setItem('totalUsers', '0');
}

// Function to update total counts in the dashboard
function updateTotalCounts() {
    const totalBusCount = parseInt(localStorage.getItem('totalBusCount')) || 0; // Total buses
    const totalBusesIn = parseInt(localStorage.getItem('totalBusesIn')) || 0; // Get buses in
    const totalBusesOut = parseInt(localStorage.getItem('totalBusesOut')) || 0; // Get buses out
    const totalBookings = parseInt(localStorage.getItem('totalBookings')) || 0; // Get total bookings
    const totalUsers = parseInt(localStorage.getItem('totalUsers')) || 0; // Placeholder for total users

    document.getElementById("total-buses").innerText = totalBusCount;
    document.getElementById("total-buses-in").innerText = totalBusesIn;
    document.getElementById("total-buses-out").innerText = totalBusesOut;
    document.getElementById("total-bookings").innerText = totalBookings; 
    document.getElementById("total-users").innerText = totalUsers; 
}

// Function to add a booking
function addBooking() {
    const totalBookings = parseInt(localStorage.getItem('totalBookings')) || 0;
    localStorage.setItem('totalBookings', totalBookings + 1); // Increment total bookings in local storage
}

// Function to add a bus
function addBus() {
    const buses = JSON.parse(localStorage.getItem('buses')) || [];
    const newBusNo = document.getElementById('busNo').value;

    if (newBusNo) {
        buses.push({ no: newBusNo });
        localStorage.setItem('buses', JSON.stringify(buses));
        localStorage.setItem('totalBusCount', buses.length); // Update total bus count
        document.getElementById('addBusForm').reset();
    }
}

// Function to delete a bus
function deleteBus() {
    let buses = JSON.parse(localStorage.getItem('buses')) || [];
    const currentBusNo = prompt("Enter the bus number to delete:");
    if (currentBusNo) {
        buses = buses.filter(bus => bus.no !== currentBusNo); // Filter out the bus to delete
        localStorage.setItem('buses', JSON.stringify(buses)); // Save to localStorage
        localStorage.setItem('totalBusCount', buses.length); // Update total bus count
        alert("Bus information has been deleted!");
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('addBookingForm')) {
        document.getElementById('addBookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            addBooking();
            updateTotalCounts();
        });
    }

    if (document.getElementById('addBusForm')) {
        document.getElementById('addBusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            addBus();
            updateTotalCounts();
        });
    }

    if (document.getElementById('deleteBusBtn')) {
        document.getElementById('deleteBusBtn').addEventListener('click', function() {
            deleteBus();
            updateTotalCounts();
        });
    }

    updateTotalCounts(); // Initial update of total counts
});