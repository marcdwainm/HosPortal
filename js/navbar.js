$(document).ready(function () {
    if ($(this).width() >= 925) {
        $('#check-prof').prop('checked', true)
        $('.profile-container').css({
            'transform': 'translateY(0%)',
            'transition': '0.3s ease-in-out'
        });
    }
    else {
        $('#check-prof').prop('checked', false)
        $('.profile-container').css({
            'transform': 'translateY(-101%)',
        });
    }


    $(window).on("resize", function () {
        if ($(this).width() >= 925) {
            $('#check-prof').prop('checked', true)
            $('.profile-container').css({
                'transform': 'translateY(0%)',
                'transition': '0.3s ease-in-out'
            });
        }

        if ($(this).width() < 925) {
            $('#check-prof').prop('checked', false)
            $('.profile-container').css({
                'transform': 'translateY(-101%)',
                'transition': '0.3s ease-in-out'
            });
        }
    });

    $('#check-prof').click(function () {
        if ($(this).is(':checked')) {
            $('.profile-container').css({
                'transform': 'translateY(0%)',
                'transition': '0.3s ease-in-out'
            });
            console.log('checked')
        }
        else if ($(this).is(':not(:checked')) {
            $('.profile-container').css({
                'transform': 'translateY(-100%)',
                'transition': '0.3s ease-in-out'
            });
            console.log('unchecked')
        }
    })

    $("#appointments").on("click", function () {
        window.location.href = 'patient-homepage.php';
    })

    $("#documents").on("click", function () {
        window.location.href = 'patient-documents.php';
    })

    $("#logout").click(function () {
        $.ajax({
            type: "GET",
            url: "php_processes/logout.php",
            data: '{}',

            success: function () {
                window.location.href = "index.php";
            },
            failure: function () {

            }
        });
    });
});