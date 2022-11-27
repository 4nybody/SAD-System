<?php

include_once '../autoloader.php';

$userInfo = getUserWithEmailAddress( trim( $_POST['email'] ) );

if ( '' == $_POST['email'] || empty( $userInfo ) ) { // check email address
    $status = 'fail';
    $message = 'Invalid Email or Password';
} elseif ( '' == $_POST['password'] || password_verify( $_POST['password'], $userInfo['password'] ) ) { // user already exists with that email
    $status = 'fail';
    $message = 'Invalid Email or Password';
} else { // all passes so we are all good!
    $status = 'ok';
    $message = 'valid';

    // sign the user up to our site!

    $_SESSION['is_logged_in'] = true;
    $_SESSION['user_info'] = $userInfo;
}

echo json_encode(
    array( 
        'status' => $status,
        'message' => $message
    )
);