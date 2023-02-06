$('#reason_category').on('change', function () {
    var reason_category_id = this.value;
    $.ajax({
        url: 'cause_of_offline.php',
        type: "POST",
        data: {
            reason_category_data: reason_category_id
        },
        success: function (result) {
            $('#cause_category').html(result);
        }
    })
})

$('#cause_category').on('change', function () {
    var cause_category_id = this.value;
    $.ajax({
        url: 'action_taken.php',
        type: "POST",
        data: {
            cause_category_data: cause_category_id
        },
        success: function (result) {
            $('#action_category').html(result);
        }
    })
})

function imagePreview(fileInput) {
    if (fileInput.files && fileInput.files[0]) {
        var fileReader = new FileReader();
        fileReader.onload = function (event) {
            $('#preview').html('<img src="' + event.target.result + '" width="200" height="auto"/>');
        };
        fileReader.readAsDataURL(fileInput.files[0]);
    } 
}
$("#image").change(function () {
    imagePreview(this);
});
$('#preview').html('<img src="uploads/upload.png" width="200" height="auto"/>');