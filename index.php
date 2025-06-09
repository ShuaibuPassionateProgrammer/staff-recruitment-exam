<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Recruitment Online Examination System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <!-- Custom fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animation library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.9)), url('./image/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top right, rgba(41, 196, 101, 0.1), transparent 50%);
            pointer-events: none;
        }

        .hero-section {
            padding: 6rem 0;
            position: relative;
        }

        .main-title {
            color: white;
            font-weight: 700;
            font-size: 3.2rem;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            line-height: 1.3;
        }

        .subtitle {
            color: #e0e0e0;
            font-size: 1.3rem;
            margin-bottom: 3.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            opacity: 0.9;
        }

        .get-started-btn {
            padding: 1.2rem 3rem;
            font-size: 1.2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 50px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
        }

        .get-started-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #20c997, #28a745);
            transition: all 0.4s ease;
            z-index: -1;
            opacity: 0;
        }

        .get-started-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
        }

        .get-started-btn:hover::before {
            opacity: 1;
        }

        .btn-icon {
            transition: transform 0.3s ease;
            margin-left: 8px;
        }

        .get-started-btn:hover .btn-icon {
            transform: translateX(5px);
        }

        .container {
            max-width: 1200px;
            position: relative;
        }

        /* Decorative elements */
        .decoration {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            filter: blur(1px);
        }

        .decoration-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
        }

        .decoration-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 4rem 0;
            }
            
            .main-title {
                font-size: 2.2rem;
            }
            
            .subtitle {
                font-size: 1.1rem;
                padding: 0 1rem;
            }
            
            .get-started-btn {
                padding: 1rem 2rem;
                font-size: 1.1rem;
            }
        }
    </style>
    
    <?php if(@$_GET['w'])
    {echo'<script>alert("'.@$_GET['w'].'");</script>';}
    ?>
</head>
<body>
    <div class="decoration decoration-1"></div>
    <div class="decoration decoration-2"></div>
    
    <div class="container">
        <div class="hero-section text-center" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="main-title">Welcome to Staff Recruitment<br>Online Examination System</h1>
            <p class="subtitle">Streamline your recruitment process with our advanced online examination platform. Experience a seamless and efficient way to evaluate candidates.</p>
            <a href="index1.php" class="btn btn-success get-started-btn">
                Get Started
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right btn-icon" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <!-- Animation library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
