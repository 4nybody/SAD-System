<?php
// require_once 'google_log/google_process.php';
include_once 'autoloader.php';

if ( !isLoggedIn() ) {
  header( 'location: index.php' );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>customer Page</title>
</head>
<body>
<h1>Hello Employee</h1> 
<br>
<img src="<?php $_SESSION['user_info']['picture']; ?>" alt="" width="90px" height="90px">
  <ul>
    <li>Full Name: <?php echo $_SESSION['user_info']['first_name']; ?></li>
    <li>Email Address: <?php echo $_SESSION['user_info']['email'] ?></li>
    <li>Gender: <?php echo $_SESSION['user_info']['gender']; ?></li>
    <li>role: <?php echo $_SESSION['user_info']['role']; ?></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</body>
</html>
