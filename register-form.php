<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css?<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php
include("navigation.php");
?>
    <div class="container">
        <div class="register-form">
            <h2>Create Your Account</h2>
            <form id="registerForm" action="register_script.php" method="post">
                <label for="username">User Name:</label>
                <input type="text" id="username" name="username">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email">

                <label for="password">Password:</label>
                <input type="password" id="password" name="password">

                <button type="submit">Create Account</button>
                <p class="login-link">
                    <a href="login.php">I have an Account Login</a>
                </p>
            </form>
            <div id="message"></div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#registerForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (username.trim() === '' || email.trim() === '' || password.trim() === '') {
                    alert('All fields are required');
                    return false; // Stop the form submission
                }

                // Basic email validation using a regular expression
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Please enter a valid email address');
                    return false; // Stop the form submission
                }

                $.ajax({
                    type: "POST",
                    url: "register_script.php",
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Registration successful!');
                            window.location.href = response.redirect; // Redirect
                        } else {
                            alert(response.message); // Display error message from server in an alert
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>