<?php
session_start() ;
//variables need to be declared first because of 
//rendering so the other files can actually use them
$name = '' ;
$email = '' ;
$reason = '' ;
$comment = '' ;

//should take away any unnecessary spaces
//apparently should only be sanitized if its going OUT so I won't be doing
//it here since this is to validate going INTO the database
if ($_SERVER["REQUEST_METHOD"] === "POST" ) {

    $name = trim($_POST['clientName'] ?? '') ;
    $email = trim($_POST['clientEmail'] ?? '') ;
    $reason = trim($_POST['clientReason'] ?? '') ;
    $comment = trim($_POST['clientMessage'] ?? '') ;

    //variable used to track mistakes
    $errors = [] ;

    //Custom reason will be blank if they select one of the premade ones
    if ($reason === "other")
        {
            $custom = trim($_POST['otherReason'] ?? '') ;

            if (empty($custom) ) 
                {
                    //die("Custom Reason required") ;
                    $errors[] = "Custum Reason required" ;
                }
        }
    else 
        {
            $custom = "" ;
        }

    //makes sure they actually input something into the required fields
    if (empty($name) )
        {
            //die is like an exit statement
            //die("Name required") ;

            //doesn't kill process entirely
            //simply adds to array to be read IF there are any mistakes made
            $errors[] = "Name required" ;
        }
    else if (!preg_match("/^[a-zA-Z-' ]*$/", $name) )
        {
            //die("Please no special characters for names") ;

            $errors[] = "Please remove any special character in your name" ;
        }
    if (empty($email) ) 
        {
            //die("Email required") ;

            $errors[] = "Email required" ;
        }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL) )
        {
            //die("Please enter a valid email address") ;

            $errors[] = "Please enter a valid email address" ;
        }

    if (empty($comment) ) 
        {
            //die("Comment required") ;

            $errors[] = "Comment required" ;
        }

    // if (!empty($errors) ) 
    //     {
    //         for ($i = 0; $i < count($errors); $i ++ ) 
    //             {
    //                 echo $errors[$i] . "<br>" ;
    //             }
    //     }

    //since I am redirecting it to the original page to avoid making
    //a new one I need to store the data
    $_SESSION['errors'] = $errors ;
    $_SESSION['name'] = $name ;
    $_SESSION['email'] = $email ;
    $_SESSION['comment'] = $comment ;
    $_SESSION['reason'] = $reason ;
    $_SESSION['custom'] = $custom ;

    header("Location: contact.php") ;
    exit;
    
}

?>