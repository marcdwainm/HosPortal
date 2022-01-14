$(document).ready(function () {
    $(document).on('click', '.download', function () {
        docnum = $(this).val();

        Swal.fire(
            'Disclaimer',
            'This file is protected by the portal. Upon your download, Twin Care shall not be liable for any incident that may disclose the confidentiality of this file. It is within your accountability to keep this file confidential.',
            'warning'
        ).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    data: {
                        docnum: docnum
                    },
                    url: "php_processes/download-prescription.php",
                    success: function (result) {
                        result = JSON.parse(result)
                        let base64 = result.base64;
                        let fileExt = result.file_ext

                        if (!base64.includes('data')) {
                            base64 = 'data:' + fileExt + ';base64,' + base64;
                        }

                        var a = document.createElement("a"); //Create <a>
                        a.href = base64; //Image Base64 Goes here
                        a.download = result.doctype + "-" + docnum;
                        a.click(); //Downloaded file
                    }
                })
            }
        })
    })

    $(document).on('click', '.details-btn', function () {
        docnum = $(this).val();
        window.location.href = 'patient-prescription.php?docnum=' + docnum;
    })

    $('#see-all-documents').on('click', function () {
        window.location.href = 'patient-all-documents.php';
    })

    $('#sort-table').on('click', function () {
        sortType = $('#sortation').val();
        str = '';

        switch (sortType) {
            case 'all':
                str = 'All';
                break;
            case 'oldest':
                str = 'Oldest to Latest';
                break;
            case 'prescriptions':
                str = 'Prescriptions';
                break;
            case 'labresults':
                str = 'Lab Results';
                break;
            case 'today':
                str = 'Today';
                break;
            case 'thisweek':
                str = 'This Week';
                break;
            case 'thismonth':
                str = 'This Month';
                break;
        }

        $('.header-table span').html(str);

        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-sort-docs.php',
            data: {
                type: sortType
            },
            success: function (result) {
                $('#page-num').html('1');
                $('#offset').html('0');
                $('#sorting').html(result)
            }
        })

        Swal.fire({
            position: 'bottom-right',
            icon: 'success',
            title: 'Appointments Sorted',
            backdrop: 'none',
            showConfirmButton: false,
            timer: 1000
        })
    })

    $('#next').on('click', function () {
        sortType = $('#sortation').val();
        if (sortType === '') {
            sortType == 'all'
        }
        offset = parseInt($('#offset').html());
        pageNum = parseInt($('#page-num').html());

        if (pageNum >= 1) {
            $('#prev').prop('disabled', false)
        }

        offset += 5;
        $('#offset').html(offset)
        $('#page-num').html(pageNum + 1)

        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-sort-docs.php',
            data: {
                type: sortType,
                offset: offset
            },
            success: function (result) {
                if (result == "<div class = 'no-appointments'>Documents Empty</div>") {
                    pageNum = parseInt($('#page-num').html());
                    offset -= 5;
                    $('#offset').html(offset)
                    $('#page-num').html(pageNum - 1)
                } else {
                    $('#sorting').html(result)
                }
            }
        })
    })

    $('#prev').on('click', function () {
        sortType = $('#sortation').val();
        if (sortType === '') {
            sortType == 'all'
        }
        offset = parseInt($('#offset').html());
        pageNum = parseInt($('#page-num').html());

        if (pageNum == '1') {
            $(this).prop('disabled', true)
        }
        else {
            offset -= 5;
            $('#offset').html(offset)
            $('#page-num').html(pageNum - 1)

            $.ajax({
                type: 'POST',
                url: 'php_processes/patient-sort-docs.php',
                data: {
                    type: sortType,
                    offset: offset
                },
                success: function (result) {
                    $('#sorting').html(result)
                }
            })
        }
    })

    $('#next2').on('click', function () {
        sortType = 'prescriptions';
        offset = parseInt($('.reload-all-docs #offset').html());
        pageNum = parseInt($('.reload-all-docs #page-num').html());

        if (pageNum >= 1) {
            $('.reload-all-docs #prev2').prop('disabled', false)
        }

        offset += 5;
        $('.reload-all-docs #offset').html(offset)
        $('.reload-all-docs #page-num').html(pageNum + 1)

        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-sort-docs.php',
            data: {
                type: sortType,
                offset: offset
            },
            success: function (result) {
                if (result === "<div class = 'no-appointments'>Documents Empty</div>") {
                    pageNum = parseInt($('.reload-all-docs #page-num').html());
                    offset -= 5;
                    $('.reload-all-docs #offset').html(offset)
                    $('.reload-all-docs #page-num').html(pageNum - 1)
                }
                else {
                    $('.dynamic-tbl-docs').html(result);
                }
            }
        })
    })

    $('#prev2').on('click', function () {
        sortType = 'prescriptions';
        offset = parseInt($('.reload-all-docs #offset').html());
        pageNum = parseInt($('.reload-all-docs #page-num').html());

        if (pageNum == '1') { //DISABLE IF PAGE NUM IS 1
            $(this).prop('disabled', true)
        } else {
            offset -= 5;
            $('.reload-all-docs #offset').html(offset)
            $('.reload-all-docs #page-num').html(pageNum - 1)

            $.ajax({
                type: 'POST',
                url: 'php_processes/patient-sort-docs.php',
                data: {
                    type: sortType,
                    offset: offset
                },
                success: function (result) {
                    $('.dynamic-tbl-docs').html(result);
                }
            })
        }
    })

    $('#next3').on('click', function () {
        sortType = 'labresults';
        offset = parseInt($('.reload-all-docs #offset-lab').html());
        pageNum = parseInt($('.reload-all-docs #page-num-lab').html());

        if (pageNum >= 1) {
            $('.reload-all-docs #prev3').prop('disabled', false)
        }

        offset += 5;
        $('.reload-all-docs #offset-lab').html(offset)
        $('.reload-all-docs #page-num-lab').html(pageNum + 1)

        $.ajax({
            type: 'POST',
            url: 'php_processes/patient-sort-docs.php',
            data: {
                type: sortType,
                offset: offset
            },
            success: function (result) {
                if (result === "<div class = 'no-appointments'>Documents Empty</div>") {
                    pageNum = parseInt($('.reload-all-docs #page-num-lab').html());
                    offset -= 5;
                    $('.reload-all-docs #offset-lab').html(offset)
                    $('.reload-all-docs #page-num-lab').html(pageNum - 1)
                }
                else {
                    $('.dynamic-tbl-docs-lab').html(result);
                }
            }
        })
    })

    $('#prev3').on('click', function () {
        sortType = 'labresults';
        offset = parseInt($('.reload-all-docs #offset-lab').html());
        pageNum = parseInt($('.reload-all-docs #page-num-lab').html());

        if (pageNum == '1') { //DISABLE IF PAGE NUM IS 1
            $(this).prop('disabled', true)
        } else {
            offset -= 5;
            $('.reload-all-docs #offset-lab').html(offset)
            $('.reload-all-docs #page-num-lab').html(pageNum - 1)

            $.ajax({
                type: 'POST',
                url: 'php_processes/patient-sort-docs.php',
                data: {
                    type: sortType,
                    offset: offset
                },
                success: function (result) {
                    $('.dynamic-tbl-docs-lab').html(result);
                }
            })
        }
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