<!--Author of File: Hayden Arceneaux
    Purpose: Validate input from forms to either send to db
             Or advise user to revise inputs-->

<?php
session_start() ;
include 'db.php' ;

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
                    $errors[] = "Custom Reason required" ;
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

    //If there are no issues information should be added to froms tbale
    //in DB

    //if the very first error is nothing there shouldn't be any others
    if (empty($errors) ) 
        {
            try
            {
                //creates a copy of the custom variable because it CAN be 
                //null and I wil set the copy to null rather than the input itself
                $customCopy = ($reason === 'other') ? $custom : null ;

                // if ($reason === 'other' ) 
                //     {
                //         $stmt = $conn->prepare("
                //             INSERT INTO Forms (ClientName, Email, Reason, CustomReason, ClientConcern)
                //             VALUES (:name, :email, :reason, null, :comment)
                //         ") ;
                //     }
                // else
                //     {
                //         $stmt = $conn->prepare("
                //             INSERT INTO Forms (ClientName, Email, Reason, CustomReason, ClientConcern)
                //             VALUES (:name, :email, :reason, :custom, :comment)
                //         ") ;
                //     }
                $stmt = $conn->prepare("
                    INSERT INTO Forms (ClientName, ClientEmail, Reason, CustomReason, ClientConcern)
                    VALUES (:name, :email, :reason, :custom, :comment)
                ") ;


                $stmt->execute([
                    ":name" => $name,
                    ":email" => $email,
                    ":reason" => $reason,
                    ":custom" => $customCopy,
                    ":comment" => $comment
                ]) ;

                //cant actually test the email part from local host so I guess
                //that just has to wait

                //form should be sent to email before values are wiped
                if (!empty($customCopy) ) 
                    {
                        $reason = $customCopy ;
                    }
                $headers = "From: TBHDtaskmanagement@gmail.com\r\n" ;
                $headers .= "Reply-To: $email\r\n" ;
                mail("TBHDtaskmanagement@gmail.com",
                     htmlspecialchars($reason),
                      htmlspecialchars($comment),
                      $headers) ;

                //with the form properly submitted and in the db I can now
                //remove the values as I won't need to echo them back out for 
                //any reason
                $_SESSION['name'] = "" ;
                $_SESSION['email'] = "" ;
                $_SESSION['comment'] = "" ;
                $_SESSION['reason'] = "account" ;
                $_SESSION['custom'] = "" ;

                //popup to let them know the form was submitted
                $_SESSION['FormSubmitted'] = true ;

                header("Location: contact.php") ;
                exit;
                //die("Form submitted") ;
            }
            catch (PDOException $e) 
            {
                //die("failed to send data to database") ;
                //more so for debugging so I know what happened
                die("Failed to send data to database " . $e->getMessage()) ;
            }

            //Should probably close stuff 
            $stmt = null ;
            $conn = null ;
        }

    if (!empty($errors) ) {

    header("Location: contact.php") ;
    exit;

    }
    
}

?>
