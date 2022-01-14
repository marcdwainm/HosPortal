function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if ((charCode < 48 || charCode > 57))
        return false;

    return true;
}

$(document).ready(function () {

    $("#telnum").on('keypress', function (e) {
        return isNumberKey(e)
    })

    $('#register').click(function () {
        $('.login-form').fadeOut(300, function () {
            $('.reg-form').fadeIn(300);
        });
    })

    $('#back-btn').click(function () {
        window.location.href = "index.php";
    })

    $('#employee_code').keyup(function () {
        var keyword = $(this).val();

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (xhr.responseText == 'found') {
                    $('.employee-dropdown').show();
                    $('.employee-code').css({
                        'grid-template-columns': '1fr 1fr',
                        'grid-gap': '10px'
                    })
                }
                else if (xhr.responseText == 'not found') {
                    $('.employee-dropdown').hide();
                    $('.employee-code').css({
                        'grid-template-columns': '1fr'
                    })
                }
            }
        }

        xhr.open("GET", "php_processes/employee-code.php?code=" + keyword, true);
        xhr.send();
    })


    let searchParams = new URLSearchParams(window.location.search);

    if (searchParams.get('success') == "false") {
        $('.login-form').hide(1, function () {
            $('.reg-form').show();
        });
    }
    else if (searchParams.get('success') == "true") {
        $('.login-form').show();
        $('.reg-form').hide();
        Swal.fire(
            'Congrats!',
            'You are now registered!',
            'success'
        )
    }

})