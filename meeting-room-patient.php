<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/meeting.css'>
    <title>Document</title>
    <script src='https://meet.jit.si/external_api.js'></script>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    ?>

    <div class='header-nav'>
        <div class='display-flex'>
            <div class='header-logo'>
                <img src='img/logo-2-meet.png'>
            </div>
        </div>
        <div>
            <button onclick="window.location.href = 'patient-homepage.php'">Go Back</button>
        </div>
    </div>

    <div id='meeting-window'>

    </div>
</body>

<script>
    var container = document.querySelector('#meeting-window');
    var api = null;

    let searchParams = new URLSearchParams(window.location.search);
    randomString = searchParams.get('meetlink');

    var domain = "meet.jit.si";
    var options = {
        "roomName": randomString,
        "parentNode": container,
        userInfo: {
            displayName: 'Patient'
        },
        configOverwrite: {
            remoteVideoMenu: {
                // If set to true the 'Kick out' button will be disabled.
                disableKick: true,
                // If set to true the 'Grant moderator' button will be disabled.
                disableGrantModerator: true
            },
            disableRemoteMute: true,
                toolbarButtons: [
                    'camera',
                    'chat',
                    'closedcaptions',
                    'desktop',
                    // 'download',
                    // 'embedmeeting',
                    'etherpad',
                    // 'feedback',
                    'filmstrip',
                    'fullscreen',
                    'hangup',
                    'help',
                    // 'invite',
                    // 'livestreaming',
                    'microphone',
                    // 'mute-everyone',
                    // 'mute-video-everyone',
                    // 'participants-pane',
                    // 'profile',
                    'raisehand',
                    // 'recording',
                    // 'security',
                    'select-background',
                    // 'settings',
                    'shareaudio',
                    'sharedvideo',
                    // 'shortcuts',    
                    'stats',
                    'tileview',
                    'toggle-camera',
                    'videoquality',
                    '__end'
                ],
        }
    };
    
    api = new JitsiMeetExternalAPI(domain, options);
</script>

</html>