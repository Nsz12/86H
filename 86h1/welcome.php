<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
require_once "config.php";
$xmlDoc=new DOMDocument();
$xmlDoc->load("links.xml");

$x=$xmlDoc->getElementsByTagName('link');

//get the q parameter from URL
$q=$_GET["q"];

//lookup all links from the xml file if length of q>0
if (strlen($q)>0) {
  $hint="";
  for($i=0; $i<($x->length); $i++) {
    $y=$x->item($i)->getElementsByTagName('title');
    $z=$x->item($i)->getElementsByTagName('url');
    if ($y->item(0)->nodeType==1) {
      //find a link matching the search text
      if (stristr($y->item(0)->childNodes->item(0)->nodeValue,$q)) {
        if ($hint=="") {
          $hint="<a href='" .
          $z->item(0)->childNodes->item(0)->nodeValue .
          "' target='_blank'>" .
          $y->item(0)->childNodes->item(0)->nodeValue . "</a>";
        } else {
          $hint=$hint . "<br /><a href='" .
          $z->item(0)->childNodes->item(0)->nodeValue .
          "' target='_blank'>" .
          $y->item(0)->childNodes->item(0)->nodeValue . "</a>";
        }
      }
    }
  }
}

// Set output to "no suggestion" if no hint was found
// or to the correct values
if ($hint=="") {
  $response="no suggestion";
} else {
  $response=$hint;
}

//output the response
echo $response;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["group_name1"])){
if(!empty($_POST["group_name1"])){

  $id = $_SESSION['id'];
   $group_name1=$_POST['group_name1'];

  $sql= "INSERT INTO groups (group_name, owner, image) VALUES ('".$group_name1."',".$id.", 'assets/img/sharing-money-2.png')";
   mysqli_query($connect, $sql);

   $group_id= mysqli_insert_id($connect);
   $ss= "id=".$id." and the group id is ".$group_id;

 // echo "<script>alert('$ss' );</script>";

  /* $sql="SELECT ".$id." from groups where group_id = ".$group_id."";
   $owner_id = mysqli_query($connect, $sql);
*/
/*$zz= "".$group_id.",".$id;
$dd="INSERT INTO group_users (group_id_fk,user_id_fk,status) VALUES ('$zz',1);";
  echo "<script>alert('$dd');</script>";
*/
   $sql= "INSERT INTO group_users (group_id_fk,user_id_fk,status) VALUES (".$group_id.",".$id.",3);";
   mysqli_query($connect, $sql);
}
}
echo '<script>window.location="welcome.php"</script>';

}
// Include config file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["group_join"])){
if(!empty($_POST["group_join"])){

  $id = $_SESSION['id'];
   $group_id=$_POST['group_join'];

   $sql = "SELECT group_id from groups where group_id = ".$group_id ;
   //store the result
   $result = mysqli_query($connect, $sql);

   if ($result==1) {
// status 1 for members status 2 for admins status 3 for owner 
  $sql= "INSERT INTO group_users (group_id_fk,user_id_fk,status) VALUES (".$group_id.",".$id.",1)";
   mysqli_query($connect, $sql);

  /* $group_id= mysqli_insert_id($connect);
   $ss= "id=".$id." and the group id is ".$group_id;

 // echo "<script>alert('$ss' );</script>";

  /* $sql="SELECT ".$id." from groups where group_id = ".$group_id."";
   $owner_id = mysqli_query($connect, $sql);
*/
/*$zz= "".$group_id.",".$id;
$dd="INSERT INTO group_users (group_id_fk,user_id_fk,status) VALUES ('$zz',1);";
  echo "<script>alert('$dd');</script>";
*/
/*   $sql= "INSERT INTO group_users (group_id_fk,user_id_fk,status) VALUES (".$group_id.",".$id.",1);";
   mysqli_query($connect, $sql);*/
 }
}
}
echo '<script>window.location="welcome.php"</script>';

}

//get information about the user for the database
$id = $_SESSION['id'];

//select statment
$sql = "SELECT * from group_users JOIN groups on groups.group_id = group_users.group_id_fk where group_users.user_id_fk = ".$id ;
//store the result
$result = mysqli_query($connect, $sql);


$sql = "SELECT * from invitation JOIN groups on groups.group_id = invitation.group_id where invitation.user_id = ".$id ;

$invite = mysqli_query($connect, $sql);

if(isset($_GET["action"])){
    $sql = "DELETE from invitation where group_id = ".$_GET['group']." and user_id = ".$id;
    mysqli_query($connect, $sql);

     if($_GET["action"] == "join"){
       $sql= "INSERT INTO group_users (user_id_fk, group_id_fk, status)
        VALUES (".$id.",".$_GET['group']." , 1)";

    mysqli_query($connect, $sql);
     }

echo '<script>window.location="welcome.php"</script>';

}
 /*if(isset($_POST["group_name"])){
 $id = $_SESSION['id'];

  $sql= "INSERT INTO groups (group_name, owner) VALUES ('".$group_name."',".$id.")";
   mysqli_query($connect, $sql);

   $group_id= mysqli_insert_id($connect);
     echo "php group id =".$group_id;

   $sql="SELECT ".$owner_id." from groups where group_id = ".$group_id."";
   $owner_id = mysqli_query($connect, $sql);


   $sql= "INSERT INTO group_users (group_id_fk,user_id_fk,status) VALUES (".$group_id.",".$owner_id.",1);";
   mysqli_query($connect, $sql);
}*/
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
        <div class="container-fluid"><img src="assets/img/76dc75b0-7f18-4306-9bc8-32e1641adfc1.jpg" width="70px" height="70px" alt="logo">
          <span class="navbar-brand"  style="color: rgb(230,255,255);">&nbsp; &nbsp;86H</span><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <form>
                <input type="text" size="30" onkeyup="showResult(this.value)">
                <div id="livesearch"></div>
            </form>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav"></ul>
                </div>
                <div><a  aria-expanded="false" href="logout.php" style="color: rgb(230,255,255);">LOGOUT </a>

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


                                    <td class="text-justify">
                                      <a href="ESTRAHA.php?group=<?php echo $row["group_id"];?>&id=<?php echo $row["user_id_fk"];?>&status=<?php echo $row["status"];?>" style="color: rgb(230,255,255);font-size: 25px;">
                                        <img src="<?php echo $row["image"];?>" width="100px" height="100px" alt="group logo">&nbsp;<?php echo $row["group_name"]?>&nbsp;</a>
                                      </td>



                                   <?php
                                    }

                                   } else {
                                             echo "<div class='card-body'><p class='card-text' style='color: rgb(230,255,255);'>YOU ARE NOT JOINED IN ANY GROUP<p><div class='card-body'>";
                                            }


                                            ?>
                                </tr>
                            </tbody>
                        </table>
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
                                              $sql = "SELECT name";
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
                <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-3" href="#accordion-1 .item-3" style="color: rgb(230,255,255);">CREATE GROUP</a></h5>
            </div>
            <div class="collapse item-3" role="tabpanel" data-parent="#accordion-1" style="color: rgb(230,255,255);background-color: rgb(15,7,67);">
                <div class="card-body">

                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <div class="card-body">
                  <input type="text" name="group_name1"  value="<?php echo $group_name1; ?>" placeholder="GROUP NAME">
                  <button class="btn btn-link float-right" type="submit" style="background-color: rgb(137,71,244);color: rgb(230,255,255);">CREATE</button>
                </div>
              </form>



                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab" style="background-color: rgb(46,15,123);">
                <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-1 .item-4" href="#accordion-1 .item-4" style="color: rgb(230,255,255);">JOIN GROUP</a></h5>
            </div>
            <div class="collapse item-4" role="tabpanel" data-parent="#accordion-1" style="color: rgb(230,255,255);background-color: rgb(15,7,67);">
                <div class="card-body">

                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <div class="card-body">
                  <input type="text" name="group_join"  value="<?php echo $group_join; ?>" placeholder="GROUP ID">
                  <button class="btn btn-link float-right" type="submit" style="background-color: rgb(137,71,244);color: rgb(230,255,255);">JOIN</button>
                </div>
              </form>



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

<script type="text/javascript">
function showResult(str) {
  if (str.length==0) {
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("livesearch").innerHTML=this.responseText;
      document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","livesearch.php?q="+str,true);
  xmlhttp.send();
}
</script>

<script type="text/javascript">
/*function Create_group() {
  var group = prompt("Please enter the group name");
    alert("hi");
    alert(group);


  if (group != null) {
      alert("hiasdasdd");

    document.getElementById("group_name").value = group;
   document.getElementById("group_form").submit();
    alert("php");

 /*<php
  require_once "config.php";

  $id = $_SESSION['id'];



  $sql= "INSERT INTO groups (group_name, owner) VALUES ('".$group_name."',".$id.")";
 if (mysqli_query($connect, $sql)) {
  echo "alert('New record created successfully. Last inserted ID is: " . $id."');";

 }else{
     echo "alert('Error: " . mysqli_error($conn)."');";
 }
  $group_id  = mysqli_insert_id($connect);


  $sql="SELECT ".$owner_id." from groups where group_id = ".$group_id."";
  $owner_id = mysqli_query($connect, $sql);


  $sql= "INSERT INTO group_users (group_id_fk,user_id_fk,status) VALUES (".$group_id.",".$owner_id.",1);";
  mysqli_query($connect, $sql);

  mysqli_close($connect);

   ?>
alert("php end");
  }

}*/
</script>

</html>
