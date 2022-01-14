$(document).ready(function () {
    $('#forgot-pass').on('click', function () {
        $('.dim-forgot-pass').fadeIn();
    })

    $('.forgot-pass-exit').on('click', function () {
        $('.dim-forgot-pass').fadeOut()
        $('.email-not-found').hide();
        $('#email-input-forgot').val('')
    })

    $('#forgot-pass-submit').on('click', function () {
        emailInput = $('#email-input-forgot').val();
        var randomString = randomStringGenerate();
        $.ajax({
            type: 'POST',
            url: 'php_processes/forgot-pass.php',
            data: {
                emailInput: emailInput,
                randomString: randomString
            },
            success: function (result) {
                if (result == 'email not found') {
                    $('.email-not-found').show();
                }
                else if (result == 'on reset') {
                    $('.dim-forgot-pass').fadeOut();
                    Swal.fire(
                        'Invalid',
                        'A mail has already been sent to this e-mail. Kindly check the e-mail and click the link attached.',
                        'error'
                    )
                }
                else {
                    $('.dim-forgot-pass').fadeOut();
                    Swal.fire(
                        'E-mail sent!',
                        'Check your e-mail and click the link we sent you.',
                        'success'
                    )
                }
            }
        })
    })

    $('#reset-pass-submit').on('click', function () {
        var searchParams = new URLSearchParams(window.location.search);
        newPass = $('#new-pass').val();
        confNewPass = $('#conf-new-pass').val();
        resetKey = searchParams.get('resetKey');

        if (newPass != confNewPass) {
            $('.invalid-pass').show();
        }
        else {
            $.ajax({
                type: 'POST',
                url: 'php_processes/reset-pass.php',
                data: {
                    newPass: newPass,
                    resetKey: resetKey
                }, success: function (result) {
                    console.log(result)
                    $('.dim-reset-pass').fadeOut();
                    Swal.fire(
                        'Success!',
                        'The password has been reset',
                        'success'
                    )
                }

            })
        }
    })

    function randomStringGenerate() {
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var stringLength = 30;

        function pickRandom() {
            return possible[Math.floor(Math.random() * possible.length)];
        }

        var randomString = Array.apply(null, Array(stringLength)).map(pickRandom).join('');
        return randomString;
    }
})