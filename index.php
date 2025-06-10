<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Staff Recruitment Online Examination System</title>
<link rel="stylesheet" href="css/bootstrap.min.css"/>
<link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/font.css">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<style>
body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-family: 'Roboto', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
}

.main-container {
    width: 100%;
    max-width: 1200px;
    padding: 20px;
}

.welcome-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 60px 40px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.2);
    text-align: center;
    animation: fadeInUp 1s ease-out;
}

.welcome-title {
    color: white;
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    line-height: 1.2;
}

.welcome-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.3rem;
    font-weight: 300;
    margin-bottom: 40px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.get-started-btn {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    padding: 18px 50px;
    font-size: 1.2rem;
    font-weight: 600;
    border-radius: 50px;
    color: white;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.get-started-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(40, 167, 69, 0.4);
    background: linear-gradient(45deg, #218838, #1ea085);
    color: white;
    text-decoration: none;
}

.feature-icons {
    margin: 40px 0;
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
}

.feature-item {
    color: rgba(255, 255, 255, 0.8);
    text-align: center;
    padding: 20px;
}

.feature-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 24px;
    transition: all 0.3s ease;
}

.feature-icon:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.feature-text {
    font-size: 0.9rem;
    font-weight: 400;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .welcome-title {
        font-size: 2.5rem;
    }
    
    .welcome-card {
        padding: 40px 20px;
        margin: 20px;
    }
    
    .feature-icons {
        gap: 20px;
    }
    
    .get-started-btn {
        padding: 15px 40px;
        font-size: 1.1rem;
    }
}

@media (max-width: 480px) {
    .welcome-title {
        font-size: 2rem;
    }
    
    .welcome-subtitle {
        font-size: 1.1rem;
    }
}
</style>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>

<script>
function validateForm() {
    var y = document.forms["form"]["name"].value;
    var letters = /^[A-Za-z]+$/;
    if (y == null || y == "") {
        alert("Name must be filled out.");
        return false;
    }
    var z = document.forms["form"]["college"].value;
    if (z == null || z == "") {
        alert("college must be filled out.");
        return false;
    }
    var x = document.forms["form"]["email"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        alert("Not a valid e-mail address.");
        return false;
    }
    var a = document.forms["form"]["password"].value;
    if(a == null || a == ""){
        alert("Password must be filled out");
        return false;
    }
    if(a.length<5 || a.length>25){
        alert("Passwords must be 5 to 25 characters long.");
        return false;
    }
    var b = document.forms["form"]["cpassword"].value;
    if (a!=b){
        alert("Passwords must match.");
        return false;
    }
}
</script>
</head>
<body>
<div class="main-container">
    <div class="welcome-card">
        <h1 class="welcome-title">Staff Recruitment</h1>
        <p class="welcome-subtitle">Online Examination System</p>
        
        <div class="feature-icons">
            <div class="feature-item">
                <div class="feature-icon">📝</div>
                <div class="feature-text">Online Testing</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">⚡</div>
                <div class="feature-text">Fast & Secure</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">📊</div>
                <div class="feature-text">Instant Results</div>
            </div>
        </div>
        
        <a href="index1.php" class="get-started-btn">Get Started</a>
    </div>
</div>
</body>
</html>