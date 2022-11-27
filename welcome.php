<?php
// require_once './google_log/google_process.php';
require_once 'php/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
</head>

<body>
  <img src="<?php $_SESSION['user_info']['picture']; ?>" alt="picture" width="90px" height="90px">
  <ul>
    <li>Full Name: <?php echo isset($_SESSION['user_info']['first_name']); ?></li>
    <li>Email Address: <?php echo $_SESSION['user_info']['email'] ?></li>
    <li>Gender: <?php echo $_SESSION['user_info']['gender']; ?></li>
    <li>role: <?php echo $_SESSION['user_info']['role']; ?></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</body>

</html>