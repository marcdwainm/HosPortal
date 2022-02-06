<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' href='css/index.css'>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Twin Care Portal | Login</title>
    <link rel="icon" href="img/logo.png">
</head>

<body>

    <?php
    session_start();

    if (isset($_SESSION['position'])) {
        $position = $_SESSION['position'];
        if ($position == 'doctor') {
            header("Location: employee-homepage.php");
        } else {
            header("Location: $position-homepage.php");
        }
    }

    include 'php_processes/db_conn.php';

    if (isset($_GET['resetPassword']) && isset($_GET['resetKey'])) {
        //IF RESET KEY IS IN TABLE, DISPLAY
        $reset_key = $_GET['resetKey'];
        $found = false;

        $result = mysqli_query($conn, "SELECT * FROM reset_pass_keys WHERE reset_key = '$reset_key'");
        $found = (mysqli_num_rows($result) > 0) ? true : false;

        if ($found) {
            echo "
                <div class='dim-reset-pass'>
                    <div class='reset-pass-window'>
                        <div class='forgot-pass-header'>
                            <span>Reset Password</span>
                        </div>
                        <div class='forgot-pass-body'>
                            <span>Enter the new password for your account. You will be using this the next time you log in.</span>
                            <input type='password' placeholder='New Password' id = 'new-pass'>
                            <input type='password' placeholder='Confirm New Password' id = 'conf-new-pass'>
                            <span class='invalid-pass'>Passwords do not match</span>
                            <button type='button' id='reset-pass-submit'>Reset Password</button>
                        </div>
                    </div>
                </div>
                ";
        }
    }
    //URL PATTERN: ?evalidation=jh2j13h9
    else if(isset($_GET['evalidation'])){
        $validation_key = $_GET['evalidation'];
        $found = false;

        $result = mysqli_query($conn, "SELECT * FROM email_validation_keys WHERE validation_key = '$validation_key'");
        $found = (mysqli_num_rows($result) > 0) ? true : false;
        
        if($found){
            $row = mysqli_fetch_array($result);
            $email = $row['email'];

            $query = "SELECT * FROM user_table WHERE email = '$email'";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) > 0){
                $query_nest = "UPDATE user_table SET email_validated = '1' WHERE email = '$email'";
                mysqli_query($conn, $query_nest);
            }

            else if(mysqli_num_rows($result) <= 0){
                $query = "SELECT * FROM employee_table WHERE email = '$email'";
                $result = mysqli_query($conn, $query);

                $query_nest = "UPDATE employee_table SET email_validated = '1' WHERE email = '$email'";
                mysqli_query($conn, $query_nest);
            }


            $query = "DELETE FROM email_validation_keys WHERE email = '$email'";
            mysqli_query($conn, $query);

            echo "
                <script>
                    Swal.fire(
                        'Success',
                        'Your e-mail has been successfully validated. You may now log in.',
                        'success'
                    )
                </script>
            ";
        } else{
            echo "
                <script>
                    Swal.fire(
                        'Invalid',
                        'Unfortunately, this e-mail validation is invalid.',
                        'error'
                    )
                </script>
            ";
        }
    }
    ?>

    <div class='dim-forgot-pass'>
        <div class="forgot-pass-window">
            <div class='forgot-pass-header'>
                <span>Forgot Password</span>
                <span class='forgot-pass-exit'>X</span>
            </div>
            <div class="forgot-pass-body">
                <span>Enter your E-mail below and we will be sending you a link to reset your password.</span>
                <input type='text' placeholder='E-mail' autocomplete="off" id='email-input-forgot'>
                <span class='email-not-found'>Email not found!</span>
                <button type='button' id='forgot-pass-submit'>Reset Password</button>
            </div>
        </div>
    </div>

    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
    <!--THIS INVISIBLE IFRAME IS FOR PREVENTING FORM REDIRECTIONS-->

    <div class='bg'></div>

    <div class='header-index'>
        <div class='header-logo'>
            <img src='img/logo-2.png'>
        </div>
        <span><i class="fas fa-phone-alt"></i>0925-734-7552</span>
    </div>

    <div class='first-div'>
        <div>

            <h1>High quality online care,</h1>
            <h1>you deserve, we give.</h1>
            <span class='margin-bottom'>As the pandemic persists, we should use the portal as it gives us: </span>
            <span>• 24/7 Accessibility</span>
            <span>• Less travelling</span>
            <span>• Appointment bookings online</span>
            <span>• Online bill payments</span>
            <span>• Storage for your health information needs</span>
            <span>• Notifications </span>
        </div>
    </div>
    <div class='second-div'>
        <div class='login-form'>
            <form class='l-form' action='php_processes/login.php' method='POST'>
                <span class='form-header'>Login to your account</span>
                <div class='login-inputs'>
                    <?php
                    if (isset($_GET['loginemail'])) {
                        $email = $_GET['loginemail'];
                        echo "<input type = 'text' placeholder = 'E-mail' name = 'login-email' value = '$email' autocomplete = 'off'>";
                    } else {
                        echo "<input type = 'text' placeholder = 'E-mail' name = 'login-email' autocomplete = 'off'>";
                    }
                    ?>

                    <div class="password-div">
                        <input type='password' placeholder='Password' name='login-pass'>
                        <div class="eye-div">
                            <i class="far fa-eye"></i>
                        </div>
                    </div>
                    <button>Login</button>
                    <span class="error-span">
                        <?php
                        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                        if (strpos($url, "fieldslogin=empty") == true) {
                            echo 'Field/s empty!';
                        } else if (strpos($url, "emaillogin=invalid") == true) {
                            echo 'Email input is not an email';
                        } else if (strpos($url, "acc=notfound") == true) {
                            echo 'Such account does not exist';
                        } else if (strpos($url, "validated=false") == true) {
                            echo "<span class = 'error-msg'>E-mail not yet validated! Go to your mail and follow the instructions provided.</span>";
                        }
                        ?>
                    </span>
                </div>
                <div class='login-links'>
                    <a href='#' id='register'>Create an Account</a>
                    <a href='#' id='forgot-pass'>Forgot your Password?</a>
                </div>
                <span class='footer'>Twin Care Portal. Copyright 2021.</span>
            </form>
        </div>


        <div class='reg-form'>
            <form class='r-form' action='php_processes/registration.php' method='POST'>
                <div class='back-header'>
                    <button id='back-btn' type='button'><i class="fas fa-arrow-left"></i></button>
                </div>

                <div class='login-inputs'>
                    <div class='fullname'>
                        <?php
                        //FIRSTNAME
                        if (isset($_GET['fname'])) {
                            $fname = $_GET['fname'];
                            echo "<input type = 'text' placeholder = 'First Name' name = 'firstname' value = '$fname' autocomplete = 'off'>";
                        } else {
                            echo "<input type = 'text' placeholder = 'First Name' name = 'firstname' autocomplete = 'off'>";
                        }
                        //MIDDLENAME
                        if (isset($_GET['mname'])) {
                            $mname = $_GET['mname'];
                            echo "<input type = 'text' placeholder = 'Middle Name' name = 'middlename' value = '$mname' autocomplete = 'off'>";
                        } else {
                            echo "<input type = 'text' placeholder = 'Middle Name' name = 'middlename' autocomplete = 'off'>";
                        }
                        //LASTNAME
                        if (isset($_GET['lname'])) {
                            $lname = $_GET['lname'];
                            echo "<input type = 'text' placeholder = 'Last Name' name = 'lastname' value = '$lname' autocomplete = 'off'>";
                        } else {
                            echo "<input type = 'text' placeholder = 'Last Name' name = 'lastname' autocomplete = 'off'>";
                        }
                        ?>
                    </div>
                    <div class='sex-tel' aria-placeholder="gender">
                        <div class='select-wrapper'>
                            <select name='sex'>
                                <?php
                                if ($_GET['sex'] === 'male') {
                                    echo "
                                                <option value = '' disabled>Sex</option>
                                                <option value = 'male' selected>Male</option>
                                                <option value = 'female'>Female</option>
                                            ";
                                } else if ($_GET['sex'] === 'female') {
                                    echo "
                                                <option value = '' disabled>Sex</option>
                                                <option value = 'male'>Male</option>
                                                <option value = 'female' selected>Female</option>
                                            ";
                                } else {
                                    echo "
                                            <option value = '' disabled selected>Sex</option>
                                            <option value = 'male'>Male</option>
                                            <option value = 'female'>Female</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type='text' class='birthdate' placeholder='Birthdate' onfocus="(this.type='date')" name='bdate'>
                    </div>

                    <?php
                    if (isset($_GET['telnum'])) {
                        $telnum = $_GET['telnum'];
                        echo "<input type = 'tel' id = 'telnum' name = 'telnum' placeholder = 'Phone Number (Format: 09983900813)' value = '$telnum' autocomplete = 'off' maxlength = '11'>";
                    } else {
                        echo "<input type = 'tel' id = 'telnum' name = 'telnum' placeholder = 'Phone Number (Format: 09983900813)' autocomplete = 'off' maxlength = '11'>";
                    }

                    echo "<input type = 'text' name = 'address' placeholder = 'Address' autocomplete = 'off'>";

                    if (isset($_GET['email'])) {
                        $email = $_GET['email'];
                        echo "<input type = 'text' placeholder = 'E-mail' name = 'email' value = '$email' autocomplete = 'off'>";
                    } else {
                        echo "<input type = 'text' placeholder = 'E-mail' name = 'email' autocomplete = 'off'>";
                    }

                    ?>

                    <div class="passwords">
                        <div class = 'password-div'>
                            <input type='password' placeholder='Password (Must be 8 characters or more)' name='pass'>
                            <div class = 'eye-div'>
                                <i class="far fa-eye"></i>
                            </div>
                        </div>
                        <div class="password-div">
                            <input type='password' placeholder='Confirm Password' name='conf_pass'>
                            <div class="eye-div">
                                <i class="far fa-eye"></i>
                            </div>
                        </div>
                    </div>

                    <div class="employee-code">
                        <input type="password" placeholder='Employee Code (Only for Employees)' id='employee_code' name='employee_code' autocomplete='off'>
                        <div class='employee-dropdown'>
                            <select name='position'>
                                <option value='' disabled selected>Position</option>
                                <!-- <option value='doctor'>Doctor</option> -->
                                <option value='medtech'>MedTech</option>
                                <option value='nurse'>Nurse</option>
                            </select>
                        </div>
                    </div>

                    <div class='new-patient'>
                        <input type="checkbox" id="new-patient" name="new-patient">
                        <label for="new-patient">Check if you are a new patient from Twin Care</label><br>
                    </div>
                    <button type='submit'>Register</button>
                    <?php
                    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                    if (strpos($url, "fields=empty") == true) {
                        echo "<span class = 'error-msg'>Field/s empty!</span>";
                    } else if (strpos($url, "name=invalid") == true) {
                        echo "<span class = 'error-msg'>Name entered contains invalid characters</span>";
                    } else if (strpos($url, "pnumber=invalid") == true) {
                        echo "<span class = 'error-msg'>Phone Number entered is Invalid</span>";
                    } else if (strpos($url, "email=invalid") == true) {
                        echo "<span class = 'error-msg'>Email entered is invalid</span>";
                    } else if (strpos($url, "email=taken") == true) {
                        echo "<span class = 'error-msg'>Email entered is already taken</span>";
                    } else if (strpos($url, "confpass=invalid") == true) {
                        echo "<span class = 'error-msg'>Passwords don't match</span>";
                    } else if (strpos($url, "pass=invalid") == true) {
                        echo "<span class = 'error-msg'>Password should contain 8 or more characters</span>";
                    } else if (strpos($url, "empcode=invalid") == true) {
                        echo "<span class = 'error-msg'>Wrong Employee Code. Try again.</span>";
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>

    <div class='attribution'>
        <a href='https://www.freepik.com/vectors/family'>Family vector created by pch.vector - www.freepik.com</a>
    </div>
    <!-- Messenger Chat Plugin Code -->
    <div id="fb-root"></div>

    <!-- Your Chat Plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "100555252523932");
        chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code -->
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v12.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

</body>
<script src='js/login-and-register.js'></script>
<script src="js/forgot-pass.js"></script>

</html>