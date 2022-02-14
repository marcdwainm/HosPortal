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

<div class='profile-container'>
    <div class='logo-container'>
        <img src='img/logo-2.png' class='logo'>
    </div>
    <button id='dashboard-nurse'><i class="fas fa-chart-line fa-lg"></i><span>Dashboard</span></button>
    <button id='appointments-nurse'><i class="far fa-calendar-check fa-lg"></i><span>Appointments</span></button>
    <button id='patients-nurse'><i class="fas fa-hospital-user fa-lg"></i></i><span>Patients</span></button>
    <button id='documents-nurse'><i class="far fa-file-word fa-lg"></i><span>Documents</span></button>
    <button id='archive-nurse'><i class="fas fa-archive fa-lg"></i><span>Archive</span></button>
    <div class='settings-div'>
        <button id='settings-doctor'><i class="fas fa-cog fa-lg"></i><span>Settings</span></button>
        <div class='settings-dropdown'>
            <button id='change-pass-btn'><i class="fas fa-key"></i><span>Change Password</span></button>
        </div>
    </div>
    <button id='logout-nurse'><i class="fas fa-sign-out-alt fa-lg"></i><span>Logout</span></button>

    <div class='clock'>
        <span id='clock-greetings'></span>
        <span id='live-clock'></span>
        <span id="live-clock-date"></span>
    </div>
</div>