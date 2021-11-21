$(document).ready(function () {
    $('#search-patient').on('keyup', function () {
        val = $(this).val()

        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-list-ajax.php',
            data: {
                keyword: val
            },
            success: function (result) {
                $('.patient-tbl').html(result);
            }
        })
    })
})