<?php session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['uid']==0)) {
  header('location:logout.php');
  } else{

// Get product details for price calculation
$pid=$_GET['bookid'];
$productQuery = mysqli_query($con, "SELECT RentPrice FROM tblproduct WHERE ID='$pid'");
$productData = mysqli_fetch_array($productQuery);
$rentPrice = $productData['RentPrice'];

if(isset($_POST['submit']))
  {
$uid=$_SESSION['uid'];
    $fromdate=$_POST['fromdate'];
    $todate=$_POST['todate'];
    $usedfor=$_POST['usedfor'];
    $quantity=$_POST['quantity'];
    $deladdress=$_POST['deladdress'];
    $mpesaPhone=$_POST['mpesa_phone'];
    $booknumber = mt_rand(100000000, 999999999);
    
    // Calculate total amount
    $date1 = new DateTime($fromdate);
    $date2 = new DateTime($todate);
    $days = $date2->diff($date1)->days + 1; // Include both start and end date
    $totalAmount = $rentPrice * $days * $quantity;
    
   $addproof=$_FILES["image"]["name"];
     $extension = substr($addproof,strlen($addproof)-4,strlen($addproof));
// allowed extensions
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
// Validation for allowed extensions .in_array() function searches an array for a specific value.
if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
}
else
{

$addproof=md5($addproof).$extension;
     move_uploaded_file($_FILES["image"]["tmp_name"],"img/".$addproof);



     $ret=mysqli_query($con,"SELECT * FROM tblbooking where ('$fromdate' BETWEEN date(FromDate) and date(ToDate) || '$todate' BETWEEN date(FromDate) and date(ToDate) || date(FromDate) BETWEEN '$fromdate' and '$todate') and ProductID='$pid'");
     $count=mysqli_num_rows($ret);

  if($count==0){
    $query=mysqli_query($con,"insert into tblbooking(BookingNumber,UserID,ProductID,FromDate,ToDate,UsedFor,Quantity,DeliveryAddress,AddressProof,TotalAmount,PaymentStatus,MpesaPhoneNumber) value('$booknumber','$uid','$pid','$fromdate','$todate','$usedfor','$quantity','$deladdress','$addproof','$totalAmount','Pending','$mpesaPhone')");
    if ($query) {
        // Booking created successfully, now stored for payment processing
        $_SESSION['pending_booking'] = $booknumber;
        $_SESSION['pending_amount'] = $totalAmount;
        $_SESSION['pending_phone'] = $mpesaPhone;
        echo "<script>alert('Booking created! Redirecting to payment...');</script>";
        echo "<script>window.location.href ='book-products.php?bookid=$pid&pay=1&bn=$booknumber&amt=$totalAmount&phone=$mpesaPhone'</script>";
  }
  else
    {
     echo "<script>alert('Something Went Wrong. Please try again.');</script>"; 
    } }else{
        echo "<script>alert('Equipment not available for these days');</script>"; 
    }

  
}
}

 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Agriculture Equipment Rental System || Book Your Product</title>
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
    .payment-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
    }
    .payment-modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 30px;
        border: 1px solid #888;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    .payment-close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .payment-close:hover,
    .payment-close:focus {
        color: #000;
    }
    .payment-status {
        text-align: center;
        padding: 20px;
    }
    .payment-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .payment-success {
        color: #28a745;
        font-size: 60px;
        margin: 20px 0;
    }
    .payment-error {
        color: #dc3545;
        font-size: 60px;
        margin: 20px 0;
    }
    .amount-display {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 15px 0;
        text-align: center;
    }
    .amount-display h3 {
        color: #28a745;
        margin: 0;
        font-size: 28px;
    }
    </style>
  </head>
  <body class="goto-here">

<?php include_once('includes/header.php');?>
    <!-- END nav -->

    <div class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Book Your Product</span></p>
            <h1 class="mb-0 bread">Book Your Product</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section contact-section bg-light">
      <div class="container">
   
        <div class="row block-9">
          
          <div class="col-md-12 order-md-last d-flex">

            <form class="bg-white p-5 contact-form" method="post" enctype="multipart/form-data" id="bookingForm"> 
            
              <div class="form-group">
          <label class="">From Date <abbr title="required" class="required">*</abbr>
                                                </label>
              <input type="date" name="fromdate" id="fromdate" required="true" class="form-control" min="<?php echo date('Y-m-d'); ?>" onchange="calculateAmount()">
              </div>
              <div class="form-group">
                  <label class="">To Date <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="date" name="todate" id="todate" required="true" class="form-control" min="<?php echo date('Y-m-d'); ?>" onchange="calculateAmount()">
              </div>
              <div class="form-group">
               <label class="">Used For<abbr title="required" class="required">*</abbr>
                                                </label>
                                                <select name="usedfor" required="true" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Individual">Individual</option>
                                                    <option value="Corporate">Corporate</option>
                                                </select>
              </div>
              <div class="form-group">
                <label class="">Quantity<abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="number" name="quantity" id="quantity" required="true" class="form-control" min="1" value="1" onchange="calculateAmount()">
              </div>
              
              <!-- Amount Display -->
              <div class="amount-display" id="amountDisplay" style="display: none;">
                  <p style="margin: 0; color: #666; font-size: 14px;">Total Amount to Pay:</p>
                  <h3>KSH <span id="totalAmount">0</span></h3>
                  <small style="color: #999;">
                      <span id="daysCount">0</span> day(s) × <span id="qtyCount">1</span> unit(s) × KSH <?php echo number_format($rentPrice, 2); ?>/day
                  </small>
              </div>
              
              <div class="form-group">
                <label class="">Delivery Address <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <textarea name="deladdress" required="true"  class="form-control"></textarea>
              </div>
              <div class="form-group">
                <label class="">Delivery Address Proof<abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="file" name="image" required="true" class="form-control">
              </div>
              
              <!-- M-Pesa Phone Number -->
              <div class="form-group">
                <label class="">M-Pesa Phone Number <abbr title="required" class="required">*</abbr></label>
                <input type="tel" name="mpesa_phone" id="mpesa_phone" required="true" class="form-control" placeholder="e.g., 0712345678 or 254712345678" pattern="^(254|0)[17][0-9]{8}$" title="Enter a valid Kenyan phone number">
                <small class="form-text text-muted">Enter your M-Pesa registered phone number (Safaricom)</small>
              </div>

              
              <div class="form-group">
                <input type="submit" name="submit" value="Book & Pay with M-Pesa" class="btn btn-primary py-3 px-5">
              </div>
            </form>
          
          </div>

          
        </div>
      </div>
    </section>
    
    <!-- M-Pesa Payment Modal -->
    <div id="paymentModal" class="payment-modal">
        <div class="payment-modal-content">
            <span class="payment-close" onclick="closePaymentModal()">&times;</span>
            <div class="payment-status" id="paymentStatus">
                <div class="payment-spinner"></div>
                <h4>Processing Payment...</h4>
                <p>Please check your phone and enter your M-Pesa PIN</p>
                <p style="font-size: 12px; color: #999; margin-top: 15px;">Do not close this window</p>
            </div>
        </div>
    </div>

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
  
  <script>
  // Rent price per day from PHP
  const rentPricePerDay = <?php echo $rentPrice; ?>;
  
  // Calculate total amount
  function calculateAmount() {
      const fromDate = document.getElementById('fromdate').value;
      const toDate = document.getElementById('todate').value;
      const quantity = parseInt(document.getElementById('quantity').value) || 1;
      
      if (fromDate && toDate) {
          const date1 = new Date(fromDate);
          const date2 = new Date(toDate);
          
          if (date2 < date1) {
              alert('End date must be after start date');
              document.getElementById('todate').value = '';
              document.getElementById('amountDisplay').style.display = 'none';
              return;
          }
          
          const timeDiff = date2.getTime() - date1.getTime();
          const days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Include both dates
          
          const totalAmount = rentPricePerDay * days * quantity;
          
          document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
          document.getElementById('daysCount').textContent = days;
          document.getElementById('qtyCount').textContent = quantity;
          document.getElementById('amountDisplay').style.display = 'block';
      }
  }
  
  // Check if payment needs to be initiated
  <?php if(isset($_GET['pay']) && $_GET['pay'] == 1): ?>
  window.onload = function() {
      initiatePayment('<?php echo $_GET['bn']; ?>', '<?php echo $_GET['amt']; ?>', '<?php echo $_GET['phone']; ?>');
  };
  <?php endif; ?>
  
  // Initiate M-Pesa STK Push
  function initiatePayment(bookingNumber, amount, phone) {
      // Show payment modal
      document.getElementById('paymentModal').style.display = 'block';
      
      // Send STK Push request
      fetch('includes/mpesa-stk-push.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({
              booking_number: bookingNumber,
              amount: amount,
              phone: phone
          })
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // STK Push sent, start checking payment status
              document.getElementById('paymentStatus').innerHTML = `
                  <div class="payment-spinner"></div>
                  <h4>Payment Request Sent!</h4>
                  <p>Please check your phone and enter your M-Pesa PIN</p>
                  <p style="font-size: 14px; color: #666; margin-top: 10px;">Booking Number: <strong>${bookingNumber}</strong></p>
                  <p style="font-size: 12px; color: #999;">Waiting for payment confirmation...</p>
              `;
              
              // Start polling for payment status
              checkPaymentStatus(bookingNumber, 0);
          } else {
              // Error sending STK Push
              document.getElementById('paymentStatus').innerHTML = `
                  <div class="payment-error">✗</div>
                  <h4>Payment Request Failed</h4>
                  <p style="color: #dc3545;">${data.message}</p>
                  <button class="btn btn-primary mt-3" onclick="closePaymentModal()">Close</button>
              `;
          }
      })
      .catch(error => {
          document.getElementById('paymentStatus').innerHTML = `
              <div class="payment-error">✗</div>
              <h4>Connection Error</h4>
              <p style="color: #dc3545;">Unable to connect to payment server. Please try again.</p>
              <button class="btn btn-primary mt-3" onclick="closePaymentModal()">Close</button>
          `;
      });
  }
  
  // Check payment status
  function checkPaymentStatus(bookingNumber, attempts) {
      if (attempts >= 30) { // Stop after 30 attempts (60 seconds)
          document.getElementById('paymentStatus').innerHTML = `
              <div class="payment-error">⏱</div>
              <h4>Payment Timeout</h4>
              <p>Payment verification timed out. Please check your M-Pesa messages.</p>
              <p style="font-size: 14px;">Booking Number: <strong>${bookingNumber}</strong></p>
              <button class="btn btn-primary mt-3" onclick="window.location.href='my-booking.php'">View My Bookings</button>
          `;
          return;
      }
      
      setTimeout(() => {
          fetch(`includes/mpesa-status.php?booking_number=${bookingNumber}`)
          .then(response => response.json())
          .then(data => {
              if (data.status === 'Completed') {
                  // Payment successful
                  document.getElementById('paymentStatus').innerHTML = `
                      <div class="payment-success">✓</div>
                      <h4>Payment Successful!</h4>
                      <p style="color: #28a745;">Your booking has been confirmed.</p>
                      <p style="font-size: 14px;">Receipt Number: <strong>${data.receipt_number}</strong></p>
                      <p style="font-size: 14px;">Booking Number: <strong>${bookingNumber}</strong></p>
                      <button class="btn btn-success mt-3" onclick="window.location.href='my-booking.php'">View My Bookings</button>
                  `;
              } else if (data.status === 'Failed') {
                  // Payment failed
                  document.getElementById('paymentStatus').innerHTML = `
                      <div class="payment-error">✗</div>
                      <h4>Payment Failed</h4>
                      <p style="color: #dc3545;">${data.message}</p>
                      <p style="font-size: 14px;">Booking Number: <strong>${bookingNumber}</strong></p>
                      <button class="btn btn-primary mt-3" onclick="window.location.href='my-booking.php'">View My Bookings</button>
                  `;
              } else {
                  // Still pending, check again
                  checkPaymentStatus(bookingNumber, attempts + 1);
              }
          })
          .catch(error => {
              // Continue checking on error
              checkPaymentStatus(bookingNumber, attempts + 1);
          });
      }, 2000); // Check every 2 seconds
  }
  
  // Close payment modal
  function closePaymentModal() {
      document.getElementById('paymentModal').style.display = 'none';
      window.location.href = 'my-booking.php';
  }
  
  // Close modal when clicking outside
  window.onclick = function(event) {
      const modal = document.getElementById('paymentModal');
      if (event.target == modal) {
          // Don't allow closing while payment is processing
          return false;
      }
  }
  </script>
    
  </body>
</html><?php }  ?>