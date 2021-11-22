// $(document).ready(function () {
//     $("#copy").on('click', function () {
//         $.ajax({
//             type: "POST",
//             url: "../php_processes/download-prescription.php",
//             success: function (result) {
//                 let base64 = result;
//                 console.log(base64);

//                 downloadPDF(base64);
//             }
//         })
//     })

// })


// function downloadPDF(pdf) {
//     const linkSource = pdf;
//     const downloadLink = document.createElement("a");
//     const fileName = "abc.pdf";
//     downloadLink.href = linkSource;
//     downloadLink.download = fileName;
//     downloadLink.click();
// }