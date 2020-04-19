 <?php
 // Include config file
 require_once "config.php";

 // Define variables and initialize with empty values
 $username = $email = $password = $confirm_password = "";
 $username_err = $email_err = $password_err = $confirm_password_err = "";

 // Processing form data when form is submitted
 if($_SERVER["REQUEST_METHOD"] == "POST"){

     // Validate username
     if(empty(trim($_POST["username"]))){
         $username_err = "Please enter a username.";
     } else{
         // Prepare a select statement
         $sql = "SELECT id FROM users WHERE username = ?";

         if($stmt = mysqli_prepare($connect, $sql)){
             // Bind variables to the prepared statement as parameters
             mysqli_stmt_bind_param($stmt, "s", $param_username);

             // Set parameters
             $param_username = trim($_POST["username"]);

             // Attempt to execute the prepared statement
             if(mysqli_stmt_execute($stmt)){
                 /* store result */
                 mysqli_stmt_store_result($stmt);

                 if(mysqli_stmt_num_rows($stmt) == 1){
                     $username_err = "This username is already taken.";
                 } else{
                     $username = trim($_POST["username"]);
                 }
             } else{
                 echo "Oops! Something went wrong. Please try again later.";
             }


             // Validate email
             if(empty(trim($_POST["email"]))){
                 $email_err = "Please enter a email.";
             } else{
                 // Prepare a select statement
                 $sql = "SELECT id FROM users WHERE email = ?";

                 if($stmt = mysqli_prepare($connect, $sql)){
                     // Bind variables to the prepared statement as parameters
                     mysqli_stmt_bind_param($stmt, "s", $param_email);

                     // Set parameters
                     $param_email = trim($_POST["email"]);

                     // Attempt to execute the prepared statement
                     if(mysqli_stmt_execute($stmt)){
                         /* store result */
                         mysqli_stmt_store_result($stmt);

                         if(mysqli_stmt_num_rows($stmt) == 1){
                             $email_err = "This email is already used.";
                         } else{
                             $email = trim($_POST["email"]);
                         }
                     } else{
                         echo "Oops! Something went wrong. Please try again later.";
                     }

             // Close statement
             mysqli_stmt_close($stmt);
         }
     }

     // Validate password
     if(empty(trim($_POST["password"]))){
         $password_err = "Please enter a password.";
     } elseif(strlen(trim($_POST["password"])) < 6){
         $password_err = "Password must have atleast 6 characters.";
     } else{
         $password = trim($_POST["password"]);
     }

     // Validate confirm password
     if(empty(trim($_POST["confirm_password"]))){
         $confirm_password_err = "Please confirm password.";
     } else{
         $confirm_password = trim($_POST["confirm_password"]);
         if(empty($password_err) && ($password != $confirm_password)){
             $confirm_password_err = "Password did not match.";
         }
     }

     // Check input errors before inserting in database
     if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){

         // Prepare an insert statement
         $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

         if($stmt = mysqli_prepare($connect, $sql)){
             // Bind variables to the prepared statement as parameters
             mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

             // Set parameters
             $param_username = $username;
             $param_email    = $email;
             $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

             // Attempt to execute the prepared statement
             if(mysqli_stmt_execute($stmt)){
                 // Redirect to login page
                 header("location: login.php");
             } else{
                 echo "Something went wrong. Please try again later.";
             }

             // Close statement
             mysqli_stmt_close($stmt);
         }
     }

     // Close connection
     mysqli_close($link);
 }
 ?>


 <!DOCTYPE html>
 <html style="background-color: rgb(46,15,123);">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
     <title>Create account</title>
     <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
     <link rel="stylesheet" href="assets/css/Footer-Basic.css">
     <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
     <link rel="stylesheet" href="assets/css/styles.css">
 </head>

 <body style="background-color: rgb(46,15,123);">
     <nav class="navbar navbar-light navbar-expand-md" style="background-color: rgb(46,15,123);">
         <div class="container-fluid"><img src="assets/img/76dc75b0-7f18-4306-9bc8-32e1641adfc1.jpg" width="70px" height="70px"><a class="navbar-brand" href="#" style="color: rgb(230,255,255);">&nbsp; &nbsp;86H</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
             <div
                 class="collapse navbar-collapse" id="navcol-1">
                 <ul class="nav navbar-nav">
                     <li class="nav-item" role="presentation"></li>
                     <li class="nav-item" role="presentation"></li>
                     <li class="nav-item" role="presentation"></li>
                 </ul>
         </div>
         </div>
     </nav>
     <div class="register-photo" style="background-color: rgb(15,7,67);">
         <div class="form-container">
             <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="background-color: rgb(52,33,128);">
                 <h2 class="text-center" style="color: rgb(230,255,255);"><strong>Create</strong> an account.</h2>

                 <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                 <input class="form-control" type="text" name="UserName" placeholder="User name" value="<?php echo $username; ?>">
                 <span class="help-block"><?php echo $username_err; ?></span>
                 </div>

                 <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                 <input class="form-control" type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">
                 <span class="help-block"><?php echo $email_err; ?></span>
                 </div>

                 <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                 <input class="form-control" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>">
                 <span class="help-block"><?php echo $password_err; ?></span>
                 </div>

                 <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                 <input class="form-control" type="password" name="password-repeat" placeholder="Password (repeat)" value="<?php echo $confirm_password; ?>">
                 <span class="help-block"><?php echo $confirm_password_err; ?></span>
                 </div>

                 <div class="form-group"><input type="submit" value="Sign Up" class="btn btn-primary btn-block" style="background-color: rgb(137,71,244);">
                 </div><a class="already" href="login.php" style="color: rgb(230,255,255);">You already have an account? Login here.</a>
                 </form>
         </div>
     </div>
     <div class="footer-basic" style="background-color: rgb(46,15,123);">
         <footer>
             <div class="social"><a href="#" style="color: rgb(230,255,255);"><i class="icon ion-social-instagram" style="color: rgb(230,255,255);"></i></a><a href="#" style="color: rgb(230,255,255);"><i class="icon ion-social-snapchat" style="color: rgb(230,255,255);"></i></a>
                 <a
                     href="#" style="color: rgb(230,255,255);"><i class="icon ion-social-twitter" style="color: rgb(230,255,255);"></i></a><a href="#" style="color: rgb(230,255,255);"><i class="icon ion-social-facebook"></i></a></div>
             <ul class="list-inline">
                 <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">Home</a></li>
                 <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">Services</a></li>
                 <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">About</a></li>
                 <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">Terms</a></li>
                 <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">Privacy Policy</a></li>
             </ul>
             <p class="copyright" style="color: rgb(230,255,255);">Company Name © 2017</p>
         </footer>
     </div>
     <script src="assets/js/jquery.min.js"></script>
     <script src="assets/bootstrap/js/bootstrap.min.js"></script>
 </body>

 </html>
