$(document).ready(function () {
    var interval;

    $('#upload-document').on('click', function () {
        $('.add-document-overlay, .document-upload-container').fadeIn();
        $('.p-name-documents-autocomplete').hide();
        interval = setInterval(checkInputs, 100);
    })

    $('.add-document-overlay').on('click', function () {
        $('.add-document-overlay, .document-upload-container').fadeOut();
    })

    $('.exit').on('click', function () {
        $('.add-document-overlay, .document-upload-container').fadeOut();
        $("#patient-search").val("")
        $('#document-type').val("default")
        $('#file').val(null);
        $('#file').hide();
        $('#upload-from-device').show();
        $('#file-to-database').hide();

        clearInterval(interval);
    })


    $('#patient-search').on('keyup', function () {
        keyword = $(this).val()

        //If patient is portal resgitered
        if ($('#portal-registered').is(':checked')) {
            if (keyword === '') {
                $('.p-name-documents-autocomplete').hide();
                $('#patient-error').hide()
            } else {
                $('.p-name-documents-autocomplete').show();
                $.ajax({
                    type: "POST",
                    url: "php_processes/documents-patient-search.php",
                    data: {
                        query: keyword
                    },
                    success: function (result) {
                        $('.p-name-documents-autocomplete').html(result);

                        if (result === '') {
                            $('#patient-error').show()
                            $('.p-name-documents-autocomplete').hide();
                        }
                        else {
                            $('#patient-error').hide()
                        }
                    }
                })
            }
        } else {
            // DO NOTHING
        }
    })

    $('#portal-registered').on('click', function () {
        if ($(this).is(':checked')) {
            keyword = $('#patient-search').val()

            if (keyword === '') {
                $('.p-name-documents-autocomplete').hide();
                $('#patient-error').hide()
            } else {
                $('.p-name-documents-autocomplete').show();
                $.ajax({
                    type: "POST",
                    url: "php_processes/documents-patient-search.php",
                    data: {
                        query: keyword
                    },
                    success: function (result) {
                        $('.p-name-documents-autocomplete').html(result);

                        if (result === '') {
                            $('#patient-error').show()
                            $('.p-name-documents-autocomplete').hide();
                        }
                        else {
                            $('#patient-error').hide()
                        }
                    }
                })
            }
        }
        else {
            $('#upload-from-device').val('0000');
            $('#file-to-database').val('0000');
            $('#generate-document').val('0000');
            $('#patient-error').hide();
            $('.p-name-documents-autocomplete').hide();
        }
    })

    $('#generate-document').on('click', function () {
        docType = $('#document-type').val();
        pid = $('#generate-document').val();
        pname = $('#patient-search').val();
        var redirect = '';

        if (pid === '0000') {
            redirect = window.open('extras/prescription-labresult.php?docType=' + docType + '&pid=' + pid + '&pname=' + pname, '_blank', 'location=yes,height=800,width=1000,scrollbars=yes,status=yes')
        } else {
            redirect = window.open('extras/prescription-labresult.php?docType=' + docType + '&pid=' + pid + '&pname=' + pname, '_blank', 'location=yes,height=800,width=1000,scrollbars=yes,status=yes')
        }
        redirect.location;

        $('#file').hide();
        $('#file').val(null);
        $('.add-document-overlay, .document-upload-container').fadeOut();
        $('#upload-from-device').show();
        $('#file-to-database').hide();
        $("#patient-search").val("")
        $('#document-type').val("default")

        clearInterval(interval);
    })

    $(document).on('click', '.result-autocomplete', function () {
        userfullname = $(this).children(':first').html();
        userid = $(this).val();

        $('#patient-search').val(userfullname)
        $('.p-name-documents-autocomplete').hide()
        $('#generate-document').val(userid);
        $('#upload-from-device').val(userid);
    })

    $('#upload-from-device').on('click', function () {
        $('#file').show();
    })

    $('#file').on('input', function () {
        $('#upload-from-device').hide();
        $('#file-to-database').show()
    })

    document.getElementById('file').addEventListener('change', handleFileSelect, false);
    var base64String;

    function handleFileSelect(evt) {
        var f = evt.target.files[0]; // FileList object
        var reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = (function (theFile) {
            return function (e) {
                var binaryData = e.target.result;
                //Converting Binary Data to base 64
                base64String = window.btoa(binaryData);
                //showing file converted to base64
            };
        })(f);
        // Read in the image file as a data URL.
        reader.readAsBinaryString(f);
    }

    $('#file-to-database').on('click', function () {
        let doctype = $('#document-type').val();
        let base64 = base64String;
        let sentTo = $('#upload-from-device').val();
        let pname = $('#patient-search').val();

        //If patient is not portal registered
        if (sentTo === '0000') {
            Swal.fire({
                title: 'Are you sure?',
                text: "The patient you entered is not portal-registered. Proceed?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Upload!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/upload-document.php',
                        data: {
                            base64: base64,
                            doctype: doctype,
                            sentTo: sentTo,
                            pname: pname
                        },
                        success: function (result) {
                            $('#patient-search').val('');
                            $('#document-type').val('default');
                            $('#file').val(null);
                            $('#file').hide();
                            $('#upload-from-device').show();
                            $('#file-to-database').hide();
                            $('.add-document-overlay').fadeOut();
                            $('.document-upload-container').fadeOut();

                            Swal.fire(
                                'Uploaded!',
                                'Your file is now in the database.',
                                'success'
                            )
                        }
                    })
                }
            })
        }
        else if (sentTo !== '0000') {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will upload the file to the database!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Upload!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/upload-document.php',
                        data: {
                            base64: base64,
                            doctype: doctype,
                            sentTo: sentTo
                        },
                        success: function (result) {
                            $('#patient-search').val('');
                            $('#document-type').val('default');
                            $('#file').val(null);
                            $('#file').hide();
                            $('#upload-from-device').show();
                            $('#file-to-database').hide();
                            $('.add-document-overlay').fadeOut();
                            $('.document-upload-container').fadeOut();

                            Swal.fire(
                                'Uploaded!',
                                'Your file is now in the database.',
                                'success'
                            )
                        }
                    })
                }
            })
        }
    })


    function checkInputs() {
        let empty1 = false;
        let empty2 = false;
        let empty3 = false;


        if ($('#patient-search').val() === '') {
            empty1 = true;
        }

        if ($('#document-type').val() === null) {
            empty2 = true;
        }

        if ($('#portal-registered').is(':checked') && $('#generate-document').val() === '0000') {
            empty3 = true;
        }

        if (!empty1 && !empty2 && !empty3) {
            $('.document-upload-btns button').prop('disabled', false)
        }
        else {
            $('.document-upload-btns button').prop('disabled', true)
        }
    }

    $('.download-pdf').on('click', function () {
        docnum = $(this).val();

        $.ajax({
            type: "POST",
            data: {
                docnum: docnum
            },
            url: "php_processes/download-prescription.php",
            success: function (result) {
                let base64 = result;

                downloadPDF(base64, docnum);
            }
        })
    })


    $('.view').on('click', function () {
        docnum = $(this).val()
        window.location.href = 'employee-prescription.php?docnum=' + docnum;
    })

    $('#reload-tbl-prescription').on('click', function () {
        $.ajax({
            type: 'POST',
            url: 'php_processes/reload-prescription-tbl.php',
            success: function (result) {
                $('.presc-tbl').html(result);
            }
        })
    })
})


function downloadPDF(pdf, docnum) {
    let filename = 'prescription-' + docnum + '.pdf';

    const linkSource = pdf;
    const downloadLink = document.createElement("a");
    const fileName = filename;
    downloadLink.href = linkSource;
    downloadLink.download = fileName;
    downloadLink.click();
}
