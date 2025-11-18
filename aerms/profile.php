
<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['uid']==0)) {
  header('location:logout.php');
  } else{
if(isset($_POST['submit']))
  {
    $uid=$_SESSION['uid'];
    $fname=$_POST['fname'];
    $lname=$_POST['lname'];
    

    $query=mysqli_query($con, "update tbluser set FirstName='$fname',LastName='$lname' where ID='$uid'");


    if ($query) {
    
    echo '<script>alert("Your profile has been updated.")</script>';
  }
  else
    {
      
      echo '<script>alert("Something Went Wrong. Please try again.")</script>';
    }

}

 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Agriculture Equipment Rental System || Signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body class="goto-here">

<?php include_once('includes/header.php');?>
    <!-- END nav -->

    <div class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Profile</span></p>
            <h1 class="mb-0 bread">Profile</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section contact-section bg-light">
      <div class="container">
   
        <div class="row block-9">
          
          <div class="col-md-12 order-md-last d-flex">

            <form class="bg-white p-5 contact-form" method="post">
               <h3>Update Your Profile!!!</h3>
               <?php
$uid=$_SESSION['uid'];
$ret=mysqli_query($con,"select * from  tbluser where ID='$uid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
              <div class="form-group">
                <label style="font-weight: bolder;">First Name <abbr title="required" class="required">*</abbr></label>
                <input type="text" value="<?php  echo $row['FirstName'];?>" id="fname" name="fname" class="form-control">
                
              </div>
              <div class="form-group">
                 <label style="font-weight: bolder;">Last Name <abbr title="required" class="required">*</abbr></label>
                <input type="text" class="form-control" value="<?php  echo $row['LastName'];?>" id="lname" name="lname">
              </div>
              <div class="form-group">
                 <label style="font-weight: bolder;">Email <abbr title="required" class="required">*</abbr></label>
               <input type="email" value="<?php  echo $row['Email'];?>" id="email" name="email" readonly="true" class="form-control">
              </div>
              <div class="form-group">
                <label style="font-weight: bolder;">Mobile Number <abbr title="required" class="required">*</abbr></label>
               <input type="text" value="<?php  echo $row['MobileNumber'];?>" id="mobilenumber" name="mobilenumber" readonly="true" maxlength="10" pattern="[0-9]+" class="form-control">
              </div>
                <?php }?>
              <div class="form-group">
                <input type="submit" name="submit" value="Update" class="btn btn-primary py-3 px-5">
              </div>
            </form>
          
          </div>

          
        </div>
      </div>
    </section>

    <?php include_once('includes/footer.php');?>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html><?php }  ?>