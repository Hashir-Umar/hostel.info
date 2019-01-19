<?php include("../config.php");?>
<?php include_once("../includes/header.php"); ?>
<body>

    <nav class="navbar navbar-expand-md py-4">
        <div class="wrapper">
            <span class="logo ml-5">HOSTEL</span>
        </div>
    </nav>

    <div class="container">
        <div class="vertical-align">
        <h1 class="text-center mb-4"> Log<span>in</span>  </h1>
        <form action="#">
            <div class="row">
                <div class="col-sm-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                        </div>
                        <input class="form-control" type="text" name="email" placeholder ="Enter your Email" required>
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-key"></i></div>
                        </div>
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                    <button id=btn type="submit" class="btn btn-block btn-outline-dark  mb-2"> Login </button>
                    <div class="lables mb-2">
                        <p> <a href="forget-password.php"> <i class="fas fa-key"></i> Forgot your password?</a></p>
                        <p> <a href="signup.php"> <i class="fas fa-sign-in-alt"></i> Signup for free</a></p>
                    </div>
                    <a href="../index.php"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back to Home</a>
                </div>
            </div>
        
        </form>
    </div>
</div>

    <footer>
        <div class='container text-center py-4'>
            All Rights Reserved. <a href='#' class="text-muted"> hostel.info </a>  &copy; 2018
        </div>
    </footer>

<?php include_once("../includes/footer.php"); ?>