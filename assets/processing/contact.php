<?php
// Processing for emails to be sent from the contact form at the bottom of the page

// If this file was accessed w/ POST key "access" set to TRUE,
if( isset( $_POST['access'] ) && $_POST['access'] == TRUE ) {

    $email_to = "team@shire-digital.com";
    $email_subject = "Shire-Digital.com Contact Form Submission";

    // died() : Returns formatted error string
    function died( $error ) {
        // Return: status = error
        echo "~e|";
        echo "There were error(s) found with the form you submitted: <br /><br />";
        echo "<strong>" . $error . "</strong> <br /><br />";
        echo "Please fix these errors, then submit the form again! <br /><br />";
        die();
    }

    // Check for empty required inputs
    if( empty( $_POST['name'] ) || empty( $_POST['email'] ) ) {
        died( "Name and Email fields are required." );
    }

    // Form inputs
    $name       = $_POST['name'];       // required
    $email      = $_POST['email'];      // required
    $budget     = $_POST['budget'];     // not required
    $comment    = $_POST['comment'];    // required

    // error_msg, regExp strings
    $error_msg  = "";
    $string_exp = "/^[A-Za-z .'-]+$/";
    $email_exp  = "/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/";

    // Error check: name
    if( !preg_match( $string_exp, $name ) ) {
        $error_msg .= 'The name you entered does not appear to be valid. <br />';
    }

    // Error check: email
    if( !preg_match( $email_exp, $email ) ) {
        $error_msg .= 'The email you entered does not appear to be valid. <br />';
    }

    // Error check: comments
    // if( strlen( $comments ) < 5 ) {
    //     $error_msg .= 'The Comments you entered do not appear to be valid. <br />';
    // }

    // If there are any error messages,
    if( strlen( $error_msg ) > 0 ) {
        // Pass them to died()
        died( $error_msg );
    }

    $email_message = "The contact form at shire-digital.com was submitted with the following info: \n\n";

    // clean_string() : Remove potential email code injection attacks
    function clean_string( $string ) {
        $bad = array( "content-type", "bcc:", "to:", "cc:", "href" );
        return str_replace( $bad, "", $string );
    }

    // Build email body
    $email_message .= "Name: " . clean_string( $name ) . "\n";
    $email_message .= "Email: " . clean_string( $email ) . "\n";
    $email_message .= "Budget: " . clean_string( $budget ) . "\n";
    $email_message .= "Comment: " . clean_string( $comment ) . "\n";

    // Build email headers
    $headers =  'From: '  .$email . "\r\n" .
                'Reply-To: ' . $email . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

    // Send the email, and if it sends,
    if( @mail( $email_to, $email_subject, $email_message, $headers ) ) {
        // Return: status = success
        echo "~s|";
    };
} else {
    exit;
}
?>
