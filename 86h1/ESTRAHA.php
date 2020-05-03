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


if(isset($_GET["group"])&&isset($_GET["id"])){
$sql = "SELECT * from to_do_list where user_id = ".$_GET["id"]." and group_id = ".$_GET["group"]." and status = 2";
$my_list = mysqli_query($connect, $sql);
$sql = "SELECT * from to_do_list where group_id = ".$_GET["group"]." and status = 3";
$group_list = mysqli_query($connect, $sql);

$sql = "SELECT * from to_do_list where group_id = ".$_GET["group"]." and status = 1";
$not_assign = mysqli_query($connect, $sql);


if(isset($_GET["action"])){
   

     if($_GET["action"]=="add"){
        
         $sql = "INSERT into to_do_list(group_id, item, status) VALUES (".$_GET["group"].",'".$_GET["item"]."', 1)";
         
         mysqli_query($connect, $sql);

     }else if($_GET["action"]=="pick"){
        $sql = "UPDATE to_do_list SET user_id=".$_GET["id"].", status=2 WHERE item_id=".$_GET["item_id"];
         mysqli_query($connect, $sql);
         
     }else if($_GET["action"]=="check"){
        $sql = "UPDATE to_do_list SET amount=".$_GET["amount"].", status=3 WHERE item_id=".$_GET["item_id"];
         mysqli_query($connect, $sql);
     }
     
     header("location: ESTRAHA.php?group=".$_GET["group"]."&id=".$_GET["id"]);
}


}






// Close connection
    mysqli_close($connect);
    ?>

<!DOCTYPE html>
<html style="background-color: rgb(46,15,123);">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ESTRAHA</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
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
        <div class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#" style="color: rgb(230,255,255);">Dropdown </a>
            <div class="dropdown-menu dropdown-menu-right" role="menu">
                
                <?php

                if($_GET["status"]!=1){

                    ?>
                <a class="dropdown-item" role="presentation" href="#">INVITE</a>
                <?php

            }
            ?>
                <a class="dropdown-item" role="presentation" href="#">LEAVE GROUP</a>
            </div>
        </div>
        </div>
    </nav>
    <div role="tablist" id="accordion-1">
        <div class="card">
            <div class="card-header" role="tab" style="background-color: rgb(46,15,123);">
                <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-1" href="#accordion-1 .item-1" style="color: rgb(230,255,255);">MY TO DO LIST</a></h5>
            </div>
            <div class="collapse item-1" role="tabpanel" data-parent="#accordion-1" style="background-color: rgb(15,7,67);">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                 <?php

                                    if ($my_list->num_rows > 0) {
                                            // output data of each row
                                            while($row_my_list = $my_list->fetch_assoc()) {

                                             ?>
                                <tr>
                                    <td>
                                        <span style="color: rgb(230,255,255);"><?php echo $row_my_list["item"] ?>&nbsp; &nbsp;</span>
                                        <form action="ESTRAHA.php" method="GET">
                                            <input class="border rounded float-right"
                                           name="amount" type="text" placeholder="SET PRICE">
                                           <input type="hidden" name="group" value="<?php echo $_GET['group']?>">
                                            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                                            <input type="hidden" name="action" value="check">
                                            <input type="hidden" name="item_id" value="<?php echo $row_my_list['item_id'] ?>">
                                        <input class="btn btn-link float-right" type="submit" style="background-color: rgb(137,71,244);color: rgb(230,255,255);" value="CHECK">
                                        
                                            </form>
                                        </td>
                                </tr>
                               
                                   <?php
                                    }

                                   } else {
                                             echo "<div class='card-body'><p class='card-text' style='color: rgb(230,255,255);'>THERE ARE NOTHING TO DO<p><div class='card-body'>";
                                            }


                                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab" style="background-color: rgb(46,15,123);">
                <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-2" href="#accordion-1 .item-2" style="color: rgb(230,255,255);">GROUP TO DO LIST</a></h5>
            </div>
            <div class="collapse item-2" role="tabpanel" data-parent="#accordion-1" style="background-color: rgb(15,7,67);">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                 <?php

                                    if ($group_list->num_rows > 0) {
                                            // output data of each row
                                            while($row_group_list = $group_list->fetch_assoc()) {

                                             ?>
                                <tr>
                                    <td>
                                        <span style="color: rgb(230,255,255);"><?php echo $row_group_list["item"] ?></span>
                                        <button class="btn btn-link float-right" type="submit" style="background-color: rgb(137,71,244);color: rgb(230,255,255);">DONE</button>
                                    </td>
                                </tr>
                                <?php
                                    }

                                   } else {
                                             echo "<div class='card-body'><p class='card-text' style='color: rgb(230,255,255);'>THERE ARE NOTHING TO DO<p><div class='card-body'>";
                                            }


                                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab" style="background-color: rgb(46,15,123);">
                <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-3" href="#accordion-1 .item-3" style="color: rgb(230,255,255);">NOT ASSIGNED</a></h5>
            </div>
            <div class="collapse item-3" role="tabpanel" data-parent="#accordion-1" style="background-color: rgb(15,7,67);">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                 <?php

                                    if ($not_assign->num_rows > 0) {
                                            // output data of each row
                                            while($row_not_assign = $not_assign->fetch_assoc()) {

                                             ?>
                                <tr>
                                    <td>
                                        <span style="color: rgb(230,255,255);"><?php echo $row_not_assign["item"]?></span>
                                        <a class="btn btn-link float-right" href="ESTRAHA.php?action=pick&group=<?php echo $_GET['group']?>&id=<?php echo $_GET['id']?>&item_id=<?php echo $row_not_assign["item_id"]?>" style="background-color: rgb(137,71,244);color: rgb(230,255,255);">PICK</a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                 } else {
                                             echo "<div class='card-body'><p class='card-text' style='color: rgb(230,255,255);'>THERE ARE NOTHING TO ASSIGN<p><div class='card-body'>";
                                            }


                                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab" style="background-color: rgb(46,15,123);">
                <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-4" href="#accordion-1 .item-4" style="color: rgb(230,255,255);">ADD TO DO ITEM</a></h5>
            </div>
            <div class="collapse item-4" role="tabpanel" data-parent="#accordion-1" style="background-color: rgb(15,7,67);">
                <div class="card-body">
                    <form action="ESTRAHA.php" method="GET">
                    <input type="text" name="item" placeholder="ADD ITEM">
                    <input type="hidden" name="group" value="<?php echo $_GET['group']?>">
                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    <input type="hidden" name="action" value="add">
                    <input class="btn btn-link float-right" type="submit" style="background-color: rgb(137,71,244);color: rgb(230,255,255);" value="ADD">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-basic" style="background-color: rgb(46,15,123);">
        <footer>
            <div class="social"><a href="#" style="color: rgb(230,255,255);"><i class="icon ion-social-instagram" style="color: rgb(230,255,255);"></i></a><a href="#"><i class="icon ion-social-snapchat" style="color: rgb(230,255,255);"></i></a><a href="#" style="color: rgb(230,255,255);"><i class="icon ion-social-twitter"></i></a>
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
            <p class="copyright" style="color: rgb(230,255,255);">Company Name © 2017</p>
        </footer>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>