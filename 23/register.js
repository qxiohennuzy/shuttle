document.getElementById('registration-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    const firstName = document.getElementById('first-name').value;
    const lastName = document.getElementById('last-name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const userRole = document.getElementById('user-role').value;

    alert(Registration Successful!\nWelcome, ${firstName} ${lastName}!\nYour Email: ${email}\nUser Role: ${userRole});
});