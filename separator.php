<?php

include_once 'autoloader.php';
$userInfo = getUserWithEmailAddress( $_POST['email'] );

    if ( $_SESSION['user_info']['role'] == 1) {
      echo $userinfo["role"];
    } elseif ( $_SESSION['user_info']['role'] == 2) {
      echo $userinfo["role"];
      header("Location: ./employee.php");
    } elseif ( $_SESSION['user_info']['role'] == 3) {
      echo "your a customer";
      header("Location: ./customer.php");
    }

