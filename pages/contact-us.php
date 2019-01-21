<?php session_start(); ?>
<?php include("../config.php");?>
<?php include_once("../includes/header.php"); ?>

<?php
  $account_type = 0;
  if(isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] == 1)
  {
    $account_type = 1;
  }
  else if(isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] == 2)
  {
    $account_type = 2;
  }
  else if(isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] == 3)
  {
    $account_type = 3;
  }
  
?>

<body> 

    <?php include_once("../includes/navbar.php"); ?>

    <div class="info-text d-md-none">
      <h4>Contact Us</h4>
    </div>

    <div class="container">
      <div class="vertical-align">
        <div class="col-sm-12 col-md-8 col-lg-6 mx-auto">
          <form action="#" class="form">
              <h5 class="text-center">We'd <i class="fas fa-heart"></i> to help</h5>
              <p style="font-size: 15px;" class="text-center">We like to have fun while learning, and try to be as open-minded as we can. Feel free to say hello</p>
              <div class="input-group mb-2">
                  <div class="input-group-prepend">
                      <div class="input-group-text"><i class="fas fa-user"></i></div>
                  </div>
                  <input class="form-control" type="text" name="name" placeholder ="Name" required>
              </div>
              <div class="input-group mb-2">
                  <div class="input-group-prepend">
                      <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                  </div>
                  <input class="form-control" type="text" name="email" placeholder ="Email" required>
              </div>
              <div class="input-group mb-2">
                  <div class="input-group-prepend">
                      <div class="input-group-text"><i class="fas fa-phone"></i></div>
                  </div>
                  <input class="form-control" type="text" name="number" placeholder ="Phone" required>
              </div>
              <div class="input-group mb-2">
                  <div class="input-group-prepend text-white">
                      <span class="input-group-text"> <i class="fas fa-pencil-alt"></i></span>
                  </div>
                  <textarea type="text" placeholder="Message" rows="5" class="form-control" ></textarea>
              </div>
              <div class="form-group">
                  <input type="button" class="btn btn-block btn-outline-dark" value="Submit">
              </div>
          </form>
        </div>
      </div>
    </div>	

  <?php include_once("../includes/footer.php"); ?>
