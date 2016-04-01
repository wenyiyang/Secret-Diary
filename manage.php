<?php 
  session_start();
  if(!isset($_SESSION['id'])) {
    header("Location:signup.php");
    exit();
  }
  include ("connection.php");
  if(isset($_GET['delpost'])){ 
    $postID = $_GET['delpost'];
    $query = "DELETE FROM Post WHERE Post_ID = '".$postID."'";
    $result = mysqli_query($link, $query);
    header('Location: home.php?action=deleted');
    exit();
  } 
  $infoID = $_SESSION['id'];
  $query ="SELECT Post_ID, Post_Title, Post_Date FROM Post WHERE Info_ID = '".$infoID."'ORDER BY Post_Date DESC";
  $result = mysqli_query($link, $query);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secret Dairy</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <style type="text/css">
      a {
        color:black !important;
      }
      #background {
        background-image:url("background.jpg");
        background-repeat:no-repeat;
        width:100%;
        background-size:cover;
      }
      #topRow {
        margin-top:100px;
      }
      .marginBottom {
        margin-bottom:20px;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Secret Diary</a>
        </div>
        <div class="collapse navbar-collapse navbar-right" id="myNavbar">
          <ul class="nav navbar-nav">
            <li><a href="home.php">Home</a></li>
            <li><a href="post.php">Post</a></li>
            <li class="active"><a href="manage.php">Manage</a></li>
            <li><a href="http://wenyiyang.net/web-application/secret-diary/signup.php?logOut=1">Log Out</a>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container myContainer" id="background">
      <div class="row">
        <div class="col-md-8 col-md-offset-2" id="topRow">
          <table class="table table-bordered">
            <tr><th>Title</th><th>Date</th><th>Action</th></tr>
            <?php
              while($row = mysqli_fetch_array($result)) {
                echo '<tr>';
                echo '<td>'.$row['Post_Title'].'</td>';
                echo '<td>'.date('jS M Y', strtotime($row['Post_Date'])).'</td>';
                ?>
                <td>
                    <a href="editpost.php?id=<?php echo $row['Post_ID'];?>">Edit</a> | <a href="javascript:delpost('<?php echo $row['Post_ID'];?>','<?php echo $row['Post_Title'];?>')">Delete</a>
                </td>
                <?php 
                echo '</tr>';
              }
            ?>
          </table>
        </div>
      </div>
    </div>
    <script>
      $(".myContainer").css("min-height",$(window).height());
      function delpost(id, title) {
        if (confirm("Are you sure you want to delete '" + title + "'")){
          window.location.href = 'manage.php?delpost=' + id;
        }
      }
    </script>
  </body>
</html>

