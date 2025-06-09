<?php
include_once 'dbConnection.php';
include_once 'admin_middleware.php';

// Verify admin session
verifyAdminSession();

// Get admin info
$email = $_SESSION['email'];
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Staff Recruitment Online Examination System</title>
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
            background: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
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
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1.5rem;
        }
        .dashboard-stats {
            padding: 2rem 0;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #4b6cb7;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 600;
            color: #182848;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            font-weight: 600;
            color: #182848;
            border-bottom-width: 1px;
        }
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dash.php?q=0">
                <i class="fas fa-shield-alt me-2"></i>Admin Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user me-2"></i>Welcome, <?php echo sanitizeOutput($name); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="btn btn-light ms-2">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sub Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <div class="collapse navbar-collapse" id="subNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (@$_GET['q']==0)?'active':''; ?>" href="dash.php?q=0">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (@$_GET['q']==1)?'active':''; ?>" href="dash.php?q=1">
                            <i class="fas fa-users me-2"></i>Candidates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (@$_GET['q']==2)?'active':''; ?>" href="dash.php?q=2">
                            <i class="fas fa-chart-bar me-2"></i>Rankings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (@$_GET['q']==3)?'active':''; ?>" href="dash.php?q=3">
                            <i class="fas fa-comments me-2"></i>Feedback
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-file-alt me-2"></i>Exams
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="dash.php?q=4">
                                    <i class="fas fa-plus me-2"></i>Add Exam
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="dash.php?q=5">
                                    <i class="fas fa-trash me-2"></i>Remove Exam
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <?php
        // Dashboard Overview
        if(@$_GET['q'] == 0) {
            // Get statistics
            $total_users = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM user"))[0];
            $total_exams = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM quiz"))[0];
            $total_questions = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM questions"))[0];
            $total_feedback = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM feedback"))[0];
        ?>
        <div class="dashboard-stats">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-users stat-icon"></i>
                        <div class="stat-number"><?php echo $total_users; ?></div>
                        <div class="stat-label">Total Candidates</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-file-alt stat-icon"></i>
                        <div class="stat-number"><?php echo $total_exams; ?></div>
                        <div class="stat-label">Total Exams</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-question-circle stat-icon"></i>
                        <div class="stat-number"><?php echo $total_questions; ?></div>
                        <div class="stat-label">Total Questions</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-comments stat-icon"></i>
                        <div class="stat-number"><?php echo $total_feedback; ?></div>
                        <div class="stat-label">Total Feedback</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Recent Exams</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Marks</th>
                            <th>Time Limit</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC LIMIT 5");
                        while($row = mysqli_fetch_array($result)) {
                            echo '<tr>
                                    <td>'.sanitizeOutput($row['title']).'</td>
                                    <td>'.$row['total'].'</td>
                                    <td>'.$row['sahi']*$row['total'].'</td>
                                    <td>'.$row['time'].' min</td>
                                    <td>'.date('d M Y', strtotime($row['date'])).'</td>
                                  </tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } 

        // Candidates List
        if(@$_GET['q'] == 1) {
        ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manage Candidates</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" id="searchCandidate" placeholder="Search candidates...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="candidatesTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Exam Venue</th>
                            <th>Mobile</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $result = mysqli_query($con, "SELECT * FROM user ORDER BY name");
                        while($row = mysqli_fetch_array($result)) {
                            echo '<tr>
                                    <td>'.sanitizeOutput($row['name']).'</td>
                                    <td>'.sanitizeOutput($row['email']).'</td>
                                    <td>'.sanitizeOutput($row['gender']).'</td>
                                    <td>'.sanitizeOutput($row['college']).'</td>
                                    <td>'.sanitizeOutput($row['mob']).'</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-action" onclick="deleteUser(\''.$row['email'].'\')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                  </tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php }

        // Rankings
        if(@$_GET['q'] == 2) {
        ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Candidate Rankings</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Exam Venue</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $q = mysqli_query($con, "SELECT * FROM rank ORDER BY score DESC");
                        $c = 1;
                        while($row = mysqli_fetch_array($q)) {
                            $e = $row['email'];
                            $s = $row['score'];
                            $q12 = mysqli_query($con, "SELECT * FROM user WHERE email='$e'");
                            $row2 = mysqli_fetch_array($q12);
                            echo '<tr>
                                    <td>'.$c++.'</td>
                                    <td>'.sanitizeOutput($row2['name']).'</td>
                                    <td>'.sanitizeOutput($row2['gender']).'</td>
                                    <td>'.sanitizeOutput($row2['college']).'</td>
                                    <td>'.$s.'</td>
                                  </tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php }

        // Feedback
        if(@$_GET['q'] == 3) {
            if(isset($_GET['fid'])) {
                $id = $_GET['fid'];
                $result = mysqli_query($con, "SELECT * FROM feedback WHERE id='$id'");
                $row = mysqli_fetch_array($result);
                ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <a href="dash.php?q=3" class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            Feedback Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <h4><?php echo sanitizeOutput($row['subject']); ?></h4>
                        <p class="text-muted">
                            By <?php echo sanitizeOutput($row['name']); ?> 
                            (<?php echo sanitizeOutput($row['email']); ?>) 
                            on <?php echo date('d M Y, h:i A', strtotime($row['date'] . ' ' . $row['time'])); ?>
                        </p>
                        <hr>
                        <div class="feedback-content">
                            <?php echo nl2br(sanitizeOutput($row['feedback'])); ?>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Feedback Management</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>From</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $result = mysqli_query($con, "SELECT * FROM feedback ORDER BY date DESC");
                                while($row = mysqli_fetch_array($result)) {
                                    echo '<tr>
                                            <td>'.sanitizeOutput($row['subject']).'</td>
                                            <td>'.sanitizeOutput($row['name']).'<br>
                                                <small class="text-muted">'.sanitizeOutput($row['email']).'</small>
                                            </td>
                                            <td>'.date('d M Y', strtotime($row['date'])).'<br>
                                                <small class="text-muted">'.$row['time'].'</small>
                                            </td>
                                            <td>
                                                <a href="dash.php?q=3&fid='.$row['id'].'" class="btn btn-sm btn-primary btn-action me-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger btn-action" onclick="deleteFeedback('.$row['id'].')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                          </tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            }
        }

        // Add Exam
        if(@$_GET['q'] == 4) {
            if(!isset($_GET['step'])) $_GET['step'] = 1;
            $step = $_GET['step'];
            
            if($step == 1) {
        ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Exam</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="update.php?q=addquiz">
                    <div class="mb-3">
                        <label class="form-label">Exam Title</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Number of Questions</label>
                            <input type="number" name="total" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time Limit (minutes)</label>
                            <input type="number" name="time" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Marks for Right Answer</label>
                            <input type="number" name="right" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Marks for Wrong Answer</label>
                            <input type="number" name="wrong" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Exam
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
            } else if($step == 2) {
                $eid = $_GET['eid'];
                $n = $_GET['n'];
                $total = $_GET['total'] ?? $n;
        ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add Question <?php echo $n; ?> of <?php echo $total; ?></h5>
                <div class="progress" style="width: 200px;">
                    <div class="progress-bar" role="progressbar" 
                         style="width: <?php echo ($n/$total*100); ?>%" 
                         aria-valuenow="<?php echo $n; ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="<?php echo $total; ?>">
                        <?php echo $n; ?>/<?php echo $total; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="update.php?q=addqns&eid=<?php echo $eid; ?>&n=<?php echo $n; ?>&total=<?php echo $total; ?>">
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <textarea name="qns" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Options</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">A</span>
                                    <input type="text" name="a" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">B</span>
                                    <input type="text" name="b" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">C</span>
                                    <input type="text" name="c" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">D</span>
                                    <input type="text" name="d" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correct Answer</label>
                        <select name="ans" class="form-select" required>
                            <option value="">Select correct option</option>
                            <option value="a">Option A</option>
                            <option value="b">Option B</option>
                            <option value="c">Option C</option>
                            <option value="d">Option D</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <?php if($n == $total) { ?>
                                <i class="fas fa-check me-2"></i>Finish
                            <?php } else { ?>
                                <i class="fas fa-arrow-right me-2"></i>Next Question
                            <?php } ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
            }
        }

        // Remove Exam
        if(@$_GET['q'] == 5) {
        ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Manage Exams</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Marks</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC");
                        while($row = mysqli_fetch_array($result)) {
                            echo '<tr>
                                    <td>'.sanitizeOutput($row['title']).'</td>
                                    <td>'.$row['total'].'</td>
                                    <td>'.$row['sahi']*$row['total'].'</td>
                                    <td>'.$row['time'].' min</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-action" onclick="deleteExam(\''.$row['eid'].'\')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                  </tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Search functionality for candidates
        document.getElementById('searchCandidate')?.addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('#candidatesTable tbody tr');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        });

        // Delete user confirmation
        function deleteUser(email) {
            if(confirm('Are you sure you want to delete this candidate?')) {
                window.location.href = `update.php?demail=${email}`;
            }
        }

        // Delete feedback confirmation
        function deleteFeedback(id) {
            if(confirm('Are you sure you want to delete this feedback?')) {
                window.location.href = `update.php?fdid=${id}`;
            }
        }

        // Delete exam confirmation
        function deleteExam(eid) {
            if(confirm('Are you sure you want to delete this exam? This will also delete all related questions and results.')) {
                window.location.href = `update.php?q=rmquiz&eid=${eid}`;
            }
        }
    </script>
</body>
</html>
