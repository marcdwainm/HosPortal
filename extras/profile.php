<?php session_start(); ?>

<div class='profile-container'>
    <div class='profile-picture'>
        <div class='profile-pic-div'>
            <img src='img/profile-picture.jpg'>
        </div>
    </div>
    <div class='profile-details'>
        <div class='full-name'>
            <h1><?php echo $_SESSION['fullname']; ?></h1>
            <span><?php echo ucfirst($_SESSION['position']); ?></span>
        </div>
        <div class='contact-details'>
            <div class='contact-header'>
                <h3>Contact Details</h3>
            </div>
            <div class='detail'>
                <span>Mobile No.:</span>
                <span class='blue-span'><?php echo $_SESSION['contact']; ?></span>
            </div>
            <div class='detail'>
                <span>E-mail:</span>
                <span class='blue-span'><?php echo $_SESSION['email']; ?></span>
            </div>
        </div>
    </div>
</div>