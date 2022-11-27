<?php

function makeFacebookApiCall( $endpoint, $params ) { 
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $endpoint . '?' . http_build_query( $params ) );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

    $fbResponse = curl_exec( $ch );
    $fbResponse = json_decode( $fbResponse, TRUE );
    curl_close( $ch );

    return array( 
        'endpoint' => $endpoint,
        'params' => $params,
        'has_errors' => isset( $fbResponse['error'] ) ? TRUE : FALSE, // boolean for if an error occured
        'error_message' => isset( $fbResponse['error'] ) ? $fbResponse['error']['message'] : '', // error message
        'fb_response' => $fbResponse // actual response from the call
    );
}

function getFacebookLoginUrl() {
    // endpoint for facebook login dialog
    $endpoint = 'https://www.facebook.com/' . FB_GRAPH_VERSION . '/dialog/oauth';

    $params = array( // login url params required to direct user to facebook and promt them with a login dialog
        'client_id' => FB_APP_ID,
        'redirect_uri' => FB_REDIRECT_URI,
        'state' => FB_APP_STATE,
        'scope' => 'email, user_gender, public_profile',
        'auth_type' => 'rerequest'
    );

    // return login url
    return $endpoint . '?' . http_build_query( $params );
}

function getAccessTokenWithCode( $code ) {
    // Endpoint https://graph.facebook.com/{fb-graph-version}/oauth/access_token?client_id{app-id}&client_secret={app-secret}&redirect_uri={redirect_uri}&code={code}
    $endpoint = 'https://graph.facebook.com/'. FB_GRAPH_VERSION .'/oauth/access_token';

    $params = array( // params for the endpoint
        'client_id' => FB_APP_ID,
        'client_secret' => FB_APP_SECRET,
        'redirect_uri' => FB_REDIRECT_URI,
        'code' => $code
    );

    return makeFacebookApiCall( $endpoint, $params );
}

function getFacebookUserInfo( $accessToken ) { 
    // Endpoint https://graph.facebook.com/me?fields={fields}&access_token={access-token}
    $endpoint = FB_GRAPH_DOMAIN . 'me';
    $params = array( // params for the endpoint
        'fields' => 'first_name, last_name, email, picture',
        'access_token' => $accessToken
    );
    return makeFacebookApiCall( $endpoint, $params );
}

function tryAndLoginWithFacebook( $get ) { 
    $status = 'fail';
    $message = '';
    $_SESSION['fb_access_token'] = array();
		$_SESSION['fb_user_info'] = array();
    $_SESSION['eci_login_required_to_connect_facebook'] = false;
    if ( isset( $get['error'] ) ) {
        $message = $get['error_description'];
    } else { 
        $accessTokenInfo = getAccessTokenWithCode( $get['code']);

        if ( $accessTokenInfo['has_errors'] ) {
            $message = $accessTokenInfo['error_description'];
        } else {
            $_SESSION['fb_access_token'] = $accessTokenInfo['fb_response']['access_token'];

            $fbUserInfo = getFacebookUserInfo( $_SESSION['fb_access_token'] );

            if ( !$fbUserInfo['has_errors'] && !empty( $fbUserInfo['fb_response']['id'] ) && !empty( $fbUserInfo['fb_response']['email'] ) ) { 
                $status = 'ok';

                $_SESSION['fb_user_info'] = $fbUserInfo['fb_response'];

                $userInfoWithId = getRowWithValue( 'users', 'fb_user_id', $fbUserInfo['fb_response']['id'] );

                $userInfoWithEmail = getRowWithValue( 'users', 'email', $fbUserInfo['fb_response']['email'] );

                if ( $userInfoWithId || ( $userInfoWithEmail && !$userInfoWithEmail['password'] ) ) { // user has logged in with facebook before so we found them
                    // update user
                    updateRow( 'users', 'fb_access_token', $_SESSION['fb_access_token'], $userInfoWithId['id'] );
                    $userInfo = getRowWithValue( 'users', 'id', $userInfoWithId['id'] );

                    // save info to php session so they are logged in
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['user_info'] = $userInfo;
                } elseif ( $userInfoWithEmail && !$userInfoWithEmail['fb_user_id'] ) { // existing account exists for the email and has not logged in with facebook before
                    $_SESSION['eci_login_required_to_connect_facebook'] = true;
                } else { // user not found with id/email sign them up and log them in
                    // sign user up
                    $fbUserInfo['fb_response']['fb_access_token'] = $_SESSION['fb_access_token'];
                    $userId = signUserUp( $fbUserInfo['fb_response'] );
                    $userInfo = getRowWithValue( 'users', 'id', $userId );

                    // save info to php session so they are logged in
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['user_info'] = $userInfo;
                }
            } else {
                $message = 'Invalid creds';
            }
        }
    }

    return array( 
        'status' => $status,
        'message' => $message,

    );
}