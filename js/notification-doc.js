
$(document).ready(function () {
    intervalNotifDoc = setInterval(function () {
        $.ajax({
            type: 'POST',
            url: 'php_processes/count-notifs-doctor.php',
            success: function (result) {
                if (result != '0') {
                    $('.notification-num span').html(result);
                }
                else {
                    $('.notification-num').hide();
                }
            }
        })
    }, 500)


    $(document).on('click', ".notification-btn", function () {
        $.ajax({
            type: 'POST',
            url: 'extras/doctor-notifications.php',
            success: function (result) {
                $('.notif-contents-doc').html(result);
            }
        })

        $(".notification-box").animate({
            height: "toggle",
            opacity: "toggle"
        }, "fast")
    })

    $(document).on('click', ".notif-doc", function () {
        value = $(this).val();
        $(this).children('.seen').children(".seen-circle").remove();

        //IDENTIFY WHAT TYPE OF APPOINTMENT IT IS
        $.ajax({
            type: 'POST',
            url: 'php_processes/seen-notif-doc.php',
            data: {
                value: value
            }
        })
    })
})