<?php
include_once '../autoloader.php';

$userInfo = getUserWithEmailAddress( trim( $_POST['email'] ) );

if ( !filter_var( trim( $_POST['email'] ), FILTER_VALIDATE_EMAIL ) ) { // check email address
    $status = 'fail';
    $message = 'Invalid Email';
} elseif ( !empty( $userInfo ) ) { // user already exists with that email
    $status = 'fail';
    $message = 'Email address already registered';
} elseif ( !$_POST['username']) { // check name
    $status = 'fail';
    $message = 'Invalid first or last name';
} elseif ( !$_POST['password'] || $_POST['password'] != $_POST['cpassword'] || strlen( $_POST['password'] ) < 8 ) { // check password/confirm password
    $status = 'fail';
    $message = 'Invalid password or passwords do not match and must be at least 8 characters';
} else { // all passes so we are all good!
    $status = 'ok';
    $message = 'valid';

    // sign the user up to our site!
    $userId = signUserUp( $_POST );
}

echo json_encode(
    array( 
        'status' => $status,
        'message' => $message
    )
);