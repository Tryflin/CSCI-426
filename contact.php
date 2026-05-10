<!--Author of Task Management System Contact Page: Hayden Arceneaux-->

<!--for ssr-->
<?php
session_start() ;

$errors = $_SESSION['errors'] ?? [] ;
$name = $_SESSION['name'] ?? '' ;
$email = $_SESSION['email'] ?? '' ;
$comment = $_SESSION['comment'] ?? '' ;
$reason = $_SESSION['reason'] ?? '' ;
$custom = $_SESSION['custom'] ?? '' ;

unset(
    $_SESSION['errors'], 
    $_SESSION['name'], 
    $_SESSION['email'], 
    $_SESSION['comment'], 
    $_SESSION['reason'],
    $_SESSION['custom'] ) ;

//this is for when they submit the form
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta name="author" content="Hayden Arceneaux" />
        <meta name="description" content="Contact Page" />
        <meta charset="UTF-8" />

        <title>Contact Us</title>

        <link rel="stylesheet" href="contactStyle.css" />
    </head>

    

    <body>
        <nav>
            <span class="logo">Task Management System</span>

            <ul>
                <li><a href="calendar.php">Calendar</a></li>
                <li><a href="index.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            
            <!--Reused signup button for login-->
            <a href="login.php" class="signup-button">Log In</a>
        </nav>

        <main>
            <section class="contact-section">

                <section class="contact-info">
                    <h2>Contact Info</h2>

                    <section class="cell">
                        <img src="contact.jpg">
                    </section>

                    <section class="cell">
                        <h3>Other Forms of Contact</h3>

                            <ul id="extraContact">
                                <li id="phone">
                                    <!--We definitely don't have a company phone number-->
                                    Call us at (555)-555-5555
                                </li>
                                <li id="mail">
                                    <!--We don't have a company email or anything-->
                                    Email us directly at TBHDtaskmanagement@gmail.com
                                </li>
                            </ul>
                        </section>
                    </section>

                <section class="contact-form">
                    <h2>Contact Us</h2>

                    <!--Notification for what they need to change-->
                        <?php if (!empty($errors) ) : ?>
                            <aside class="errorNoti">
                                <?php foreach ($errors as $item): ?>
                                    <p> 
                                        <?php echo htmlspecialchars($item) ; ?>
                                    </p>
                                <?php endforeach; ?>
                            </aside>
                        
                        <!--php dont know where to end before the html starts apparently-->
                        <?php endif; ?>

                    <!--Notification for if they completed submission-->
                        <?php if (!empty($_SESSION['FormSubmitted'])) : ?>
                            <!--I am using the same class because its easier-->
                            <aside class="errorNoti">
                                <?php 
                                    echo "Thank you for your feedback!" ; 
                                    unset($_SESSION['FormSubmitted']) ;
                                ?>
                            </aside>
                                
                        <?php endif; ?>
                        

                    <section class="cell">
                        

                        <form name="contactPage" action="validate.php" method="post">
                            <label for="clientName">Full Name</label>
                            <!--had to start identing each attribute 
                            because I actually couldn't read them anymore-->
                            <input
                                id="clientName" 
                                name="clientName" 
                                type="text" 
                                maxlength="60" 
                                value="<?php echo htmlspecialchars($name) ; ?>"
                                required>

                            <label for="clientEmail">Email Address</label>
                            <input 
                                id="clientEmail" 
                                name="clientEmail" 
                                type="email" 
                                maxlength="60" 
                                value="<?php echo htmlspecialchars($email) ; ?>"
                                required>

                            <label for="clientReason">Reason For Contact</label>
                            <select id="clientReason" name="clientReason">
                                <option value="account"
                                    <?php if ($reason === "account") echo "selected"; ?>>
                                    Account
                                </option>

                                <!--This is assuming we even had ads or they werent using an ad blocker-->
                                <!--Was kinda struggling with ideas for why they would even contact us-->
                                <option value="ad"
                                    <?php if ($reason === "ad") echo "selected"; ?>>
                                    Inappropriate Ad
                                </option>

                                <option value="feedback"
                                    <?php if ($reason === "feedback") echo "selected"; ?>>
                                    General Feedback
                                </option>

                                <option value="other"
                                    <?php if ($reason === "other") echo "selected"; ?>>
                                    Other (custom subject)
                                </option>
                            </select>

                            <label for="otherReason" style="display: none;">Other</label>
                            <input 
                                id="otherReason" 
                                name="otherReason" 
                                type="text" 
                                maxlength="30" 
                                placeholder="Please Specify" 
                                value="<?php echo htmlspecialchars($custom) ; ?>"
                                style="display: none;">

                            <label for="clientMessage">Comments</label>
                            <textarea 
                                id="clientMessage" 
                                name="clientMessage" 
                                placeholder="Please enter your questions/comments/concerns." 
                                rows="10" 
                                maxlength="500" 
                                required><?php echo htmlspecialchars($comment) ; ?></textarea>

                            <button type="reset">Clear</button>
                            <!--when they click submit it SHOULD check to make sure they actually used the right inputs
                                I don't know how people get around the html validation like required but -->
                            <button type="submit">Submit</button>
                        </form>
                    </section>
                </section>
            </section>
        </main>
       
    </body>

    <!--Script at the bottom because it was loading before the page finished before-->
    <script src="contactScript.js"></script>

</html>
