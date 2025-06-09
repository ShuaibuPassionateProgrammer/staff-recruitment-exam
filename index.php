<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Staff Recruitment Online Examination System </title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css">
 <link  rel="stylesheet" href="css/font.css">
 <style>
  #bg {
    background-image: url(./image/bg.jpg);
  }
 </style>
 <script src="js/jquery.js" type="text/javascript"></script>

  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<?php if(@$_GET['w'])
{echo'<script>alert("'.@$_GET['w'].'");</script>';}
?>
<script>
function validateForm() {var y = document.forms["form"]["name"].value;	var letters = /^[A-Za-z]+$/;if (y == null || y == "") {alert("Name must be filled out.");return false;}var z =document.forms["form"]["college"].value;if (z == null || z == "") {alert("college must be filled out.");return false;}var x = document.forms["form"]["email"].value;var atpos = x.indexOf("@");
var dotpos = x.lastIndexOf(".");if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {alert("Not a valid e-mail address.");return false;}var a = document.forms["form"]["password"].value;if(a == null || a == ""){alert("Password must be filled out");return false;}if(a.length<5 || a.length>25){alert("Passwords must be 5 to 25 characters long.");return false;}
var b = document.forms["form"]["cpassword"].value;if (a!=b){alert("Passwords must match.");return false;}}
</script>


</head>
<body id="bg" style="background: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url(./image/bg.jpg) no-repeat center center fixed; background-size:cover;">
<div class="container" style="margin-top: 10vh;">
  <div class="row justify-content-center">
    <h1 style="color: #fff; font-weight: bold; text-align: center; text-shadow: 1px 2px 8px #000;">Welcome to <span style="color:#ffbb33;">Staff Recruitment Online Examination System</span></h1>
  </div>
  <div class="row justify-content-center mt-5">
    <div class="col-md-12 mt-5" style="margin-top:60px;">
      <center>
        <a href="index1.php" class="btn btn-success btn-lg" style="padding: 15px 40px; font-size: 1.5em; border-radius: 8px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">Get Started</a>
      </center>
    </div>
  </div>
</div>

</body>
</html>
