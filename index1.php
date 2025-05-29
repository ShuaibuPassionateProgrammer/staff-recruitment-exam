<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Recruitment Online Examination System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
            padding: 1rem 0;
        }
        .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 1.5rem;
        }
        .main-container {
            padding: 2rem 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background: transparent;
            border-bottom: none;
            padding: 1.5rem 1.5rem 0.5rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1.2rem;
            border: 1px solid #e0e0e0;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(75, 108, 183, 0.1);
            border-color: #4b6cb7;
        }
        .btn-primary {
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(75, 108, 183, 0.3);
        }
        .footer {
            background: #182848;
            color: white;
            padding: 2rem 0;
            position: relative;
            margin-top: 3rem;
        }
        .footer a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: #c3cfe2;
        }
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .developer-card {
            border-radius: 15px;
            overflow: hidden;
        }
        .developer-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 1rem auto;
            display: block;
        }
        .title1 {
            color: #182848;
            font-weight: 600;
        }
    </style>
    
    <?php if(@$_GET['w'])
    {echo'<script>alert("'.@$_GET['w'].'");</script>';}
    ?>
    
    <script>
    function validateForm() {
        const name = document.forms["form"]["name"].value;
        const college = document.forms["form"]["college"].value;
        const email = document.forms["form"]["email"].value;
        const password = document.forms["form"]["password"].value;
        const cpassword = document.forms["form"]["cpassword"].value;
        
        if (!name) {
            alert("Name must be filled out.");
            return false;
        }
        if (!college) {
            alert("College must be filled out.");
            return false;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }
        
        if (!password) {
            alert("Password must be filled out");
            return false;
        }
        if (password.length < 5 || password.length > 25) {
            alert("Passwords must be 5 to 25 characters long.");
            return false;
        }
        if (password !== cpassword) {
            alert("Passwords must match.");
            return false;
        }
        return true;
    }
    </script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <span class="navbar-brand">Staff Recruitment Online Examination System</span>
            <button class="btn btn-light ms-auto" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="fas fa-sign-in-alt me-2"></i>Sign in
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container main-container">
        <div class="row justify-content-end">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="title1 text-center">Sign Up</h4>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" name="form" action="sign.php?q=account.php" onSubmit="return validateForm()" method="POST">
                            <div class="mb-3">
                                <input id="name" name="name" placeholder="Enter your name" class="form-control" type="text">
                            </div>
                            
                            <div class="mb-3">
                                <select id="gender" name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <input id="college" name="college" placeholder="Enter your college name" class="form-control" type="text">
                            </div>
                            
                            <div class="mb-3">
                                <input id="email" name="email" placeholder="Enter your email-id" class="form-control" type="email">
                            </div>
                            
                            <div class="mb-3">
                                <input id="mob" name="mob" placeholder="Enter your mobile number" class="form-control" type="number">
                            </div>
                            
                            <div class="mb-3">
                                <input id="password" name="password" placeholder="Enter your password" class="form-control" type="password">
                            </div>
                            
                            <div class="mb-3">
                                <input id="cpassword" name="cpassword" placeholder="Confirm Password" class="form-control" type="password">
                            </div>
                            
                            <?php if(@$_GET['q7'])
                            { echo'<p class="text-danger mb-3">'.@$_GET['q7'].'</p>';}?>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Sign Up
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <a href="#" target="_blank">About us</a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#login">Admin Login</a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#developers">Developers</a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="feedback.php" target="_blank">Feedback</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Sign In Modal -->
    <div class="modal fade" id="myModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title1">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="login.php?q=index.php" method="POST">
                        <div class="mb-3">
                            <input id="email" name="email" placeholder="Enter your email-id" class="form-control" type="email">
                        </div>
                        <div class="mb-3">
                            <input id="password" name="password" placeholder="Enter your Password" class="form-control" type="password">
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="user">Log in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Developers Modal -->
    <div class="modal fade" id="developers" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title1">Developers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="image/logo.jpg" class="developer-img" alt="Developer">
                        <h5 class="mt-3">Yunusa Habu</h5>
                        <p class="mb-1"><i class="fas fa-phone me-2"></i>09066371373</p>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i>yunusahabu2207@gmail.com</p>
                        <p class="mb-0"><i class="fas fa-user-graduate me-2"></i>Student</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Login Modal -->
    <div class="modal fade" id="login" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title1">Admin Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="post" action="admin.php?q=index.php">
                        <div class="mb-3">
                            <input type="text" name="uname" maxlength="31" placeholder="Admin user id" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" maxlength="23" placeholder="Password" class="form-control">
                        </div>
                        <div class="text-center">
                            <button type="submit" name="login" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
