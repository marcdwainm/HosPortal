$(document).ready(function () {
    $('#book-appointment').click(function () {
        $('.dim').fadeIn();
        $('.date-time-input').val('');
        $('.description').val('');
        $('#book').prop('disabled', true)
    });

    $('.exit').click(function () {
        $('.dim').fadeOut();
        $('.date-time-input').val('');
        $('.description').val('');
    })


    $('#book').on('click', function () {
        dt = $("#appointment-date-time").val();
        desc = $(".description").val();

        $.ajax({
            type: "POST",
            url: "php_processes/book-appointment.php",
            data: {
                'appointment-date-time': dt,
                'description': desc
            },
            success: function (result) {
                $('#book-appointment').prop('disabled', true);
                $('.dim').fadeOut();
                Swal.fire(
                    'Success!',
                    'Your appointment is now on queue!',
                    'success'
                )
                $("#appt-table").html(result);


            },
            error: function (result) {
                alert('error');
            }
        })
    })

    $('.book-content').on('input', function () {
        $('#book').prop('disabled', false)
    })

    flatpickr("#appointment-date-time", {
        enableTime: true,
        defaultHour: 9,
        defaultMinute: 0,
        dateFormat: "Y-m-d H:i:s",
        altInput: true,
        altFormat: "F j, Y l (h:S K)",
        minDate: new Date().fp_incr(1),
        maxDate: new Date().fp_incr(40),
        minTime: "09:00",
        maxTime: "16:00",
        disable: [
            function (date) {
                return (date.getDay() === 0);
            }
        ]
    });


})