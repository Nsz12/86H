<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
// Include config file
require_once "config.php";
//get information about the user for the database
$id = $_SESSION['id'];

//select statment
$sql = "SELECT * from groups_list where user_id = ".$id ;
//store the result
$result = mysqli_query($connect, $sql);


$sql = "SELECT * from invitation where user_id =". $id ;

$invite = mysqli_query($connect, $sql);

if(isset($_GET["action"])){
    $sql = "DELETE from invitation where group_id = ".$_GET['group']." and user_id = ".$id;
    mysqli_query($connect, $sql);

     if($_GET["action"] == "join"){
        $sql = "SELECT group_name from groups_list where group_id = ".$_GET['group'];
        $res = mysqli_query($connect, $sql);
        $r = $res->fetch_assoc();
       $sql= "INSERT INTO groups_list (user_id, group_id, group_name, image)
        VALUES (".$id.",".$_GET['group']." , '".$r['group_name']."', 'assets/img/sharing-money-2.png')";

    mysqli_query($connect, $sql);
     }

echo '<script>window.location="welcome.php"</script>';

}
 // Close connection
    mysqli_close($connect);
?>


<!DOCTYPE html>
<html  style="background-color: rgb(46,15,123);">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Groups_page</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body  style="background-color: rgb(46,15,123);">
    <nav class="navbar navbar-light navbar-expand-md" style="background-color: rgb(46,15,123);">
        <div class="container-fluid"><img src="assets/img/76dc75b0-7f18-4306-9bc8-32e1641adfc1.jpg" width="70px" height="70px" alt="logo"><a class="navbar-brand" href="#" style="color: rgb(230,255,255);">&nbsp; &nbsp;86H</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <input
                class="border rounded" type="search" placeholder="search">
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav"></ul>
                </div>
                <div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#" style="color: rgb(230,255,255);">Dropdown </a>
            <div class="dropdown-menu dropdown-menu-right" role="menu"><a class="dropdown-item" role="presentation" href="#">SETTING</a><a class="dropdown-item" role="presentation" href="#">JOIN GROUP</a>
              <a class="dropdown-item" role="presentation" onclick="Create_group()">CREATE GROUP</a>
                <a class="dropdown-item" role="presentation" href="logout.php">LOGOUT</a>
            </div>
        </div>
        </div>
    </nav>
    <div role="tablist" id="accordion-1">
        <div class="card">
            <div class="card-header" role="tab" style="background-color: rgb(46,15,123);">
                <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="true" aria-controls="accordion-1 .item-1" href="#accordion-1 .item-1" style="color: rgb(230,255,255);">GROUPS LIST</a></h5>
            </div>
            <div class="collapse show item-1" role="tabpanel" data-parent="#accordion-1" style="color: rgb(255,255,255);background-color: rgb(15,7,67);">
                <div class="card-body">
                    <div class="table-responsive table-borderless" style="background-color: rgb(15,7,67);">
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="text-justify" style="margin: 20px;padding: 20px;">
                                    <?php

                                    if ($result->num_rows > 0) {
                                            // output data of each row
                                            while($row = $result->fetch_assoc()) {

                                             ?>


                                    <td class="text-justify"><a href="ESTRAHA.php?group=<?php echo $row["group_id"];?>&id=<?php echo $row["user_id"];?>" style="color: rgb(230,255,255);font-size: 25px;"><img src="<?php echo $row["image"];?>" width="100px" height="100px" alt="group logo">&nbsp;<?php echo $row["group_name"]?>&nbsp;</a></td>



                                   <?php
                                    }

                                   } else {
                                             echo "<div class='card-body'><p class='card-text' style='color: rgb(230,255,255);'>you are not joined in any group<p><div class='card-body'>";
                                            }


                                            ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab" style="background-color: rgb(46,15,123);">
                <h5 class="mb-0" style="background-color: rgb(46,15,123);"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-2" href="#accordion-1 .item-2" style="color: rgb(230,255,255);">INVITAIONS</a></h5>
            </div>
            <div class="collapse item-2" role="tabpanel" data-parent="#accordion-1" style="background-color: rgb(15,7,67);">
                <div class="card-body">
                     <?php
                    if ($invite->num_rows > 0) {
                                            // output data of each row
                                            while($row2 = $invite->fetch_assoc()) {

                                             ?>
                                            <div>
                                             <label style="color: white"><?php echo  $row2["group_name"] ?></label>
                                             <a class="btn btn-primary"  href="welcome.php?action=join&group=<?php echo $row2["group_id"];?>">Join</a>
                                             <a class="btn btn-primary"  href="welcome.php?action=reject&group=<?php echo $row2["group_id"];?>">Reject</a>
                                             </div>



                      <?php


                      }
                  }else{
                  ?>
                    <p class="card-text" style="color: rgb(230,255,255);">YOU HAVE NO INVITAIONS</p>
                       <?php

                }?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab" style="background-color: rgb(46,15,123);">
                <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-3" href="#accordion-1 .item-3" style="color: rgb(230,255,255);">REQUISTS</a></h5>
            </div>
            <div class="collapse item-3" role="tabpanel" data-parent="#accordion-1" style="color: rgb(230,255,255);background-color: rgb(15,7,67);">
                <div class="card-body">

                    <p class="card-text">YOU HAVE NO REQUISTS</p>



                </div>
            </div>
        </div>
    </div>
    <div class="footer-basic" style="background-color: rgb(46,15,123);">
        <footer>
            <div class="social"><a href="#" style="color: rgb(230,255,255);"><i class="icon ion-social-instagram" style="color: rgb(230,255,255);"></i></a><a href="#"><i class="icon ion-social-snapchat" style="color: rgb(230,255,255);"></i></a><a href="#"><i class="icon ion-social-twitter" style="color: rgb(230,255,255);"></i></a>
                <a
                    href="#" style="color: rgb(230,255,255);"><i class="icon ion-social-facebook"></i></a>
            </div>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">Home</a></li>
                <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">Services</a></li>
                <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">About</a></li>
                <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">Terms</a></li>
                <li class="list-inline-item"><a href="#" style="color: rgb(230,255,255);">Privacy Policy</a></li>
            </ul>
            <p class="copyright" style="color: rgb(255,255,255);"></p>
        </footer>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

<script>
function Create_group() {
  var group = prompt("Please enter the group name");
  if (group != null) {
  <?php
  require_once "config.php";

  $id = $_SESSION['id'];

  $sql= "INSERT INTO groups (group_name, owner) VALUES (echo'"<script>document.writeln(group);</script>"';,$id)";
  mysqli_query($connect, $sql);

  $group_id="SELECT group_id from groups where owner = '$id' and group_name=".echo"'<script>document.writeln(group);</script>'";.";";
  $owner_id="SELECT $owner_id from groups where group_id = ".$group_id."";

  $sql= "INSERT INTO group_users (group_id_fk,user_id_fk,status) VALUES (".$group_id.",".$owner_id.","1");";
  mysqli_query($connect, $sql);

  mysqli_close($connect);

   ?>
  }
}
</script>

</html>
