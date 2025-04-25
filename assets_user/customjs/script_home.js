function getPucTypeRate(){

    var puc_type_id = $("#puc_type").val();
    var puc_challan = $("#Challan-form").val();
    var puc_fine = $("#puc_fine").val();
    
    let type = 'POST';
    let url = '/getPucTypeRate';
    let message = '';
    let form = '';
    let data = new FormData();
    data.append("puc_type_id", puc_type_id);
    data.append("puc_challan", puc_challan);
    data.append("puc_fine", puc_fine);
    puc_fine
    // PASSING DATA TO FUNCTION
    SendAjaxRequestToServer(type, url, data, '', getPucTypeRateResponse, '', 'submit_button');
}
function getPucTypeRateResponse(response){

    var data = response.data;
    var charges = data['charges'];
    var pucTypeTotalRate = data['pucTypeTotalRate'];
    var challanTotalRate = data['challanTotalRate'];
    var pucFineRate = data['pucFineRate'];

    if(charges != null){
        $("#puc_charges").html('&#8377; '+charges);
        $("#puc_total_charges").val(charges != null ? charges : 0);
        $("#puc_type_rate").val(pucTypeTotalRate != null ? pucTypeTotalRate : 0);
        $("#puc_challan_rate").val(challanTotalRate != null ? challanTotalRate : 0);
        $("#puc_fine_rate").val(pucFineRate != null ? pucFineRate : 0);
    }else{
        $("#puc_charges").html(`&#8377; 0`);
        $("#puc_total_charges").val('0');
        $("#puc_type_rate").val('0');
        $("#puc_challan_rate").val('0');
        $("#puc_fine_rate").val('0');
    }
}
$(document).on('change', '#Challan-form', function (e) {

	getPucTypeRate();
	
});

$(document).on('click', '#puc_create_submit', function (e) {

    e.preventDefault();
    let type = 'POST';
	let url = '/createPucUser';
	let message = '';
    let form = $('#puc_create_form');
    let data = new FormData(form[0]);
    data.append('puc_id', '');

    $('[name]').removeClass('is-invalid');
    // this code is for compress image to 400 kB
    
    let files = selectedFiles;
    let file_challan = document.getElementById('upload_challan').files[0];

    if (files.length > 0) {
        let promises = [];

        for (let i = 0; i < files.length; i++) { 
            let file = files[i];
            let promise = compressImage(file, 600);
            promises.push(promise);
        }

        if (file_challan) {
            let challanPromise = compressImage(file_challan, 600);
            promises.push(challanPromise);
        }

        $.when.apply($, promises).done(function(...compressedBlobs) {
            for (let i = 0; i < files.length; i++) {
                data.append('upload_vehicle[]', compressedBlobs[i], files[i].name);
            }
            if (file_challan) {
                data.append('upload_challan', compressedBlobs[files.length], file_challan.name);
            }
            SendAjaxRequestToServer(type, url, data, '', pucCreateUserResponse, '', '#puc_create_submit');
        });
    } else {
        data.append('upload_vehicle', '');
        
        if (file_challan) {
            compressImage(file_challan, 600).then(function(compressedBlob) {
                data.append('upload_challan', compressedBlob, file_challan.name);
                SendAjaxRequestToServer(type, url, data, '', pucCreateUserResponse, '', '#puc_create_submit');
            });
        } else {
            SendAjaxRequestToServer(type, url, data, '', pucCreateUserResponse, '', '#puc_create_submit');
        }
    }
    
    // if (files.length > 0) {

    //     let promises = [];

    //     for (let i = 0; i < files.length; i++) { 
    //         let file = files[i];
    //         let promise = compressImage(file, 400);
    //         promises.push(promise);
                
    //     }

    //     $.when.apply($, promises).done(function(...compressedBlobs) {
    //         for (let i = 0; i < compressedBlobs.length; i++) {
    //             data.append('upload_vehicle[]', compressedBlobs[i], files[i].name);
    //         }
    //         SendAjaxRequestToServer(type, url, data, '', pucCreateUserResponse, '', '#puc_create_submit');
    //     });
    // }else{
    //     data.append('upload_vehicle', '');
    //     SendAjaxRequestToServer(type, url, data, '', pucCreateUserResponse, '', '#puc_create_submit');
    // }
});

function pucCreateUserResponse(response) {

    // SHOWING MESSAGE ACCORDING TO RESPONSE
    if (response.status == 200 || response.status == '200') {
      
        toastr.success(response.message, '', {
            timeOut: 3000
        });

        let form = $('#puc_create_form');
        form.trigger("reset");
        selectedFiles = [];
        
        $("#main_section").show();
        $("#makePuc_section, #notification_section").hide(); 

    } else {
        
        if(response.status == 402){
            
            error = response.message;

        }else{
            
            error = response.responseJSON.message;
            var is_invalid = response.responseJSON.errors;
        
            $.each(is_invalid, function(key) {
                // Assuming 'key' corresponds to the form field name
                var inputField = $('[name="' + key + '"]');
                var selectField = $('[name="' + key + '"]');
                // Add the 'is-invalid' class to the input field's parent or any desired container
                inputField.closest('.form-control').addClass('is-invalid');
                selectField.closest('.form-select').addClass('is-invalid');
            });
        }
    	
        toastr.error(error, '', {
            timeOut: 3000
        });
    }
}

function compressImage(file, maxSize) {
        return $.Deferred(function(deferred) {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > maxSize) {
                            height *= maxSize / width;
                            width = maxSize;
                        }
                    } else {
                        if (height > maxSize) {
                            width *= maxSize / height;
                            height = maxSize;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function(blob) {
                        if (blob) {
                            deferred.resolve(blob);
                        } else {
                            deferred.reject(new Error('Image compression failed.'));
                        }
                    }, file.type, 1.0); // Adjust the quality parameter (0.8) as needed
                }
            }
            reader.onerror = function(error) {
                deferred.reject(error);
            }
        }).promise();
    }


// function compressImage(file, maxSizeKB, callback) {
//     const reader = new FileReader();
//     reader.readAsDataURL(file);
//     reader.onload = function(event) {
//         const img = new Image();
//         img.src = event.target.result;
//         img.onload = function() {
//             const canvas = document.createElement('canvas');
//             const ctx = canvas.getContext('2d');

//             // Set maximum dimensions
//             const MAX_WIDTH = 800;
//             const MAX_HEIGHT = 800;
//             let width = img.width;
//             let height = img.height;

//             if (width > height) {
//                 if (width > MAX_WIDTH) {
//                     height *= MAX_WIDTH / width;
//                     width = MAX_WIDTH;
//                 }
//             } else {
//                 if (height > MAX_HEIGHT) {
//                     width *= MAX_HEIGHT / height;
//                     height = MAX_HEIGHT;
//                 }
//             }

//             canvas.width = width;
//             canvas.height = height;
//             ctx.drawImage(img, 0, 0, width, height);

//             let quality = 0.9; // Start with high quality
//             function tryCompress() {
//                 canvas.toBlob(function(blob) {
//                     if (blob.size <= maxSizeKB * 1024 || quality <= 0.1) {
//                         callback(blob);
//                     } else {
//                         quality -= 0.1; // Reduce quality in smaller steps
//                         tryCompress();
//                     }
//                 }, 'image/jpeg', quality);
//             }
//             tryCompress();
//         }
//     }
// }



$(document).ready(function() {
    // $('#uploadForm').on('submit', function(e) {
    //     e.preventDefault();

    //     let file = document.getElementById('image').files[0];
    //     if (file) {
    //         compressImage(file, 400, function(compressedBlob) {
    //             let formData = new FormData();
    //             formData.append('image', compressedBlob, file.name);

    //             $.ajax({
    //                 url: '/your/upload/endpoint', // Change this to your upload endpoint
    //                 type: 'POST',
    //                 data: formData,
    //                 processData: false,
    //                 contentType: false,
    //                 success: function(response) {
    //                     alert('Image uploaded and compressed successfully!');
    //                     console.log(response);
    //                 },
    //                 error: function(xhr, status, error) {
    //                     alert('Image upload failed.');
    //                     console.log(error);
    //                 }
    //             });
    //         });
    //     }
    // });

    
});

$(document).ready(function () {
    
    

        
    $(document).on('click', '#makeNewPuc_btn', function (e) {
        
        if(user_balance > 0){
            let form = $('#puc_create_form');
            form.trigger("reset");
    
            $("#picturename").html('Upload Vehicle Photo');
            $("#challanName").html('Upload Challan Screenshot');
    
            $("#puc_charges").html('&#8377; 0');
            $("#puc_total_charges").val('0');
            $("#puc_type_rate").val('0');
            $("#puc_challan_rate").val('0');
    
            $("#main_section, #notification_section").hide();
            $("#makePuc_section").show();
        }else{
            toastr.error('Insufficient balance, please add balance in your wallet first!', '', {
                timeOut: 3000
            });
        }
    });

    $(document).on('click', '#viewAllNotif_btn', function (e) {

        $("#main_section, #makePuc_section").hide();
        $("#notification_section").show();
        
    });
    
    $(document).on('click', '#backToMainPage', function (e) {

        $("#main_section").show();
        $("#makePuc_section, #notification_section").hide();
        
    });

    $(document).on('change', '#Challan-form', function (e) {

        var challan_val = $(this).val();

        if(challan_val != ''){
            $(".challan_opt_div").show();
        }else{
            $(".challan_opt_div").hide();
        }
        
    });

    $(document).on('click', '.puc_type_opt', function (e) {

        $(".puc_type_opt").removeClass('active');
        $(this).addClass('active');

        var puc_type_id = $(this).attr('data-id');

        $("#puc_type").val(puc_type_id).trigger('change');
    });

    
    
});

var selectedFiles = [];
var allowed_images = 3;

$(document).on('click', '.cameraIcon', function (e) {
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        // Trigger click event on the input field
        document.getElementById('upload_vehicle').click();
    } else {
        if(upload_option == '1'){
            document.getElementById('upload_vehicle').click();
        }else{
            toastr.error('Not allowed to upload!', '', {
                timeOut: 3000
            });
        }
    }
});

document.getElementById('upload_vehicle').addEventListener('change', function() {
    var total_files = this.files.length + selectedFiles.length;

    // if(total_files > allowed_images){
    //     $("#upload_vehicle").val('');
    //     toastr.error('Only 3 images allowed to upload...', '', {
    //         timeOut: 3000
    //     });
    //     return;
    // }

    if (this.files.length > 0) {
        var files = this.files;
        var errorflag = '0';
        for(var i=0; i<files.length; i++){
            selectedFiles.push(files[i]);
        }

        makeFilesPreview(selectedFiles);
        
        toastr.success('Uploaded', '', {
            timeOut: 3000
        });
        
    } else {
        selectedFiles = [];
        makeFilesPreview(selectedFiles);
    }
});

function makeFilesPreview(files) {
    
    $("#image_preview").empty();
    
    
    if(files.length>0){
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            (function(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview_html = `<div class="col-4 p-1">
                                            <img src="${e.target.result}" alt="${file.name}">
                                        </div>`;
                    $("#image_preview").append(preview_html);
                };
                reader.readAsDataURL(file);
            })(file);
        }
        
        var total_files = files.length;
        var percentage = (total_files/allowed_images)*100;

        if(total_files <= 3){
            var allowed_images_count = allowed_images;
        }else{
            var allowed_images_count = total_files;
        }

        $("#progress_text").text('Uploaded...'+total_files+'/'+allowed_images_count);
        $("#progress_percent").css('width', percentage < 100 ? percentage : '100'+'%');

        $("#upload_button").hide();
        $("#uploaded_section").show();
    }else{

        $("#progress_text").text('Uploaded...0/'+allowed_images);
        $("#progress_percent").css('width', '0%');

        $("#upload_button").show();
        $("#uploaded_section").hide();
    }
    
}

// document.getElementsByClass('cameraIcon').addEventListener('click', function() {
    
//     if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
//         // Trigger click event on the input field
//         document.getElementById('upload_vehicle').click();
//     } else {
//         if(upload_option == '1'){
//             document.getElementById('upload_vehicle').click();
//         }else{
//             toastr.error('Not allowed to upload!', '', {
//                 timeOut: 3000
//             });
//         }
//     }
// });
// document.getElementById('upload_vehicle').addEventListener('change', function() {
//     if (this.files.length > 0) {
//         var files = this.files;
//         var errorflag = '0';
//         for(var i=0; i<files.length; i++){

//             var fileSize = files[i].size; // Size in bytes
//             var maxSize = 400 * 1024; // 400 KB in bytes

//             if (fileSize > maxSize) {
//                 if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
//                     var filename = files[i].name;
//                     // Update the content of the <span> element with the filename
//                     document.getElementById('picturename').innerText = filename;
//                     selectedFiles.push(files[i]);
//                     makeFileName(selectedFiles);
//                 }else{
//                     document.getElementById('picturename').innerText = 'Upload Vehicle Photo';
//                     errorflag = '1';
//                     selectedFiles = [];
//                 }
//             }else{
//                 var filename = files[i].name;
//                 // Update the content of the <span> element with the filename
//                 document.getElementById('picturename').innerText = filename;
                
//                 selectedFiles.push(files[i]);
//                 makeFileName(selectedFiles);
//             } 
//         }

//         if(errorflag == '1'){
//             $('#upload_vehicle').val('');
//             selectedFiles = [];
//             toastr.error('All Files must be less then 400 KB in size. Please choose a smaller file(s).', '', {
//                 timeOut: 3000
//             }); 
//         }else{
//             toastr.success('Uploaded', '', {
//                 timeOut: 3000
//             });
//         }
//     } else {
//         // No file selected, reset the content of the <span> element
//         document.getElementById('picturename').innerText = 'Upload Vehicle Photo';
//         selectedFiles = [];
//     }
// });

document.getElementById('challanIcon').addEventListener('click', function() {
    // Trigger click event on the input field
    document.getElementById('upload_challan').click();
});
document.getElementById('upload_challan').addEventListener('change', function() {
    if (this.files.length > 0) {
        // Get the filename of the selected file
        var fileSize = this.files[0].size; // Size in bytes
        var maxSize = 400 * 1024; // 400 KB in bytes

        // if (fileSize > maxSize) {
        //     $('#upload_challan').val('');
        //     document.getElementById('challanName').innerText = 'Upload Challan Screenshot';
        //     toastr.error('File size exceeds 400 KB. Please choose a smaller file.', '', {
        //         timeOut: 3000
        //     });
          
        // }
        // else{

            var filename = this.files[0].name;
    
            // Update the content of the <span> element with the filename
            document.getElementById('challanName').innerText = filename;
            toastr.success('Uploaded', '', {
                timeOut: 3000
            });
        // }
    } else {
        // No file selected, reset the content of the <span> element
        document.getElementById('challanName').innerText = 'Upload Challan Screenshot';
    }
});

$('#registration_number').on('input', function() {
    $(this).val($(this).val().toUpperCase());
});


