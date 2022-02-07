window.onload = displayClock();
function displayClock() {
    var span = document.getElementById('live-clock');
    var greetingSpan = document.getElementById('clock-greetings');
    var liveClockDate = document.getElementById('live-clock-date');
    var display = new Date();
    var curHr = display.getHours();
    var textGreeting = '';
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

    if (curHr < 12) {
        textGreeting = "Good Morning! It's"
    } else if (curHr < 18) {
        textGreeting = "Good Afternoon! It's"
    } else {
        textGreeting = "Good Evening It's"
    }
    
    span.innerHTML = display.toLocaleTimeString();
    greetingSpan.innerHTML = textGreeting;
    liveClockDate.innerHTML = display.toLocaleDateString("en-US", options);
    
    setTimeout(displayClock, 1000);
}


$(document).ready(function () {
    $("#appointments").on("click", function () {
        window.location.href = 'patient-homepage.php';
    })
    $("#appointments-doctor").on("click", function () {
        window.location.href = 'employee-homepage.php';
    })
    $("#appointments-nurse").on("click", function () {
        window.location.href = 'nurse-homepage.php';
    })

    $("#faqs").on('click', function(){
        window.location.href = 'patient-faqs.php'
    })
    
    
    $("#patients-doctor").on("click", function () {
        window.location.href = 'employee-patients.php';
    })
    $("#patients-nurse").on("click", function () {
        window.location.href = 'nurse-patients.php';
    })


    $("#documents").on("click", function () {
        window.location.href = 'patient-documents.php';
    })
    $("#documents-doctor").on("click", function () {
        window.location.href = 'employee-documents.php';
    })
    $("#documents-nurse").on("click", function () {
        window.location.href = 'nurse-documents.php';
    })


    $("#archive-doctor").on("click", function () {
        window.location.href = 'employee-archive.php';
    })
    $("#archive-nurse").on("click", function () {
        window.location.href = 'nurse-archive.php';
    })

    $('#lab-test-medtech').on('click', function () {
        window.location.href = 'employee-bills.php';
    })


    $("#bills-doctor").on("click", function () {
        window.location.href = 'employee-bills.php';
    })


    $('#settings-doctor').on('click', function () {
        $('.settings-dropdown').slideToggle();

        if ($('#logout-doctor').is(':visible') && $(document).width() > 767.98) {
            $('#logout-doctor').fadeOut(100)
        }
        else {
            setTimeout(function () { $('#logout-doctor').fadeIn() }, 300)
        }
    })



    $('#change-pass-btn').on('click', function () {
        $('.dim-change-pass').fadeIn();
    })

    $('#exit-change-pass').on('click', function () {
        $('.dim-change-pass').fadeOut();
    })



    $('#change-emp-code-btn').on('click', function () {
        $('.dim-change-emp-code').fadeIn();
    })

    $('#exit-change-emp-code').on('click', function () {
        $('.dim-change-emp-code').fadeOut();
    })





    $(document).on('click', '#toggle-appointment-booking-btn', function () {
        $.ajax({
            type: 'POST',
            url: 'php_processes/toggle-appointment-booking.php',
            success: function (result) {
                icon = result == 'set to 0' ? '<i class="far fa-calendar-times"></i><span>Toggle Appointment Booking</span>' : '<i class="far fa-calendar-check"></i><span>Toggle Appointment Booking</span>';
                $('#toggle-appointment-booking-btn').html(icon)

                if (result == 'set to 0') {
                    Swal.fire(
                        'Bookings disabled',
                        'Patients will not be allowed to book appointments until this option is turned on again.',
                        'success'
                    ).then((result) => { if (result.isConfirmed) { window.location.reload() } })
                }
                else {
                    Swal.fire(
                        'Bookings enabled',
                        'Users will now be allowed to book appointments',
                        'success'
                    ).then((result) => { if (result.isConfirmed) { window.location.reload() } })
                }
            }
        })
    })


    $(document).on('click', '#show-cancelled-btn', function () {
        $.ajax({
            type: 'POST',
            url: 'php_processes/toggle-cancelled-appointments.php',
            success: function (result) {
                icon = result == 'set to 0' ? '<i class="far fa-eye-slash"></i><span>Show Cancelled Appointments</span>' : '<i class="far fa-eye"></i><span>Hide Cancelled Appointments</span>';
                $('#show-cancelled-btn').html(icon)

                if (result == 'set to 0') {
                    Swal.fire(
                        'Hidden!',
                        'The calendar will stop showing cancelled appointments.',
                        'success'
                    ).then((result) => { if (result.isConfirmed) { window.location.reload() } })
                }
                else {
                    Swal.fire(
                        'Shown!',
                        'The calendar will start showing cancelled appointments.',
                        'success'
                    ).then((result) => { if (result.isConfirmed) { window.location.reload() } })
                }
            }
        })
    })

    $(document).on('keyup', '#current-password', function () {
        currPass = $(this).val();

        $.ajax({
            type: 'POST',
            url: 'php_processes/verify-current-pass.php',
            data: {
                currPass: currPass
            },
            success: function (result) {
                if (result == 'password correct') {
                    $('#change-pass').prop('disabled', false);
                } else if (result = 'password incorrect') {
                    $('#change-pass').prop('disabled', true);
                }
            }
        })
    })


    $(document).on('keyup', '#curr-emp-code', function () {
        currEmpCode = $(this).val();

        $.ajax({
            type: 'POST',
            url: 'php_processes/verify-current-emp-code.php',
            data: {
                currEmpCode: currEmpCode
            },
            success: function (result) {
                if (result == 'emp code correct') {
                    $('#change-emp-code').prop('disabled', false);
                } else if (result = 'emp code incorrect') {
                    $('#change-emp-code').prop('disabled', true);
                }
            }
        })
    })


    $(document).on('click', '#change-pass', function () {
        newPass = $('#new-pass').val();
        if ($('#new-pass').val() == '' || $('#conf-new-pass').val() == '') {
            $('.input-error').html('Field/s empty!')
        }
        else if ($('#new-pass').val() !== $('#conf-new-pass').val()) {
            $('.input-error').html('Passwords don\'t match!')
        }
        else {
            $('.dim-change-pass input').val('');
            $('.input-error').html('')
            $('.dim-change-pass').fadeOut();
            $('#change-pass').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: 'php_processes/change-pass.php',
                data: {
                    newPass: newPass
                },
                success: function (result) {
                    Swal.fire(
                        'Success!',
                        'Password successfully changed!',
                        'success'
                    )
                }
            })
        }
    })


    $(document).on('click', '#change-emp-code', function () {
        newEmpCode = $('#new-emp-code').val();
        confNewEmpCode = $('#conf-new-emp-code').val();
        if (newEmpCode == '' || confNewEmpCode == '') {
            $('.input-error').html('Field/s empty!')
        }
        else if (newEmpCode !== confNewEmpCode) {
            $('.input-error').html('Codes don\'t match!')
        }
        else {
            $('.dim-change-emp-code input').val('');
            $('.input-error').html('')
            $('.dim-change-emp-code').fadeOut();
            $('#change-emp-code').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: 'php_processes/change-emp-code.php',
                data: {
                    newEmpCode: newEmpCode
                },
                success: function (result) {
                    Swal.fire(
                        'Success!',
                        'Employee Code successfully changed!',
                        'success'
                    )
                }
            })
        }
    })


    $("#logout").add("#logout-doctor").add("#logout-nurse").click(function () {
        $.ajax({
            type: "GET",
            url: "php_processes/logout.php",
            data: '{}',

            success: function () {
                window.location.href = "index.php";
            }
        });
    });
});
