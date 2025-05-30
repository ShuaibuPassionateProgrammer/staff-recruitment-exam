<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Recruitment Online Examination System - Account</title>
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
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .main-container {
            padding: 2rem 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1.5rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            font-weight: 600;
            color: #182848;
            border-bottom-width: 1px;
        }
        .table td {
            vertical-align: middle;
        }
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
            border: none;
        }
        .btn-success {
            background: linear-gradient(90deg, #28a745 0%, #218838 100%);
            border: none;
        }
        .btn-danger {
            background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
            border: none;
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
        .quiz-panel {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .quiz-question {
            font-size: 1.2rem;
            color: #182848;
            margin-bottom: 1.5rem;
        }
        .quiz-options {
            margin-left: 1rem;
        }
        .quiz-option {
            margin-bottom: 1rem;
        }
        .result-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }
        .score-card {
            text-align: center;
            padding: 2rem;
            background: rgba(75, 108, 183, 0.1);
            border-radius: 15px;
            margin-bottom: 1rem;
        }
        .score-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #182848;
        }
        .user-welcome {
            color: white;
            font-weight: 500;
        }
    </style>

    <?php if(@$_GET['w'])
    {echo'<script>alert("'.@$_GET['w'].'");</script>';}
    ?>
</head>
<?php
    include_once 'dbConnection.php';
?>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Staff Recruitment Online Examination System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php
                        include_once 'dbConnection.php';
                        session_start();
                        if(!(isset($_SESSION['email']))){
                            header("location:index.php");
                            exit();
                        } else {
                            $name = $_SESSION['name'];
                            $email = $_SESSION['email'];
                            echo '<li class="nav-item">
                                    <span class="user-welcome me-3">
                                        <i class="fas fa-user me-2"></i>Hello, '.$name.'
                                    </span>
                                  </li>
                                  <li class="nav-item">
                                    <a href="logout.php?q=account.php" class="btn btn-light">
                                        <i class="fas fa-sign-out-alt me-2"></i>Sign out
                                    </a>
                                  </li>';
                        //}
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sub Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php if(@$_GET['q']==1) echo'active'; ?>" href="account.php?q=1">
                            <i class="fas fa-home me-2"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(@$_GET['q']==2) echo'active'; ?>" href="account.php?q=2">
                            <i class="fas fa-history me-2"></i>History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(@$_GET['q']==3) echo'active'; ?>" href="account.php?q=3">
                            <i class="fas fa-chart-bar me-2"></i>My Scores
                        </a>
                    </li>
                </ul>
                <form class="d-flex ms-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search exams...">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container main-container">
        <div class="row">
            <div class="col-md-12">
                <?php if(@$_GET['q']==1) {
                    $result = mysqli_query($con,"SELECT * FROM quiz ORDER BY date DESC") or die('Error');
                    echo '<div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Available Exams</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Topic</th>
                                            <th>Total Questions</th>
                                            <th>Marks</th>
                                            <th>Time Limit</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                    $c=1;
                    while($row = mysqli_fetch_array($result)) {
                        $title = $row['title'];
                        $total = $row['total'];
                        $sahi = $row['sahi'];
                        $time = $row['time'];
                        $eid = $row['eid'];
                        $q12=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error98');
                        $rowcount=mysqli_num_rows($q12);	
                        if($rowcount == 0){
                            echo '<tr>
                                    <td>'.$c++.'</td>
                                    <td>'.$title.'</td>
                                    <td>'.$total.'</td>
                                    <td>'.$sahi*$total.'</td>
                                    <td>'.$time.' min</td>
                                    <td>
                                        <a href="account.php?q=quiz&step=2&eid='.$eid.'&n=1&t='.$total.'" class="btn btn-danger btn-sm">
                                            <i class="fas fa-play me-2"></i>Start
                                        </a>
                                    </td>
                                  </tr>';
                        } else {
                            echo '<tr>
                                    <td>'.$c++.'</td>
                                    <td>'.$title.' <i class="fas fa-check-circle text-success" title="Completed"></i></td>
                                    <td>'.$total.'</td>
                                    <td>'.$sahi*$total.'</td>
                                    <td>'.$time.' min</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" disabled>
                                            <i class="fas fa-check me-2"></i>Completed
                                        </button>
                                    </td>
                                  </tr>';
                        }
                    }
                    echo '</tbody></table></div></div>';
                }

                if(@$_GET['q']== 'quiz' && @$_GET['step']== 2) {
                    $eid=@$_GET['eid'];
                    $sn=@$_GET['n'];
                    $total=@$_GET['t'];
                    $q=mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid' AND sn='$sn' " );
                    echo '<div class="quiz-panel">
                            <div class="quiz-question">';
                    while($row=mysqli_fetch_array($q)) {
                        $qns=$row['qns'];
                        $qid=$row['qid'];
                        echo '<p class="h5 mb-4">Question '.$sn.': '.$qns.'</p>';
                    }
                    $q=mysqli_query($con,"SELECT * FROM options WHERE qid='$qid' " );
                    echo '<form action="update.php?q=quiz&step=2&eid='.$eid.'&n='.$sn.'&t='.$total.'&qid='.$qid.'" method="POST">
                            <div class="quiz-options">';
                    while($row=mysqli_fetch_array($q)) {
                        $option=$row['option'];
                        $optionid=$row['optionid'];
                        echo '<div class="quiz-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ans" value="'.$optionid.'" id="option'.$optionid.'">
                                    <label class="form-check-label" for="option'.$optionid.'">'.$option.'</label>
                                </div>
                              </div>';
                    }
                    echo '</div><div class="text-center mt-4">';
                    if($sn<$total){
                        echo '<button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right me-2"></i>Next
                              </button>';
                    }else{
                        echo '<button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Submit
                              </button>';
                    }
                    echo '</div></form></div></div>';
                }

                if(@$_GET['q']== 'result' && @$_GET['eid']) {
                    $eid=@$_GET['eid'];
                    $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error157');
                    echo '<div class="card">
                            <div class="card-header">
                                <h4 class="mb-0 text-center">Exam Results</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">';
                    while($row=mysqli_fetch_array($q)) {
                        $s=$row['score'];
                        $w=$row['wrong'];
                        $r=$row['sahi'];
                        $qa=$row['level'];
                        echo '<div class="col-md-3">
                                <div class="score-card">
                                    <div class="score-number">'.$qa.'</div>
                                    <div class="text-muted">Total Questions</div>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="score-card">
                                    <div class="score-number text-success">'.$r.'</div>
                                    <div class="text-muted">Correct Answers</div>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="score-card">
                                    <div class="score-number text-danger">'.$w.'</div>
                                    <div class="text-muted">Wrong Answers</div>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="score-card">
                                    <div class="score-number text-primary">'.$s.'</div>
                                    <div class="text-muted">Final Score</div>
                                </div>
                              </div>';
                    }
                    echo '</div></div></div>';
                }

                if(@$_GET['q']== 2) {
                    $q=mysqli_query($con,"SELECT * FROM history WHERE email='$email' ORDER BY date DESC " )or die('Error197');
                    echo '<div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Exam History</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Quiz</th>
                                            <th>Questions Solved</th>
                                            <th>Right</th>
                                            <th>Wrong</th>
                                            <th>Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                    $c=0;
                    while($row=mysqli_fetch_array($q)) {
                        $eid=$row['eid'];
                        $s=$row['score'];
                        $w=$row['wrong'];
                        $r=$row['sahi'];
                        $qa=$row['level'];
                        $q23=mysqli_query($con,"SELECT title FROM quiz WHERE eid='$eid' " )or die('Error208');
                        while($row=mysqli_fetch_array($q23)) {
                            $title=$row['title'];
                        }
                        $c++;
                        echo '<tr>
                                <td>'.$c.'</td>
                                <td>'.$title.'</td>
                                <td>'.$qa.'</td>
                                <td class="text-success">'.$r.'</td>
                                <td class="text-danger">'.$w.'</td>
                                <td class="text-primary">'.$s.'</td>
                              </tr>';
                    }
                    echo '</tbody></table></div></div>';
                }

                if(@$_GET['q']== 3) {
                    $le = $_SESSION['email'];
                    $q=mysqli_query($con,"SELECT * FROM rank WHERE email='$le'" )or die('Error223'. mysqli_error());
                    echo '<div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">My Performance</h4>
                            </div>
                            <div class="card-body">
                                <div class="score-card">
                                    <i class="fas fa-trophy fa-3x text-warning mb-3"></i>';
                    while($row=mysqli_fetch_array($q)) {
                        $s=$row['time'];
                        echo '<div class="score-number">'.$row['score'].'</div>
                              <div class="text-muted">Overall Score</div>
                              <div class="mt-3">Last Updated: '.date_format(date_create($s), "d F, Y H:i a").'</div>';
                    }
                    echo '</div></div></div>';
                }
                ?>
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

    <!-- Developers Modal -->
    <div class="modal fade" id="developers" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Developers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="image/CAM00121" class="rounded-circle mb-3" alt="Developer" style="width: 150px; height: 150px; object-fit: cover;">
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
                    <h5 class="modal-title">Admin Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="admin.php?q=index.php">
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
