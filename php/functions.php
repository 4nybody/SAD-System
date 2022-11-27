<?php
include 'config.php';


function getDatabaseConnection() {
    try { // connect to database and return connections
        $conn = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS );
        return $conn;
    } catch ( PDOException $e ) { // connection to database failed, report error message
        return $e->getMessage();
    }
}

function getRowWithValue( $tableName, $column, $value ) {
    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare( '
        SELECT
            *
        FROM
            ' . $tableName . '
        WHERE
            ' . $column . ' = :' . $column
    );

    // execute sql with actual values
    $statement->setFetchMode( PDO::FETCH_ASSOC );
    $statement->execute( array(
        $column => trim( $value )
    ) );

    // get and return user
    $user = $statement->fetch();
    return $user;
}

function getUserWithEmailAddress( $email ) {
    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create sql statment
    $statement = $databaseConnection->prepare( '
        SELECT
            *
        FROM
            users
        WHERE
            email = :email
    ' );

    // execute sql with actual values
    $statement->setFetchMode( PDO::FETCH_ASSOC );
    $statement->execute( array(
        'email' => trim( $email )
    ) );

    // get and return user
    $user = $statement->fetch();
    return $user;
}

function updateRow( $tableName, $column, $value, $id ) {
		// get database connection
		$databaseConnection = getDatabaseConnection();

		// create our sql statment
		$statement = $databaseConnection->prepare( '
			UPDATE
				' . $tableName . '
			SET
				' . $column . ' = :value
			WHERE
				id = :id
		' );

		// set our parameters to use with the statment
		$params = array(
			'value' => trim( $value ),
			'id' => trim( $id )
		);

		// run the query
		$statement->execute( $params );
	}

function signUserUp( $info ) {
    $getDatabaseConnection = getDatabaseConnection();
    $statement = $getDatabaseConnection-> prepare( '
    INSERT INTO
     users ( 
        email,
        first_name,
        last_name,
        username,
        password,
        token,
        fb_user_id,
        fb_access_token
        ) 
    VALUES (
        :email,
        :first_name,
        :last_name,
        :username,
        :password,
        :token,
        :fb_user_id,
        :fb_access_token
    )
    ' );
    $statement->execute( array( 
        'email' => trim( $info['email'] ),
        'first_name' => isset( $info['first_name'] ) ? trim( $info['first_name'] ) : '',
        'last_name' => isset( $info['last_name'] ) ? trim( $info['last_name'] ) : '',
        'username' => isset( $info['username'] ) ? $info['username'] : '',
        'password' => isset( $info['password'] ) ? hashedPassword( $info['password'] ) : '',
        'token' => newToken(),
        'fb_user_id' => isset( $info['id'] ) ? $info['id'] : '',
		'fb_access_token' => isset( $info['fb_access_token'] ) ? $info['fb_access_token'] : '',
    ) );
    return $getDatabaseConnection-> lastInsertId();
}

function newToken( $lenght = 32 ) {
    $time = md5( uniqid() ) . microtime();
    return substr( md5($time ), 0, $lenght );
}

function hashedPassword( $password ) {
    $random = openssl_random_pseudo_bytes( 18 );
    $salt = sprintf( '$2y$%02d$%s',
        12,
        substr( strtr( base64_encode( $random ), '+', '.' ), '0', '22')
    );
    $hash = crypt( $password, $salt );
    return $hash;
}

function isLoggedIn(){
    if ( isset( $_SESSION['is_logged_in'] ) && $_SESSION['is_logged_in'] &&
     ( isset( $_SESSION['user_info'] ) && $_SESSION['user_info'] ) ) {
        return true;
    }else{
        return false;
    }
}
?>