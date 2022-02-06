$(document).ready(function () {
    var clickedOnlineRequest;
    var payPrice;

    $(document).on('click', '.accept-online', function () {
        clickedOnlineRequest = $(this).val()

        $.ajax({
            type: 'POST',
            url: 'php_processes/get-online-req-price.php',
            data: {
                clicked: clickedOnlineRequest
            },
            success: function (result) {
                payPrice = result
            }
        })
    })

    if ($('.paypal').length) {
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color: 'gold',
                shape: 'rect',
                label: 'pay',
                height: 40
            },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: payPrice
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    ajaxPay();
                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/paid-bill-notif.php',
                        success: function(result){
                            console.log(result)
                        }
                    })
                    $('.dim-3').fadeOut();
                })
            },
            onCancel: function (data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment process has been cancelled',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }).render('.paypal');
    }

    function labResultPay() {
        billnum = $('.bill-num').html();

        //UPDATE STATUS OF BILL
        $.ajax({
            type: 'POST',
            url: 'php_processes/pay-lab-result.php',
            data: {
                billnum: billnum
            },
            success: function (result) {
                //UPDATE TRANSACTIONS TABLE AS A RESULT
                $('.transaction-table').html(result)
            }
        })
    }

    function ajaxPay() {
        appnum = $('.accept-online').val()
        answer = 'accept'

        $.ajax({
            type: 'POST',
            url: 'php_processes/accept-decline-online.php',
            data: {
                appnum: appnum,
                answer: answer
            },
            success: function (result) {
                $("#appt-table").html(result)

                $.ajax({
                    type: 'POST',
                    url: 'extras/patient-notifications.php',
                    success: function (result) {
                        $('.notif-contents').html(result);
                    }
                })

                Swal.fire(
                    'Accepted!',
                    'The appointment will now be conducted online.',
                    'success'
                )
            }
        })

        //PUT IN BILLS TABLE
        $.ajax({
            type: 'POST',
            url: 'php_processes/pay-consultation.php',
            data: {
                appnum: appnum
            },
            success: function (result) {
                $(".transaction-table").html(result)

                Swal.fire(
                    'Accepted!',
                    'The appointment will now be conducted online.',
                    'success'
                )
            }
        })
    }

    setInterval(function () {
        empty = false;
        $(".fill-up-field input").each(function () {
            if ($(this).val() == "") {
                empty = true;
            }
        });

        if($('#issue-bill-manual').length){
            if($('#issue-bill-manual').val() == ''){
                empty = true;
            }
        }

        if (empty) {
            $('#issue-bill').prop('disabled', true);
            $('#issue-bill-lab').prop('disabled', true);
            $('#issue-bill-medtech').prop('disabled', true);
            $('#issue-bill-manual').prop('disabled', true)
        }
        else {
            $('#issue-bill').prop('disabled', false);
            $('#issue-bill-lab').prop('disabled', false);
            $('#issue-bill-medtech').prop('disabled', false);
            $('#issue-bill-manual').prop('disabled', false)
        }
    }, 100)

    $('#exit-issue').on('click', function () {
        $('.dim-4').fadeOut();

        $('.fill-up-field').not(':first').remove();
        $('.fill-up-item, .fill-up-price').val('');
        $('.total-price').html('0.00')

        if($('.bill-manual-input').length){
            $('.bill-manual-input').val('')
            $('#issue-bill-manual').val('')
        }
    })

    $('#add-bill-item').on('click', function () {
        let div = document.createElement("div")
        let text = document.createElement("input")
        let num = document.createElement("input")
        let button = document.createElement("button");

        div.className = "fill-up-field";
        text.className = "fill-up-item";
        num.className = "fill-up-price";
        button.className = "delete-item";
        button.innerHTML = "<i class='fas fa-trash'></i>";

        text.type = "text";
        num.type = "number";
        text.placeholder = "Enter a description";
        num.placeholder = "Enter price"

        div.appendChild(text)
        div.appendChild(num)
        div.appendChild(button)

        document.getElementsByClassName('fill-up-fields')[0].appendChild(div)
    })

    $(document).on('input', '.fill-up-price', function () {
        total = 0.00;

        $('.fill-up-price').each(function (i) {
            number = Number($(this).val());
            total += number;
        })

        $('.total-price').html(total.toFixed(2))
    })

    $(document).on('click', '.delete-item', function () {
        if ($(".fill-up-field").length == 1) {
            return;
        }
        $(this).parent().remove();
        total = 0.00;

        $('.fill-up-price').each(function (i) {
            number = Number($(this).val());
            total += number;
        })

        $('.total-price').html(total.toFixed(2))
    })

    $(document).on('keypress', '.fill-up-item', function (evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }

        var regex = /[]|\,/;
        if (regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })

    $('#issue-bill').on('click', function () {
        var names = $('.fill-up-item').map((i, e) => e.value).get();
        var prices = $('.fill-up-price').map((i, e) => e.value).get();
        var total = $('.total-price').html();
        var billNum = $(this).val();
        var selected = $('#reload-tbl').val();

        var namesString = names.join(", ");
        var pricesString = prices.join(", ");

        Swal.fire({
            title: 'Request Online?',
            text: "The patient will be issued with the bill and be will be asked to agree/disagree with your request. If the patient declines, the bill will be aborted.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/request-online.php',
                    data: {
                        appnum: appnum,
                        selected: selected
                    },
                    success: function (result) {
                        getToolTipDetails();
                        calendar.refetchEvents();
                        $("#doctor-appt-table").html(result)
                    }
                })

                $.ajax({
                    type: 'POST',
                    url: 'php_processes/issue-bill.php',
                    data: {
                        selected: selected,
                        namesString: namesString,
                        pricesString: pricesString,
                        total: total,
                        billNum: billNum,
                        fromOnline: 'true'
                    }
                })

                Swal.fire(
                    'Requested',
                    'Awaiting for patient\'s approval',
                    'success'
                )
            }
        })

        $('#exit-issue').trigger('click');
    })

    $(document).on('keyup', '.bill-manual-input', function(){
        let keyword = $(this).val()
        $('#issue-bill-manual').prop('disabled', true)
        $('#issue-bill-manual').val('')

        if(keyword == ''){
            $('.bill-manual-autocomplete').hide();
        } else{
            $.ajax({
                url: 'php_processes/documents-patient-search.php',
                type: 'POST',
                data:{
                    query: keyword
                },
                success: function(result){
                    if(result == ''){
                        $('.bill-manual-autocomplete').hide();
                    }
                    else{
                        $('.bill-manual-autocomplete').show();
                        $('.bill-manual-autocomplete').html(result);
                    }
                }
            })
        }
    })

    $(document).on('click', '.result-autocomplete', function(){
        pid = $(this).val()
        pname = $(this).find('span:first-child()').html()
        $('.bill-manual-input').val(pname)
        $('.bill-manual-autocomplete').hide();
        $('#issue-bill-manual').val(pid)
    })

    $('#issue-bill-manual').on('click', function () {
        var names = $('.fill-up-item').map((i, e) => e.value).get();
        var prices = $('.fill-up-price').map((i, e) => e.value).get();
        var total = $('.total-price').html();
        var billNum = $(this).val();

        var namesString = names.join(", ");
        var pricesString = prices.join(", ");

        Swal.fire({
            title: 'Issue bill?',
            text: "The patient will be notified and will be issued with a bill.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                
                $.ajax({
                    url: 'php_processes/issue-bill-manual.php',
                    type: 'POST',
                    data: {
                        total: total,
                        billNum: billNum,
                        namesString: namesString,
                        pricesString: pricesString
                    },
                    success: function(result){
                        $('.bills-tbl').html(result)
                        $('#page-num').html('1')
                        $('#offset').html('0')
                    }
                })

                Swal.fire(
                    'Issued',
                    'Awaiting for the patient\'s payment',
                    'success'
                )
            }
        })

        $('#exit-issue').trigger('click');
    })


    $('#issue-bill-lab').on('click', function () {
        //variables for document
        let doctype = $('#document-type').val();
        let base64 = base64String;
        let sentTo = $('#upload-from-device').val();
        let pname = $('#patient-search').val();
        let withBill = "true";

        //variables for bill
        var names = $('.fill-up-item').map((i, e) => e.value).get();
        var prices = $('.fill-up-price').map((i, e) => e.value).get();
        var total = $('.total-price').html();
        var billNum = $(this).parent().parent().find('.issue-details').find('.bill-num').html()
        var selected = $('#reload-tbl').val();

        var namesString = names.join(", ");
        var pricesString = prices.join(", ");

        //UPLOAD LAB RESULT TO DATABASE
        $.ajax({
            type: 'POST',
            url: 'php_processes/upload-document.php',
            data: {
                base64: base64,
                doctype: doctype,
                sentTo: sentTo,
                fileExt: fileExt,
                withBill: withBill,
                billNum: billNum
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
                //ISSUE THE BILL
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/issue-bill.php',
                    data: {
                        selected: selected,
                        namesString: namesString,
                        pricesString: pricesString,
                        total: total,
                        billNum: billNum,
                        correspondingDoc: result
                    }
                })
                
            }
        })


        Swal.fire(
            'Success',
            'Lab Result sent!',
            'success'
        )

        $('#exit-issue').trigger('click');
    })


    $('#issue-bill-medtech').on('click', function () {
        pname = $('#patient-search').val();
        testType = $('#test-type').val();
        testType3 = $('#test-type-3').val();
        collectionDate = $('#collection-date').val();
        collectionDate3 = $('#collection-date-3').val();
        resultDate = $('#result-date').val();
        resultDate3 = $('#result-date-3').val();
        pid = $(this).val();
        labdraftissue = false;
        documentissue = false;

        let doctype = 'labresult'
        let base64 = base64String;
        let sentTo = $('#upload-from-device').val();

        //variables for bill
        var names = $('.fill-up-item').map((i, e) => e.value).get();
        var prices = $('.fill-up-price').map((i, e) => e.value).get();
        var total = $('.total-price').html();
        var billNum = $(this).parent().parent().find('.issue-details').find('.bill-num').html()
        var selected = $('#reload-tbl').val();

        var namesString = names.join(", ");
        var pricesString = prices.join(", ");

        const params = new URLSearchParams(window.location.search);
        var doc = params.get('docType')
        var patientid = params.get('pid')

        //IF FROM CREATE DRAFT, CREATE DRAFT
        if (doc == 'labresult' && patientid != '0000') {
            const invoice = document.getElementById("invoice");
            uploadToDatabase(invoice, "&withBill=true")
        }
        else if ($('#collection-date').val() != '') {
            $.ajax({
                type: 'POST',
                url: 'php_processes/insert-lab-draft.php',
                data: {
                    pname: pname,
                    testType: testType,
                    collectionDate: collectionDate,
                    resultDate: resultDate,
                    pid: pid,
                    issuedByMedtech: 'true',
                    correspondingBill: $('.bill-num').html()
                },
                success: function (result) {
                    $('.lab-tbl').html(result);
                    $('#generate-document').prop('disabled', true);
                    $('.add-document-overlay').fadeOut();
                    $('.medtech-draft-container').fadeOut();
                    $('#portal-registered').prop('checked', false);
                    $('#patient-search').val('');
                    $('#patient-search-2').val('');
                    $('#test-type').val('');
                    $('#collection-date').val('');
                    $('#result-date').val('');
                    $('#generate-document').val('0000')
                    $('#upload-from-device').val('0000')
                    $('#file-to-database').val('0000')
                    $('#generate-document-2').val('0000')
                    clearInterval(interval)

                    $.ajax({
                        type: 'POST',
                        url: 'php_processes/medtech-dynamic-table.php',
                        success: function (result) {
                            $('.lab-tbl').html(result)

                            Swal.fire('Success', 'Draft Created!', 'success')
                        }
                    })
                }
            })

            labdraftissue = true;
        }
        else if($('#collection-date-3').val() != ''){
            $.ajax({
                type: 'POST',
                url: 'php_processes/insert-ongoing-test.php',
                data: {
                    pname: pname,
                    testType: testType3,
                    collectionDate: collectionDate3,
                    resultDate: resultDate3,
                    pid: pid,
                    issuedByMedtech: 'true',
                    correspondingBill: $('.bill-num').html()
                },
                success: function(result){
                    $('#generate-document').prop('disabled', true);
                    $('.add-document-overlay').fadeOut();
                    $('.medtech-draft-container-2').fadeOut();
                    $('#portal-registered, #portal-registered-3').prop('checked', false);
                    $('#patient-search').val('');
                    $('#patient-search-2, #patient-search-3').val('');
                    $('#test-type, #test-type-3').val('');
                    $('#collection-date, #collection-date-3').val('');
                    $('#result-date, #result-date-3').val('');
                    clearInterval(intervalOngoing)
                }
            })
            labdraftissue = true;
        }
        else {
            $.ajax({
                type: 'POST',
                url: 'php_processes/upload-document.php',
                data: {
                    base64: base64,
                    doctype: doctype,
                    sentTo: sentTo,
                    fileExt: fileExt,
                    issuedByMedtech: 'true',
                    withBill: 'true',
                    billNum: billNum
                },
                success: function (result) {
                    $('#patient-search-2').val('');
                    $('#file').val(null);
                    $('#file').hide();
                    $('#upload-from-device').show();
                    $('#file-to-database').hide();
                    $('.add-document-overlay').fadeOut();
                    $('.direct-upload-medtech').fadeOut();
                    $('#upload-from-device').val('0000')
                    $('#file-to-database').val('0000')
                    $('#generate-document-2').val('0000')
                    $('#test-type-2').val('')
                    if (result.includes("Error while uploading the file: ")) {
                        alert(result)
                    } else {
                        Swal.fire('Uploaded!', 'Your file has been successfully uploaded!', 'success')
                    }
                }
            })
            documentissue = true;
        }

        var url = 'php_processes/issue-bill.php'
        if (doc == 'labresult' && patientid != '0000') {
            url = '../php_processes/issue-bill.php'
        }
        //BEFORE ISSUING

        //ISSUE THE BILL
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                selected: selected,
                namesString: namesString,
                pricesString: pricesString,
                total: total,
                billNum: billNum,
                labdraftissue: labdraftissue
            }
        })

        $('#exit-issue').trigger('click');
    })


    $(document).on('click', '.exit-2', function () {
        $('.dim-2').fadeOut();
    })

    $(document).on('click', '.pay-now', function () {
        billnum = $(this).val()
        paystatus = 'unpaid'

        $.ajax({
            type: 'POST',
            url: 'php_processes/open-bill-details.php',
            data: {
                billnum: billnum,
                paystatus: paystatus
            },
            success: function (result) {
                $('.bill-container').html(result);
                var price = $('#total-price').html();
                paypal.Buttons({
                    style: {
                        layout: 'vertical',
                        color: 'gold',
                        shape: 'rect',
                        label: 'pay',
                        height: 40
                    },
                    createOrder: function (data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: price
                                }
                            }]
                        });
                    },
                    onApprove: function (data, actions) {
                        return actions.order.capture().then(function (details) {
                            labResultPay();
                            $('.exit-2').trigger('click');
                            $.ajax({
                                type: 'POST',
                                url: 'php_processes/paid-bill-notif.php',
                                success: function(result){
                                    console.log(result)
                                }
                            })
                            Swal.fire(
                                'Success',
                                'Your payment was successful!',
                                'success'
                            )

                            setTimeout(function(){
                                window.location.reload()
                            }, 3000)
                        })
                    },
                    onCancel: function (data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment process has been cancelled',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }).render('.paypal-btn');

                $('.dim-2').fadeIn();
            }
        })
    })


    $(document).on('click', '.view-trans-details', function () {
        billnum = $(this).val()
        paystatus = 'paid'

        $.ajax({
            type: 'POST',
            url: 'php_processes/open-bill-details.php',
            data: {
                billnum: billnum,
                paystatus: paystatus
            },
            success: function (result) {
                $('.bill-container').html(result);
                $('.dim-2').fadeIn();
            }
        })
    })
})

