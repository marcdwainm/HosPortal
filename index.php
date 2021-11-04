<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = 'stylesheet' type = 'text/css' href = 'css/index.css'>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>
<body>
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe> <!--THIS INVISIBLE IFRAME IS FOR PREVENTING FORM REDIRECTIONS-->

    <div class = 'background'>
        <div class = 'login-form'>
            <div class = 'img-mockup'>
                <img src = 'img/login-mockup.png'>
            </div>
            <div class = 'header'>
                <div class = 'header-logo'>
                    <img src = 'img/logo-2.png'>
                </div>
                <span class = 'contact-num'><i class="fas fa-phone-alt"></i> 0998-390-0813</span>
            </div>
            
            <form class = 'l-form' action = 'php_processes/login.php' method = 'POST'>
                <span class = 'form-header'>Login to your account</span>
                <div class = 'login-inputs'>
                    <?php
                        if(isset($_GET['loginemail'])){
                            $email = $_GET['loginemail'];
                            echo "<input type = 'text' placeholder = 'E-mail' name = 'login-email' value = '$email'>";
                        }
                        else{
                            echo "<input type = 'text' placeholder = 'E-mail' name = 'login-email'>";
                        }
                    ?>
                    
                    <input type = 'password' placeholder = 'Password' name = 'login-pass'>
                    <button>Login</button>
                    <span class="error-span">
                        <?php
                            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                            if(strpos($url, "fieldslogin=empty") == true){
                                echo 'Field/s empty!';
                            }
                            else if(strpos($url, "emaillogin=invalid") == true){
                                 echo 'Email input is not an email';
                            }
                            else if(strpos($url, "acc=notfound") == true){
                                echo 'Such account does not exist';
                            }
                        ?>
                    </span>
                </div>
                <div class = 'login-links'>
                    <a href = '#' id = 'register'>Create an Account</a>
                    <a href = '#'>Forgot your Password?</a>
                </div>
                <span class = 'footer'>Twin Care Portal. Copyright 2021.</span>
            </form>
        </div>




        <div class = 'reg-form'>
            <div class = 'img-mockup-reg'>
                <img src = 'img/reg-mockup.png'>
            </div>
            <div class = 'header'>
                <div class = 'header-logo'>
                    <img src = 'img/logo-2.png'>
                </div>
                <span class = 'contact-num'><i class="fas fa-phone-alt"></i> 0998-390-0813</span>
            </div>
            
            <form class = 'r-form' action = 'php_processes/registration.php' method = 'POST'>
                <div class = 'back-header'>
                    <button id = 'back-btn' type = 'button'><i class="fas fa-arrow-left"></i></button>
                    <span class = 'form-header'>Register Now!</span>
                </div>

                <div class = 'login-inputs'>
                    <div class = 'fullname'>
                        <?php
                        //FIRSTNAME
                        if (isset($_GET['fname'])){
                            $fname = $_GET['fname'];
                            echo "<input type = 'text' placeholder = 'First Name' name = 'firstname' value = '$fname'>";

                        }
                        else{
                            echo "<input type = 'text' placeholder = 'First Name' name = 'firstname'>";
                        }
                        //MIDDLENAME
                        if (isset($_GET['mname'])){
                            $mname = $_GET['mname'];
                            echo "<input type = 'text' placeholder = 'Middle Name' name = 'middlename' value = '$mname'>";
                        }
                        else{
                            echo "<input type = 'text' placeholder = 'Middle Name' name = 'middlename'>";
                        } 
                        //LASTNAME
                        if (isset($_GET['lname'])){
                            $lname = $_GET['lname'];
                            echo "<input type = 'text' placeholder = 'Last Name' name = 'lastname' value = '$lname'>";
                            
                        }
                        else{
                            echo "<input type = 'text' placeholder = 'Last Name' name = 'lastname'>";
                        }
                        ?>
                    </div>
                    <div class = 'sex-tel' aria-placeholder="gender">
                        <div class = 'select-wrapper'>
                            <select name = 'sex'>
                                <?php
                                        if($_GET['sex'] === 'male'){
                                            echo "
                                                <option value = '' disabled>Sex</option>
                                                <option value = 'male' selected>Male</option>
                                                <option value = 'female'>Female</option>
                                            ";
                                        }
                                        else if($_GET['sex'] === 'female'){
                                            echo "
                                                <option value = '' disabled>Sex</option>
                                                <option value = 'male'>Male</option>
                                                <option value = 'female' selected>Female</option>
                                            ";
                                        }
                                        else{
                                            echo "
                                            <option value = '' disabled selected>Sex</option>
                                            <option value = 'male'>Male</option>
                                            <option value = 'female'>Female</option>"
                                            ;
                                        }
                                ?>
                            </select>
                        </div>
                        <input type = 'text' class = 'birthdate' placeholder = 'Birthdate' onfocus="(this.type='date')" name = 'bdate'>
                    </div>

                    <?php
                    if(isset($_GET['telnum'])){
                        $telnum = $_GET['telnum'];
                        echo "<input type = 'tel' name = 'telnum' placeholder = 'Phone Number' value = '$telnum'>";
                    }
                    else{
                        echo "<input type = 'tel' name = 'telnum' placeholder = 'Phone Number'>";
                    }

                    if(isset($_GET['email'])){
                        $email = $_GET['email'];
                        echo "<input type = 'text' placeholder = 'E-mail' name = 'email' value = '$email'>";
                    }
                    else{
                        echo "<input type = 'text' placeholder = 'E-mail' name = 'email'>";
                    }
                         
                    ?>

                    <div class="passwords">
                        <input type = 'password' placeholder = 'Password' name = 'pass'>
                        <input type = 'password' placeholder = 'Confirm Password' name = 'conf_pass'>
                    </div>

                    <div class="employee-code">
                        <input type = "password" placeholder = 'Employee Code (Only for Employees)' id = 'employee_code' name = 'employee_code'>
                        <div class = 'employee-dropdown'>
                            <select name = 'position'>
                                <option value = '' disabled selected>Position</option>
                                <option value = 'doctor'>Doctor</option>
                                <option value = 'medtech'>MedTech</option>
                                <option value = 'nurse'>Nurse</option>
                            </select>
                        </div>
                    </div>
                    <button type = 'submit'>Register</button>
                    
                    <?php
                        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                        if(strpos($url, "fields=empty") == true){
                            echo "<span class = 'error-msg'>Field/s empty!</span>";
                        }
                        else if(strpos($url, "name=invalid") == true){
                            echo "<span class = 'error-msg'>Name entered contains invalid characters</span>";
                        }
                        else if(strpos($url, "pnumber=invalid") == true){
                            echo "<span class = 'error-msg'>Phone Number entered is Invalid</span>";
                        }
                        else if(strpos($url, "email=invalid") == true){
                            echo "<span class = 'error-msg'>Email entered is invalid</span>";
                        }
                        else if(strpos($url, "email=taken") == true){
                            echo "<span class = 'error-msg'>Email entered is already taken</span>";
                        }
                        else if(strpos($url, "confpass=invalid") == true){
                            echo "<span class = 'error-msg'>Passwords don't match</span>";
                        }
                        else if(strpos($url, "pass=invalid") == true){
                            echo "<span class = 'error-msg'>Password should contain 8 or more characters</span>";
                        }
                        else if(strpos($url, "empcode=invalid") == true){
                            echo "<span class = 'error-msg'>Wrong Employee Code. Try again.</span>";
                        }
                    ?>
                </div>
            </form>
        </div>
    </div>
</body>
<script src = 'js/login-and-register.js'></script>
</html>