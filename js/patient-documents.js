$(document).ready(function () {
    $('.download').on('click', function () {
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