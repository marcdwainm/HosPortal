<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/contents.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Twin Care Portal | Documents</title>
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    $position = $_SESSION['position'];
    if ($position != 'patient') {
        if ($postition == 'doctor') {
            header("Location: employee-homepage.php");
        } else {
            header("Location: $position-homepage.php");
        }
    }

    include 'extras/patient-profile.php';

    ?>

    <div class='notif-live'>
        <div class="notification-area">
            <div class="notification-box">
                <div class='notif-header'>
                    <span>Notifications</span>
                </div>
                <div class="notif-contents">
                    <!--DYNAMIC NOTIFS-->
                </div>
            </div>
            <div class="notification-num"><span></span></div>
            <div class="notification-btn">
                <i class="far fa-bell"></i>
            </div>
        </div>
    </div>

    <div class='background-container'>

        <div class='contents'>
            <h1>FAQs</h1>
            <br/>

            <h2 class = 'question'>Q: What will happen after I register an account?</h2>
            <div class = 'answer-span'>A: Check your email for confirmation and then simply click URL provided to get your account validated.</div>
            
            <br/>
            <br/>

            <h2 class = 'question'>Q: What should I do if I do not have an email address?</h2>
            <div class = 'answer-span'>A: The email address is the minimum requirement for registration to prevent malicious accounts and spamming. However, you can seek help from others who have an email account.
            You can also ask for help from the Twin Care Portal's messenger feature by clicking the icon on the bottom right side of the page.</div>
            
            <br/>
            <br/>

            <h2 class = 'question'>Q: How will i pay?</h2>
            <div class = 'answer-span'>A: You may pay using your debit/credit card or through PayPal. If you do not have access to those, people are also encouraged to process their payments through face-to-face engagements.</div>

            <br/>
            <br/>

            <h2 class = 'question'>Q: Can i view my medical documents even if i'm not paid?</h2>
            <div class = 'answer-span'>A: No, you must first pay your specified medical bills sent to you registered account on the web portal before being able to view or download your medical docuemnts.</div>
            
            <br/>
            <br/>

            <h2 class = 'question'>Q: Is my information on the Portal secure?</h2>
            <div class = 'answer-span'>A: Yes. The portal uses the Cloudflare, SSL connection, and password hash for secured connections and patient data protection. Twin Care Portal also complies with the Data Privacy Act of 2012 (RA 10173).</div>

            <br/>
            <br/>

            <h2 class = 'question'>Q: What if I forgot my password?</h2>
            <div class = 'answer-span'>A: On the Portal's log-in page, click the “Forgot your password?”, and follow the instructions.</div>

            <br/>
            <br/>

            <h2 class = 'question'>Q: Can I change my password?</h2>
            <div class = 'answer-span'>A: Yes. After logging in, find the Settings tab and simply click "Change passoword".</div>
            
            <br/>
            <br/>

            <h2 class = 'question'>Q: How to book my appointment?</h2>
            <div class = 'answer-span'>A: After logging in, on the homepage click the "Book Appointment" button which can be seen at the top right side below the notification/bell button. From there, simply fill up the required fields and book your appointment. </div>
            <div class = 'answer-span'>Note: You may only book appointments once there is no existing appointment to be held. Also, booking appointments is limited to five times a day.</div>

            <br/>
            <br/>

            <h2 class = 'question'>Q: How to find my medical documents such as laboratory results?</h2>
            <div class = 'answer-span'>A: You can find your medical documents by clicking the documents tab on the side panel navigation on the left side of the screen. From there, you can access your medical documents as well as the on going lab tests.</div>

            <br/>
            <br/>

            <h2 class = 'question'>Q: How can i join in online consultation?</h2>
            <div class = 'answer-span'>A: You will be notified once the doctor initiated or created a chat room thus clicking the three vertical dots on the right hand part on your appointment row. From there, you will see a "Join Chatroom" button and upon clicking, will take you to the video-communication service. </div>
            <div class = 'answer-span'>Note: No emails or accounts to be registered. You only need to enter your name for identity verification</div>

            <br/>
            <br/>

            <h2 class = 'question'>Q: I changed my mind. Can I cancel or change my schedule?</h2>
            <div class = 'answer-span'>A: Yes, you can cancel or change your schedule. Create another booking with your new preferred date and time.</div>

            <br/>
            <br/>

            <h2 class = 'question'>Q: Once i already book an appointment can i book another appointment?</h2>
            <div class = 'answer-span'>A: No, patients are only allowed to book appointments once there is no appointment to be held. Thus, after that exisiting appointment is done, you may now again book an appointment. You may only book five appointments per day.</div>
            
            <br/>
            <br/>

            <h2 class = 'question'>Q: What should i do if i missed my appointment?</h2>
            <div class = 'answer-span'>A: If you do missed your appointment, the Doctor will mark that as missed which will also mean as cancelled. Thus, you will have to book another appointment to seek medical attention. </div>

        </div>
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
<script src='js/navbar.js'></script>
<script src='js/book-appointment.js'></script>
<script src='js/patient-documents.js'></script>
<script src="js/notification.js"></script>

</html>