<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Inventor | Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="icon" href="icon.jfif" type="image/png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Added jQuery -->
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-login {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-login img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 1rem 0 0 1rem;
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(38, 143, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .text-muted {
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .card-login {
                flex-direction: column;
                margin-top: 1rem;
            }

            .card-body {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card-login d-flex shadow-lg col-md-8 col-lg-6">
            <!-- Image Section -->
            <div class="col-md-6">
                <img src="hepc.jpg" alt="Login Image">
            </div>
            <!-- Form Section -->
            <div class="card-body col-md-6">
                <h3 class="mb-4 text-center" style="font-weight: bold; font-size: 40px;">
                    <i class="fas fa-box" style="color: #007bff; margin-right: 2px;"></i>

                    IT Inventory
                </h3>
                <div id="messageContainer"></div> <!-- Message display container -->

                <form id="loginForm">
                    <!-- Username input -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" class="form-control" placeholder="Enter your username"
                            required>
                    </div>
                    <!-- Password input -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" class="form-control" placeholder="Enter your password"
                            required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <a href="resetform.php" class="text-muted">Forgot password?</a>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100" style="font-weight: bold;">Sign
                            In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-primary text-white text-center py-4">
        <p class="mb-0">© 2025 Hayakawa Electronics (Phil.s). Corp. All rights reserved. Version 1.1.1</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#loginForm').submit(function (e) {
                e.preventDefault();

                var username = $('#username').val();
                var password = $('#password').val();

                $.ajax({
                    url: 'login_request.php',
                    method: 'POST',
                    data: {
                        username: username,
                        password: password
                    },
                    dataType: 'json',  // Expecting JSON response
                    success: function (response) {
                        if (response.status === 'success') {
                            // Redirect to the URL provided by PHP based on user role
                            window.location.replace(response.redirect);
                        } else {
                            $('#messageContainer').html(response.message);  // Display error message
                        }
                    },
                    error: function (xhr, status, error) {
                        // Improved error handling to show specific error if available
                        $('#messageContainer').html('<div class="alert alert-danger">Error occurred: ' + (xhr.responseText || 'Please try again later') + '</div>');
                    }
                });
            });
        });
    </script>

</body>

</html>