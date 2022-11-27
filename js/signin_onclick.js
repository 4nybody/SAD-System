$( function() { // once the document is ready, do things

    $( '#signin_button' ).on( 'click', function(e) { // onclick for our signin button
e.preventDefault();
        $.ajax( {
            url: 'php/process_login.php',
            data: $( '#signin_form' ).serialize(),
            type: 'post',
            dataType: 'json',
            success: function( data ) {
                if ( 'ok' == data.status ) {
                    loader.hideLoader();
                    window.location.href = "separator.php";
                } else if ( 'fail' == data.status ) {
                    $( '#error_message' ).html( data.message );
                    loader.hideLoader();
                }
            }
        } );
    } );
} ); 