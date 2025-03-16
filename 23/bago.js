function editBus(id) {
    // Here you would fetch the bus details based on the ID and fill the form
    // For the sake of example, we'll just display a modal
    let modal = document.getElementById("edit-modal");
    modal.style.display = "block";
}

function closeModal() {
    let modal = document.getElementById("edit-modal");
    modal.style.display = "none";
}

function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this bus detail?")) {
        // Call function to delete the bus. This is just a simulation.
        alert("Bus detail deleted.");
    }
}

// Close the modal when the user clicks anywhere outside of it
window.onclick = function(event) {
    const modal = document.getElementById('edit-modal');
    if (event.target == modal) {
        closeModal();
    }
}