document.getElementById("upload-medtech").addEventListener("click", () => {
    const invoice = this.document.getElementById("invoice");

    docnum = $('.invisible-params').html().split(", ")[2];
    withBill = "";
    $.ajax({
        type: 'POST',
        url: '../php_processes/check-lab-draft-paid.php',
        data: {
            'docnum': docnum,
        },
        async: false,
        success: function (result) {
            withBill = result
        }
    })

    Swal.fire({
        title: 'Upload File?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, upload it!'
    }).then((result) => {
        if (result.isConfirmed) {
            s = $('.invisible-params').html();
            arrayList = s.split(', ');
            doctype = 'labresult';
            pid = arrayList[0];
            pname = arrayList[1];

            var opt = {
                margin: 0,
                filename: 'myfile.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            file = html2pdf().from(invoice).toPdf().get('pdf').then(function (pdfObj) {
                const perBlob = pdfObj.output('blob');

                var reader = new FileReader();
                reader.readAsDataURL(perBlob);
                reader.onloadend = function () {
                    var base64data = reader.result;

                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log(xhttp.responseText);
                        }
                    }
                    xhttp.open("POST", "../php_processes/blob-to-database.php", true);
                    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhttp.send("blob=" + encodeURIComponent(base64data) + "&docnum=" + docnum + "&doctype=" + doctype + "&pid=" + pid + "&pname=" + pname + "&fileExt=" + "application/pdf" + withBill);
                }
            })

            //DELETE DRAFT
            s = $('.invisible-params').html();
            arrayList = s.split(', ');
            docnum = arrayList[2];

            $.ajax({
                type: 'POST',
                url: '../php_processes/delete-lab-draft.php',
                data: {
                    docnum: docnum
                }
            })

            //SUCCESS MSG
            Swal.fire(
                'Success!',
                'The file has been uploaded.',
                'success'
            ).then((result) => {
                if (result.isConfirmed) {
                    window.close();
                }
            })

        }
    })
})