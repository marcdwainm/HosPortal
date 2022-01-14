<!--CHANGE PASSWORD-->
<div class="dim-change-pass">
    <div class='change-pass-container'>
        <div class='book-header-exit'>
            <span>Change Password</span>
            <span class='exit' id='exit-change-pass'>X</span>
        </div>
        <div>
            <input id='current-password' type='password' placeholder="Enter your current password" autocomplete="off">
            <input id='new-pass' type='password' placeholder="Enter your new password" autocomplete="off">
            <input id='conf-new-pass' type='password' placeholder="Confirm your new password" autocomplete="off">
            <span class='input-error'></span>
            <button id='change-pass' disabled>Change Password</button>
        </div>
    </div>
</div>

<!--CHANGE EMPLOYEE CODE-->
<div class="dim-change-emp-code">
    <div class='change-pass-container'>
        <div class='book-header-exit'>
            <span>Change Employee Code</span>
            <span class='exit' id='exit-change-emp-code'>X</span>
        </div>
        <div>
            <input id='curr-emp-code' type='password' placeholder="Enter current employee code">
            <input id='new-emp-code' type='password' placeholder="Enter new employee code">
            <input id='conf-new-emp-code' type='password' placeholder="Confirm new employee code">
            <span class='input-error'></span>
            <button id='change-emp-code' disabled>Change Employee Code</button>
        </div>
    </div>
</div>



<div class='profile-container'>
    <div class='logo-container'>
        <img src='img/logo-2.png' class='logo'>
    </div>
    <button id='appointments-doctor'><i class="far fa-calendar-check fa-lg"></i><span>Appointments</span></button>
    <button id='patients-doctor'><i class="fas fa-hospital-user fa-lg"></i></i><span>Patients</span></button>
    <button id='documents-doctor'><i class="far fa-file-word fa-lg"></i><span>Documents</span></button>
    <button id='archive-doctor'><i class="fas fa-archive fa-lg"></i><span>Archive</span></button>
    <button id='bills-doctor'><i class="far fa-money-bill-alt fa-lg"></i><span>Bills</span></button>
    <div class='settings-div'>
        <button id='settings-doctor'><i class="fas fa-cog fa-lg"></i><span>Settings</span></button>
        <div class='settings-dropdown'>
            <button id='change-pass-btn'><i class="fas fa-key"></i><span>Change Password</span></button>
            <button id='change-emp-code-btn'><i class="fas fa-user-lock"></i><span>Change Employee Code</span></button>
            <button id='toggle-appointment-booking-btn'>
                <?php
                include 'php_processes/db_conn.php';
                $query = 'SELECT toggle FROM appointment_booking_toggle';
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_array($result);
                echo ($row['toggle'] == '0') ? '<i class="far fa-calendar-times"></i>' : '<i class="far fa-calendar-check"></i>';
                ?>
                <span>Toggle Appointment Booking</span>
            </button>
            <button id='show-cancelled-btn'>
                <?php
                $query = 'SELECT toggle FROM show_cancelled_appointments';
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_array($result);
                echo ($row['toggle'] == '0') ? '<i class="far fa-eye-slash"></i><span>Show Cancelled Appointments</span>' : '<i class="far fa-eye"></i><span>Hide Cancelled Appointments</span>';
                ?>
            </button>
        </div>
    </div>
    <button id='logout-doctor'><i class="fas fa-sign-out-alt fa-lg"></i><span>Logout</span></button>
    <div class='clock'>
        <span id='clock-greetings'></span>
        <span id='live-clock'></span>
        <span id="live-clock-date"></span>
    </div>
</div>