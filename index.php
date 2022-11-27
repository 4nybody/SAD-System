<?php
include_once 'autoloader.php';
error_reporting(0);

if( isset( $_GET['state'] ) && FB_APP_STATE == $_GET['state'] ) {
  $fbLogin = tryAndLoginWithFacebook( $_GET );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css" />
    <title>Sign in & Sign up Form</title>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/loader.js"></script>

    <script>
    $(function() { // once the document is ready, do things
        // initialize our loader overlay
        $('#signup_button').on('click', function(e) { // onclick for our signup button
			e.preventDefault();
            processSignin();
        });
        $('#signin_button').on('click', function(e) { // onclick for our login button
			e.preventDefault();
            processLogin();
        });
    });


    function processSignin() {
            // e.preventDefault();
            $.ajax({
                url: 'php/process_signup.php',
                data: $('#signup_form').serialize(),
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    if ('ok' == data.status) {
                        loader.hideLoader();
                        window.location.href = "index.php";
                    } else if ('fail' == data.status) {
                        $('#error_message').html(data.message);
                        loader.hideLoader();
                    }
                }
            });
    };

    function processLogin() {
            // e.preventDefault();
            $.ajax({
                url: 'php/process_login.php',
                data: $('#signin_form').serialize(),
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    if ('ok' == data.status) {
                        loader.hideLoader();
                        window.location.href = "separator.php";
                    } else if ('fail' == data.status) {
                        $('#error_message').html(data.message);
                        loader.hideLoader();
                    }
                }
            });
    };
    </script>
</head>

<body>

    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form id="signin_form" class="sign-in-form" method="post">
                    <h2 class="title">Sign in</h2>
                    <div id="error_message" class="error-message">
                    </div>
                    <div id="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="text" placeholder="Email" name="email" value="<?php echo $_POST['email']; ?>"
                            required />
                    </div>
                    <div id="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Password" name="password"
                            value="<?php echo $_POST['password']; ?>" required />
                    </div>
                    <input type="submit" id="signin_button" value="Login" name="signin" class="btn solid" />
                    <p class="social-text">Or Sign in with social platforms</p>
                    <div class="social-media">
                        <a href="<?php echo getFacebookLoginUrl(); ?>" class="social-icon">
                            <i class="fab fa-facebook-f"> </i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                    </div>
                </form>
                <form id="signup_form" class="sign-up-form" method="post">
                    <h2 class="title">Sign up</h2>
                    <div id="error_message" class="error-message">
                    </div>
                    <div id="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Username" name="username" required />
                    </div>
                    <div id="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Email" name="email" required />
                    </div>
                    <div id="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Password" name="password" required />
                    </div>
                    <div id="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Confirm Password" name="cpassword" required />
                    </div>
                    <!-- <button type="submit" class="btn" id="signup_button" name="signup">Sign up</button> -->
                    <input type="submit" class="btn" id="signup_button" name="signup" value="Sign up" />
                    <p class="social-text">Or Sign up with social platforms</p>
                    <div class="social-media">
                        <a href="<?php echo getFacebookLoginUrl(); ?>" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class='social-icon'>
                            <i class='fab fa-google'></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>New here ?</h3>
                    <p>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Debitis,
                        ex ratione. Aliquid!
                    </p>
						<button class="btn transparent" id="sign-up-btn">
                        Sign up
                    </button>
                </div>
                <img src="images/login.svg" class="image" alt="" />
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>One of us ?</h3>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum
                        laboriosam ad deleniti.
                    </p>
                    <button class="btn transparent" id="sign-in-btn">
                        Sign in
                    </button>
                </div>
                <img src="images/signup.svg" class="image" alt="" />
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>

</html>