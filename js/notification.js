$(document).ready(function () {
    $(".notification-btn").click(function () {
        $(".notification-box").animate({
            height: "toggle",
            opacity: "toggle"
        }, "fast")
    })

    $(".notif-content").on('click', function () {
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
            query = "UPDATE patients_notifications SET seen = '1' WHERE appointment_num = '" + num + "'";
        }
        else if (notiftype === 'doc-notif-type') {
            query = "UPDATE patients_notifications SET seen = '1' WHERE document_num = '" + num + "'";
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

    $('.doc-notif-type').on('click', function () {
        docnum = $(this).val();
        window.location.href = 'patient-prescription.php?docnum=' + docnum;
    })
})