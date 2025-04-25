function getStateCityWrtCodeData() {

    var pinCode = $("#user_pin").val();
    if (pinCode != '') {
        let type = 'POST';
        let url = '/getStateCityWrtCodeData';
        let message = '';
        let form = '';
        let data = new FormData();
        data.append("pinCode", pinCode);
        // PASSING DATA TO FUNCTION
        SendAjaxRequestToServer(type, url, data, '', getStateCityWrtCodeDataResponse, '', 'submit_button');
    }
}
function getStateCityWrtCodeDataResponse(response) {

    var data = response.data;

    var stateId = data.stateId;
    var cityId = data.cityId;
    var areaId = data.areaId;

    var cities = data.citiesLov;
    var areas = data.areasLov;

    var cityOptions = `<option value="">Choose City</option>`;
    if (cities.length > 0) {
        $.each(cities, function (index, value) {
            cityOptions += `<option value="${value.id}">${value.name}</option>`;
        });
        $("#user_city").html(cityOptions);
    }

    var areasOptions = `<option value="">Choose Area</option>`;
    if (areas.length > 0) {
        $.each(areas, function (index, value) {
            areasOptions += `<option value="${value.id}">${value.name}</option>`;
        });
        $("#user_area").html(areasOptions);
    }

    if (stateId != '') {
        $("#user_state").val(stateId);
    }
    if (cityId != '') {
        $("#user_city").val(cityId);
    }
    if (areaId != '') {
        $("#user_area").val(areaId);
    }
}
function getCitiesLovData() {

    var state_id = $("#user_state").val();
    if (state_id != '') {
        let type = 'POST';
        let url = '/getCitiesLovData';
        let message = '';
        let form = '';
        let data = new FormData();
        data.append("state_id", state_id);
        // PASSING DATA TO FUNCTION
        SendAjaxRequestToServer(type, url, data, '', getCitiesLovDataResponse, '', 'submit_button');
    } else {
        $("#user_city").val('').html('<option value="">Choose City</option>');
        $("#user_area").val('').html('<option value="">Choose Area</option>');
    }
}
function getCitiesLovDataResponse(response) {

    var data = response.data;
    var cities = data.cities;

    var cityOptions = `<option value="">Choose City</option>`;
    if (cities.length > 0) {
        $.each(cities, function (index, value) {
            cityOptions += `<option value="${value.id}">${value.name}</option>`;
        });
    }
    $("#user_city").html(cityOptions);
    $("#user_area").val('').html('<option value="">Choose Area</option>');
}

function getAreasLovData() {

    var city_id = $("#user_city").val();
    if (city_id != '') {
        let type = 'POST';
        let url = '/getAreasLovData';
        let message = '';
        let form = '';
        let data = new FormData();
        data.append("city_id", city_id);
        // PASSING DATA TO FUNCTION
        SendAjaxRequestToServer(type, url, data, '', getAreasLovDataResponse, '', 'submit_button');
    } else {
        $("#user_area").val('').html('<option value="">Choose Area</option>');
    }
}
function getAreasLovDataResponse(response) {

    var data = response.data;
    var areas = data.areas;

    var areasOptions = `<option value="">Choose Area</option>`;
    if (areas.length > 0) {
        $.each(areas, function (index, value) {
            areasOptions += `<option value="${value.id}">${value.name}</option>`;
        });
    }
    $("#user_area").html(areasOptions);
}

$(document).on('click', '.register_form_submit', function (e) {

    e.preventDefault();
    let type = 'POST';
    let url = '/registerUser';
    let message = '';
    let form = $('#registration_form');
    let data = new FormData(form[0]);

    // PASSING DATA TO FUNCTION
    $('[name]').removeClass('is-invalid');
    
    let file = document.getElementById('upload_picture').files[0];
    let file1 = document.getElementById('upload_aadhar').files[0];
    if (file) {
        compressImage(file, 600, function(compressedBlob) {
            data.append('upload_picture', compressedBlob, file.name);
            if (file1) {
                compressImage(file1, 600, function(compressedBlob1) {
                    data.append('upload_aadhar', compressedBlob1, file1.name);
                    SendAjaxRequestToServer(type, url, data, '', registerUserResponse, '', '.register_form_submit');
                });
            } else {
                SendAjaxRequestToServer(type, url, data, '', registerUserResponse, '', '.register_form_submit');
            }
        });
    } else if (file1) {
        compressImage(file1, 600, function(compressedBlob1) {
            data.append('upload_aadhar', compressedBlob1, file1.name);
            SendAjaxRequestToServer(type, url, data, '', registerUserResponse, '', '.register_form_submit');
        });
    } else {
        SendAjaxRequestToServer(type, url, data, '', registerUserResponse, '', '.register_form_submit');
    }

    
    // if (file) {
    //     compressImage(file, 600, function(compressedBlob) {
    //         data.append('upload_picture', compressedBlob, file.name);
    //     });
    // }
    // if(file1){
    //     compressImage(file1, 600, function(compressedBlob1) {
    //         data.append('upload_aadhar', compressedBlob1, file1.name);
    //         SendAjaxRequestToServer(type, url, data, '', registerUserResponse, '', '.register_form_submit');
    //     });
    // }else{
    //     SendAjaxRequestToServer(type, url, data, '', registerUserResponse, '', '.register_form_submit');
    // }

    

});



function registerUserResponse(response) {

    // SHOWING MESSAGE ACCORDING TO RESPONSE
    console.log(response);
    if (response.status == 200 || response.status == '200') {

        toastr.success(response.message, '', {
            timeOut: 3000
        });

        let form = $('#registration_form');
        form.trigger("reset");

        var data = response.data;
        $("#username_auto").val(data.username_auto);

        $("#picturename").text('Upload Picture');
        $("#filename").text('Upload Aadhar');
        const userData = response.data.userData;
        const formData = response.data.formData;
        const payment_settings = response.data.payment_gateway_settings;
        
        // const dataObj = {
        //     uData: userData,
        //     fData: formData
        // }
        // const datax = JSON.stringify(dataObj);
        if(payment_settings == 'on'){
            $("#user_id").val(userData.id);
            setTimeout(function () {
                // toDoPayment(datax);
                $("#online_pay_form").submit();
            }, 2000);
        }


    } else {

        error = response.responseJSON.message;
        var is_invalid = response.responseJSON.errors;

        $.each(is_invalid, function (key) {
            // Assuming 'key' corresponds to the form field name
            var inputField = $('[name="' + key + '"]');
            var selectField = $('[name="' + key + '"]');
            // Add the 'is-invalid' class to the input field's parent or any desired container
            inputField.closest('.form-control').addClass('is-invalid');
            selectField.closest('.form-select').addClass('is-invalid');
        });
        toastr.error(error, '', {
            timeOut: 3000
        });
    }
}




$(document).ready(function () {
    // getPageData();

});

document.getElementById('cameraIcon').addEventListener('click', function () {
    // Trigger click event on the input field
    document.getElementById('upload_picture').click();
});
document.getElementById('upload_picture').addEventListener('change', function () {
    if (this.files.length > 0) {
        // Get the filename of the selected file
        var fileSize = this.files[0].size; // Size in bytes
        var maxSize = 400 * 1024; // 400 KB in bytes

        // if (fileSize > maxSize) {
        //     $("#upload_picture").val('');
        //     document.getElementById('picturename').innerText = 'Upload Picture';
        //     toastr.error('File size exceeds 400 KB. Please choose a smaller file.', '', {
        //         timeOut: 3000
        //     });
          
        // }
        // else{

            var filename = this.files[0].name;
    
            // Update the content of the <span> element with the filename
            document.getElementById('picturename').innerText = filename;
            toastr.success('Uploaded', '', {
                timeOut: 3000
            });
        // }
    } else {
        // No file selected, reset the content of the <span> element
        document.getElementById('picturename').innerText = 'Upload Picture';
    }
});

document.getElementById('aadharUploadIcon').addEventListener('click', function () {
    // Trigger click event on the input field
    document.getElementById('upload_aadhar').click();
});
document.getElementById('upload_aadhar').addEventListener('change', function () {
    if (this.files.length > 0) {
        // Get the filename of the selected file
        // var fileSize = this.files[0].size; // Size in bytes
        // var maxSize = 400 * 1024; // 400 KB in bytes

        // if (fileSize > maxSize) {
        //     $('#upload_aadhar').val('');
        //     document.getElementById('filename').innerText = 'Upload Aadhar';
        //     toastr.error('File size exceeds 400 KB. Please choose a smaller file.', '', {
        //         timeOut: 3000
        //     });
          
        // } else {

            var filename = this.files[0].name;
            let file = this.files[0];
            compressImage(file, 600, function(compressedBlob) {
                $("#upload_aadhar").val(compressedBlob);
            });

            // Update the content of the <span> element with the filename
            document.getElementById('filename').innerText = filename;
            toastr.success('Uploaded', '', {
                timeOut: 3000
            });
        // }
    } else {
        // No file selected, reset the content of the <span> element
        document.getElementById('filename').innerText = 'Upload Aadhar';
    }
});

function compressImage(file, maxSizeKB, callback) {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(event) {
        const img = new Image();
        img.src = event.target.result;
        img.onload = function() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // Set maximum dimensions
            const MAX_WIDTH = 800;
            const MAX_HEIGHT = 800;
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(img, 0, 0, width, height);

            let quality = 0.9; // Start with high quality
            function tryCompress() {
                canvas.toBlob(function(blob) {
                    if (blob.size <= maxSizeKB * 1024 || quality <= 0.1) {
                        callback(blob);
                    } else {
                        quality -= 0.1; // Reduce quality in smaller steps
                        tryCompress();
                    }
                }, 'image/jpeg', quality);
            }
            tryCompress();
        }
    }
}