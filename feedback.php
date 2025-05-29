<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Recruitment Online Examination System - Feedback</title>
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
            background: rgba(255, 255, 255, 0.9);
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
        .title1 {
            color: #182848;
            font-weight: 600;
        }
        .feedback-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .email-link {
            color: #4b6cb7;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .email-link:hover {
            color: #182848;
            text-decoration: underline;
        }
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        .success-message {
            color: #28a745;
            background: rgba(40, 167, 69, 0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
    </style>

    <?php if(@$_GET['w'])
    {echo'<script>alert("'.@$_GET['w'].'");</script>';}
    ?>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <span class="navbar-brand">Staff Recruitment Online Examination System</span>
            <div class="ms-auto">
                <?php
                include_once 'dbConnection.php';
                session_start();
                if((!isset($_SESSION['email']))){
                    echo '<button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#myModal">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign in
                          </button>';
                } else {
                    echo '<a href="logout.php?q=feedback.php" class="btn btn-light me-2">
                            <i class="fas fa-sign-out-alt me-2"></i>Sign out
                          </a>';
                }
                ?>
                <a href="index.php" class="btn btn-light">
                    <i class="fas fa-home me-2"></i>Home
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center title1 mb-0">Feedback / Report a Problem</h2>
                    </div>
                    <div class="card-body">
                        <?php if(@$_GET['q']) {
                            echo '<div class="success-message">
                                    <i class="fas fa-check-circle me-2"></i>'.@$_GET['q'].'
                                  </div>';
                        } else { ?>
                            <div class="mb-4">
                                <p class="mb-2">You can send us your feedback through e-mail:</p>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-envelope me-2"></i>
                                    <a href="mailto:shuaibuibrahimisa2020@gmail.com" class="email-link">shuaibuibrahimisa2020@gmail.com</a>
                                </div>
                                <p class="mb-4">Or you can directly submit your feedback by filling the form below:</p>
                            </div>

                            <form method="post" action="feed.php?q=feedback.php">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Subject</label>
                                        <input type="text" name="subject" class="form-control" placeholder="Enter subject" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Feedback</label>
                                    <textarea name="feedback" class="form-control" placeholder="Write your feedback here..." required></textarea>
                                </div>

                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                                    </button>
                                </div>
                            </form>
                        <?php } ?>
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
                    <a href="feedback.php">Feedback</a>
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
                            <input id="email" name="email" placeholder="Enter your email-id" class="form-control" type="email" required>
                        </div>
                        <div class="mb-3">
                            <input id="password" name="password" placeholder="Enter your Password" class="form-control" type="password" required>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Log in
                            </button>
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
                        <img src="image/CAM00121" class="developer-img mb-3" alt="Developer" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                        <h5 class="mb-3">Adams Muhammad Kabir</h5>
                        <p class="mb-2"><i class="fas fa-phone me-2"></i>07089599250</p>
                        <p class="mb-2"><i class="fas fa-envelope me-2"></i>kabiradam4real@gmail.com</p>
                        <p class="mb-0"><i class="fas fa-building me-2"></i>DigiGleeTech</p>
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
                            <input type="text" name="uname" maxlength="20" placeholder="Admin user id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" maxlength="15" placeholder="Password" class="form-control" required>
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
