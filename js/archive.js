$(document).ready(function () {

    $(document).on('click', '.restore-prescription', function () {
        restoreId = $(this).val();
        docType = 'prescription';

        keywordId = "#archive-search-" + docType + " > input"
        var keyword = $(keywordId).val();

        Swal.fire({
            title: 'Restore document?',
            text: "The document will be brought back onto your document tables",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Restore'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/restore-document.php',
                    data: {
                        restoreId: restoreId,
                        docType: docType,
                        keyword: keyword
                    },
                    success: function (result) {
                        $('#archive-prescription-table').html(result);
                        $('.page-num-presc').html('1')
                        $('.offset-presc').html('0')
                    }
                })

                Swal.fire(
                    'Restored!',
                    'Your document has been restored',
                    'success'
                )
            }
        })

    })

    $(document).on('click', '.restore-labresult', function () {
        restoreId = $(this).val();
        docType = 'labresult';

        keywordId = "#archive-search-" + docType + " > input"
        var keyword = $(keywordId).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "The document will be brought back onto your document tables",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Restore'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/restore-document.php',
                    data: {
                        restoreId: restoreId,
                        docType: docType,
                        keyword: keyword
                    },
                    success: function (result) {
                        $('#archive-labresult-table').html(result);
                        $('.page-num-labresult').html('1')
                        $('.offset-labresult').html('0')
                    }
                })

                Swal.fire(
                    'Restored!',
                    'Your document has been restored',
                    'success'
                )
            }
        })

    })

    $(document).on('click', '.restore-soap', function () {
        restoreId = $(this).val();
        docType = 'soap '

        keywordId = "#archive-search-" + docType + " > input"
        var keyword = $(keywordId).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "The SOAP Note will be brought back to patient's table",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Restore'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/restore-soap.php',
                    data: {
                        restoreId: restoreId,
                        keyword: keyword
                    },
                    success: function (result) {
                        $('#archive-soap-table').html(result);
                        $('.page-num-soap').html('1')
                        $('.offset-soap ').html('0')
                    }
                })

                Swal.fire(
                    'Restored!',
                    'The note has been restored!',
                    'success'
                )
            }
        })

    })


    $(document).on('click', '.restore-other', function () {
        restoreId = $(this).val();

        keywordId = "#archive-search-other > input"
        var keyword = $(keywordId).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "The document will be brought back onto your document tables",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Restore'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'php_processes/restore-other.php',
                    data: {
                        restoreId: restoreId,
                        keyword: keyword
                    },
                    success: function (result) {
                        $('#archive-other-table').html(result);
                        $('.page-num-other').html('1')
                        $('.offset-other').html('0')
                    }
                })

                Swal.fire(
                    'Restored!',
                    'Your document has been restored',
                    'success'
                )
            }
        })

    })

    $(document).on('click', '.prev', function () {
        var typeOfPagination = $(this).parent().attr('id').substring(11);
        var page = $(this).parent().find('.page-num').html()
        var thiss = $(this)

        //GET KEYWORD IF THERE IS INPUT
        keywordId = "#archive-search-" + typeOfPagination + " > input"
        var keyword = $(keywordId).val();

        //-5 OFFSET
        var offset = parseInt($(this).parent().find('.offset').html()) - 5;

        //IF OFFSET IS LOWER THAN 0 DONT PAGINATE
        if (offset < 0) {
            offset += 5;
        }
        else {
            page -= 1;
        }
        $(this).parent().find('.offset').html(offset)

        //AJAX
        $.ajax({
            type: 'POST',
            url: 'php_processes/archive-pagination.php',
            data: {
                typeOfPagination: typeOfPagination,
                offset: offset,
                keyword: keyword
            },
            success: function (result) {
                stringId = '#archive-' + typeOfPagination + '-table';
                $(stringId).html(result);
                thiss.parent().find('.page-num').html(page)
            }
        })
    })


    $(document).on('click', '.next', function () {
        var typeOfPagination = $(this).parent().attr('id').substring(11);
        var page = $(this).parent().find('.page-num').html()
        var thiss = $(this)

        //GET KEYWORD IF THERE IS INPUT
        keywordId = "#archive-search-" + typeOfPagination + " > input"
        var keyword = $(keywordId).val();

        //+5 OFFSET
        var offset = parseInt($(this).parent().find('.offset').html()) + 5;
        $(this).parent().find('.offset').html(offset)

        //AJAX
        $.ajax({
            type: 'POST',
            url: 'php_processes/archive-pagination.php',
            data: {
                typeOfPagination: typeOfPagination,
                offset: offset,
                keyword: keyword
            },
            success: function (result) {
                //IF NO ITEMS IN NEXT PAGE, STAY IN LAST PAGE
                if (result == "<span class = 'no-appointments'>Archive Empty</span>") {
                    offset -= 5
                    thiss.parent().find('.offset').html(offset)
                    console.log(offset)
                }
                else {
                    page = parseInt(page) + 1
                    stringId = '#archive-' + typeOfPagination + '-table';
                    $(stringId).html(result);
                    thiss.parent().find('.page-num').html(page)
                }
            }
        })
    })

    $(document).on('keyup', '.archive-search', function () {
        var keyword = $(this).val()
        var tableType = $(this).parent().attr('id').substring(15)

        $.ajax({
            type: 'POST',
            url: 'php_processes/search-archive.php',
            data: {
                keyword: keyword,
                tableType: tableType
            },
            success: function (result) {
                stringId = '#archive-' + tableType + '-table';
                $(stringId).html(result);
                if (tableType == "prescription") {
                    tableType = "presc"
                }
                $(".page-num-" + tableType).html("1")
                $(".offset-" + tableType).html("0")
            }
        })
    })

    $(document).on('click', '.view-prescription, .view-labresult', function () {
        window.open('employee-prescription.php?docnum=' + $(this).val() + "&fromArchive=true", "_blank");
    })

    $(document).on('click', '.view-other', function () {
        window.open('employee-prescription.php?docnum=' + $(this).val() + "&other=true", "_blank");
    })

    $(document).on('click', '.view-soap', function () {
        soapId = $(this).val()

        $.ajax({
            type: 'POST',
            url: 'php_processes/open-soap-note-file-archive.php',
            data: {
                soap_id: soapId
            },
            success: function (result) {
                var arrOfSoap = result.split(" ### ");

                $('#soap-column-subjective').val(arrOfSoap[0])
                $('#soap-column-objective').val(arrOfSoap[1])
                $('#soap-column-assessment').val(arrOfSoap[2])
                $('#soap-column-plan').val(arrOfSoap[3])
            }
        })

        $('.dim-soap').fadeIn();
    })

    $(document).on('click', '#exit-soap', function () {
        $('.dim-soap').fadeOut();
    })
})