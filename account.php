<?php
  session_start();
  function input_test($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  if($_GET["logOut"] == 1 AND $_SESSION['id']) {
    session_destroy();
    $massage = "You already logged out. Have a nice day!";
    session_start();
  }
  $email = $_GET["email"];
  $email = input_test($email);
  if($_GET["status"] == "expired" AND $_SESSION[$email]) {
    session_destroy();
    $errormassage = "The link has been expired, please reset again.";
    session_start();
  }
  include ("connection.php");
  if($_POST["submit"] == 'reset') {
    if($_POST["Email_Reset"] == "") {
      $errormassage .= "Please enter your email address.<br />";
      echo $errormassage;
    }
    if($errormassage == "") {
      $query = "SELECT Info_Email FROM Info WHERE Info_Email = '".mysqli_real_escape_string($link, $_POST["Email_Reset"])."'";
      $result = mysqli_query($link, $query);
      $result = mysqli_num_rows($result);
      if($result == 0) {
        echo "Cannot find that email address.<br />";
      }
      else {
        $email = input_test($_POST["Email_Reset"]);
        $emailTo = $email;
        $subject = "Password Reset";
        $body = "Dear $email:\n\nIf you requested this password change, please click on the following link to reset your password:\nhttp://wenyiyang.net/web-application/secret-diary/reset.php?token=$email\n\nWenyi";
        $emailFrom = "From: wenyiloveusc@gmail.com";
        if(mail($emailTo, $subject, $body, $emailFrom)) {
          $_SESSION[$email] = "true";
          $massage = "The reset link has been sent. Please check your email.<br />";
          echo $massage;
        }
      }
    }
  }
  
  if($_POST["submit"] == "Sign Up") {
    if($_POST["Email"]) {
      $email = input_test($_POST["Email"]);
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "Please enter a vaild email address.<br />";
      }
    }
    else {
      $error .= "Please enter your email address.<br />";
    }
    if($_POST["Password"]) {
      $password = input_test($_POST["Password"]);
      if(strlen($password) < 8) {
        $error .= "The length of password should not be less than 8.<br />";
      }
      if(!preg_match("/[A-Z]+/", $password)) {
        $error .= "The password should include at least one capital letter.<br />";
      }
    }
    else {
      $error .= "Please enter your password.<br />";
    }
    if($error) {
      $error = $error;
    }
    else {
      $query = "SELECT Info_Email FROM Info WHERE Info_Email = '".mysqli_real_escape_string($link, $_POST["Email"])."'";
      $result = mysqli_query($link, $query);
      $result = mysqli_num_rows($result);
      if($result) {
        $error .= "That email is already registered! Do you want to log in?<br />";
      }
      else {
        $query = "INSERT INTO Info (Info_Email, Info_Password) VALUES ('".mysqli_real_escape_string($link, $_POST["Email"])."'".", '".md5(md5($_POST["Email"]).($_POST["Password"]))."')";
        $result = mysqli_query($link, $query);
        if($result) {
          $_SESSION['id'] = mysqli_insert_id($link);
          header("Location:home.php");
          exit();
        }
        else {
          $error .= "It failed!";
        }
      }
    }
  }
  
  if($_POST["submit"] == "Log In") {
    if($_POST["Email_Log_In"] == "") {
      $error .= "Please enter your email address.<br />";
    }
    if($_POST["Password_Log_In"] == "") {
      $error .= "Please enter your password.<br />";
    }
    if($error == "") {
      $query = "SELECT Info_Email FROM Info WHERE Info_Email = '".mysqli_real_escape_string($link, $_POST["Email_Log_In"])."'";
      $result = mysqli_query($link, $query);
      $result = mysqli_num_rows($result);
      if($result == 0) {
        $error .= "Cannot find that email address.<br />";
      }
      else {
        $query = "SELECT Info_Password FROM Info WHERE Info_Email = '".mysqli_real_escape_string($link, $_POST["Email_Log_In"])."'";
        $output = mysqli_query($link, $query);
        $password = mysqli_fetch_array($output);
        if($password[0] == md5(md5($_POST["Email_Log_In"]).($_POST["Password_Log_In"]))) {
          $query = "SELECT Info_ID FROM Info WHERE Info_Email = '".mysqli_real_escape_string($link, $_POST["Email_Log_In"])."'";
          $output = mysqli_query($link, $query);
          $ID = mysqli_fetch_array($output);
          $_SESSION['id'] = $ID[0];
          header("Location:home.php");
          exit();
        }
        else {
          $error .= "That password dose not match our records. Please enter again.<br />";
        } 
      }
    }
    else {
      $error = $error;
    }
  }
?>