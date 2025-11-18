
    <footer class="ftco-footer ftco-section">
      <div class="container">
        <div class="row">
            <div class="mouse">
                        <a href="#" class="mouse-icon">
                            <div class="mouse-wheel"><span class="ion-ios-arrow-up"></span></div>
                        </a>
                    </div>
        </div>
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">AERS</h2>
              <?php 
 $query=mysqli_query($con,"select * from  tblpage where PageType='aboutus'");
 while ($row=mysqli_fetch_array($query)) {


 ?>
              <p><?php  echo $row['PageDescription'];?>.</p><?php } ?>
             
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-5">
              <h2 class="ftco-heading-2">Menu</h2>
              <ul class="list-unstyled">
                <li><a href="index.php" class="py-2 d-block">Home</a></li>
                <li><a href="shop.php" class="py-2 d-block">Rent</a></li>
                <li><a href="about.php" class="py-2 d-block">About</a></li>
                <li><a href="contact.php" class="py-2 d-block">Contact Us</a></li>

              </ul>
            </div>
          </div>
        
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
                <h2 class="ftco-heading-2">Have a Questions?</h2>
                <div class="block-23 mb-3">
                  <ul>
                    <?php 
 $query=mysqli_query($con,"select * from  tblpage where PageType='contactus'");
 while ($row=mysqli_fetch_array($query)) {


 ?>
                    <li><span class="icon icon-map-marker"></span><span class="text"><?php  echo $row['PageDescription'];?></span></li>
                    <li><span class="icon icon-phone"></span><span class="text">+<?php  echo $row['MobileNumber'];?></span></li>
                    <li><span class="icon icon-envelope"></span><span class="text"><?php  echo $row['Email'];?></span></li><?php } ?>
                  </ul>
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            <p style="color:blue;font-weight: bolder;">Agriculture Equipment Rental System 
                        </p>
          </div>
        </div>
      </div>
    </footer>
    
  

  <!-- loader -->
