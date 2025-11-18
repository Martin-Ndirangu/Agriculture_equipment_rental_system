<?php
session_start();
error_reporting(0);

include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Agriculture Equipment Rental System|| Home Page</title>
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
    
    <style>
      /* Force background images to display */
      .owl-carousel.home-slider {
        background-image: none !important;
      }
      
      .owl-carousel.home-slider .slider-item {
        background-size: cover !important;
        background-repeat: no-repeat !important;
        background-position: center center !important;
        min-height: 650px !important;
        height: 650px !important;
      }
      
      /* Individual slide backgrounds - using attribute selector */
      .slider-item[style*="bg_1.jpg"],
      .slider-item.slide-1 {
        background: url('images/bg_1.jpg') center center / cover no-repeat !important;
      }
      .slider-item[style*="bg_2.jpg"],
      .slider-item.slide-2 {
        background: url('images/bg_2.jpg') center center / cover no-repeat !important;
      }
      .slider-item[style*="bg_3.jpg"],
      .slider-item.slide-3 {
        background: url('images/bg_3.jpg') center center / cover no-repeat !important;
      }
      .slider-item[style*="about.jpg"],
      .slider-item.slide-4 {
        background: url('images/about.jpg') center center / cover no-repeat !important;
      }
      
      /* Remove the CSS that blocks backgrounds */
      .slider-item {
        background-image: unset !important;
      }
    </style>
  </head>
  <body class="goto-here">
		<?php include_once('includes/header.php');?>

   <section id="home-section" class="hero">
  <div class="home-slider owl-carousel">

    <!-- Slide 1 -->
    <div class="slider-item slide-1" style="background-image: url(images/bg_1.jpg); background-size: cover; background-position: center center; background-repeat: no-repeat;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

          <div class="col-md-12 ftco-animate text-center">
            <h1 class="mb-2">Reliable Agricultural Equipment for Rent</h1>
            <h2 class="subheading mb-4">Tractors, Harvesters, Plows & More — Available Anytime</h2>
            <p><a href="#" class="btn btn-primary">View Equipment</a></p>
          </div>

        </div>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="slider-item slide-2" style="background-image: url(images/bg_2.jpg); background-size: cover; background-position: center center; background-repeat: no-repeat;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

          <div class="col-sm-12 ftco-animate text-center">
            <h1 class="mb-2">Boost Your Farm Productivity</h1>
            <h2 class="subheading mb-4">Affordable, Well-Maintained Equipment for Every Farmer</h2>
            <p><a href="#" class="btn btn-primary">Rent Now</a></p>
          </div>

        </div>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="slider-item slide-3" style="background-image: url(images/bg_3.jpg); background-size: cover; background-position: center center; background-repeat: no-repeat;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

          <div class="col-md-12 ftco-animate text-center">
            <h1 class="mb-2">Modern Farming Solutions</h1>
            <h2 class="subheading mb-4">Innovative Tools and Equipment for Sustainable Agriculture</h2>
            <p><a href="#" class="btn btn-primary">Explore More</a></p>
          </div>

        </div>
      </div>
    </div>

    <!-- Slide 4 -->
    <div class="slider-item slide-4" style="background-image: url(images/about.jpg); background-size: cover; background-position: center center; background-repeat: no-repeat;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

          <div class="col-md-12 ftco-animate text-center">
            <h1 class="mb-2">Expert Support & Service</h1>
            <h2 class="subheading mb-4">Professional Guidance and Maintenance for Your Equipment</h2>
            <p><a href="#" class="btn btn-primary">Contact Us</a></p>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Services Section -->
<section class="ftco-section">
  <div class="container">
    <div class="row no-gutters ftco-services">

      <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
        <div class="media block-6 services mb-md-0 mb-4">
          <div class="icon bg-color-1 active d-flex justify-content-center align-items-center mb-2">
            <span class="flaticon-tractor"></span>
          </div>
          <div class="media-body">
            <h3 class="heading">Wide Equipment Range</h3>
            <span>Tractors, harvesters, planters & more</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
        <div class="media block-6 services mb-md-0 mb-4">
          <div class="icon bg-color-2 d-flex justify-content-center align-items-center mb-2">
            <span class="flaticon-farmer"></span>
          </div>
          <div class="media-body">
            <h3 class="heading">Affordable Rental Rates</h3>
            <span>Flexible pricing for all farmers</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
        <div class="media block-6 services mb-md-0 mb-4">
          <div class="icon bg-color-3 d-flex justify-content-center align-items-center mb-2">
            <span class="flaticon-maintenance"></span>
          </div>
          <div class="media-body">
            <h3 class="heading">Well-Maintained Machines</h3>
            <span>Regularly serviced for top performance</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
        <div class="media block-6 services mb-md-0 mb-4">
          <div class="icon bg-color-4 d-flex justify-content-center align-items-center mb-2">
            <span class="flaticon-customer-service"></span>
          </div>
          <div class="media-body">
            <h3 class="heading">24/7 Customer Support</h3>
            <span>We’re here to assist you anytime</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
    <section class="ftco-section">
    	<div class="container">
				<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
          	<span class="subheading">Featured Products</span>
           <h2 class="mb-4">Our Agricultural Equipment</h2>
      <p>Explore our wide range of reliable and affordable farm machines available for rent — from tractors to harvesters and more.</p>
    </div>
          </div>
        </div>   		
    	</div>
    	<div class="container">
    		<div class="row">
    			 <?php

//Getting default page number
        if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
        // Formula for pagination
        $no_of_records_per_page = 8;
        $offset = ($pageno-1) * $no_of_records_per_page;
        $total_pages_sql = "SELECT COUNT(*) FROM tblproduct";
        $ret1=mysqli_query($con,"select COUNT(*) from  tblproduct");
$total_rows = mysqli_fetch_array($ret1)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);                                                   
 $query=mysqli_query($con,"select * from tblproduct LIMIT $offset, $no_of_records_per_page");
 while ($row=mysqli_fetch_array($query)) {


 ?>
    			<div class="col-md-6 col-lg-3 ftco-animate">
    				<div class="product">
    					<a href="single-product-details.php?viewid=<?php echo $row['ID'];?>" class="img-prod"><img class="img-fluid" src="admin/images/<?php echo $row['Image'];?>" alt="Colorlib Template">
    					
    						<div class="overlay"></div>
    					</a>
    					<div class="text py-3 pb-4 px-3 text-center">
    						<h3><a href="single-product-details.php?viewid=<?php echo $row['ID'];?>"><?php echo $row['ProductName'];?></a></h3>
    						<div class="d-flex">
    							<div class="pricing">
		    						<p class="price"><span class="price-sale">Ksh<?php echo $row['RentPrice'];?>/day</span></p>
		    					</div>
	    					</div>

    					</div>
    				</div>
    			</div><?php } ?>    	
    			
    		</div>
    	</div>
    </section>


    <hr>



	
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
</html>
