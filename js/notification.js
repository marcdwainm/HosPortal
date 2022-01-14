
$(document).ready(function () {
    // intervalNotifDoc = setInterval(function () {
    //     $.ajax({
    //         type: 'POST',
    //         url: 'extras/doctor-notifications.php',
    //         success: function (result) {
    //             $('.notif-contents-doc').html(result);
    //             unseen_count = $('.notif-contents-doc .notif-content .seen .seen-circle').length
    //             $('.notification-num span').html('ad')
    //         }
    //     })
    // }, 500)


    intervalNotif = setInterval(function () {
        $.ajax({
            type: 'POST',
            url: 'php_processes/count-notifs.php',
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
            url: 'extras/patient-notifications.php',
            success: function (result) {
                $('.notif-contents').html(result);
            }
        })

        $(".notification-box").animate({
            height: "toggle",
            opacity: "toggle"
        }, "fast")
    })

    $(document).on('click', ".notif-content, .online-req-notif-type", function () {
        value = $(this).val();
        $(this).children('.seen').children(".seen-circle").remove();

        $.ajax({
            type: 'POST',
            url: 'php_processes/seen-notif.php',
            data: {
                value: value
            },
            success: function (result) {
                console.log(result)
            }
        })
    })

    $(document).on('click', ".notif-doc", function () {
        $(this).children('.seen').children(".seen-circle").remove();
        notifnum = $(".notification-num span").html();

        if (notifnum !== '0') {
            $(".notification-num span").html(notifnum - 1);
        }

        //IDENTIFY WHAT TYPE OF APPOINTMENT IT IS
        num = $(this).val();
        notiftype = $(this).attr('class').split(' ')[1]
        query = '';

        if (notiftype === 'cancel-notif-type') {
            query = "UPDATE notifications SET seen = '1' WHERE appointment_num = '" + num + "'";
        }
        else if (notiftype === 'book-notif-type') {
            query = "UPDATE notifications SET seen = '1' WHERE appointment_num = '" + num + "'";
        }

        $.ajax({
            type: 'POST',
            url: 'php_processes/seen-notif.php',
            data: {
                query: query
            },
            success: function (result) {
                console.log(result);
            }
        })
    })

    $(document).on('click', '.doc-notif-type', function () {
        docnum = $(this).val();
        window.location.href = 'patient-prescription.php?docnum=' + docnum;
    })
})