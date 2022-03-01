$('#formPublicate').submit(function (e) {
    e.preventDefault();
    var category = $('#select-category').val();
    var newCategory = $('#newCategory').val();


    $(".error").remove();

    if (category !== '' && newCategory !== '') {
        $('#newCategory').after('<span style="color: red" class="error">Выбрано 2 категории</span>');
    }


    var data = {};
    data['category'] = newCategory;

    if ($(".error").length == 0) {
        $.ajax({
            type: 'post',
            url: '../includes/validationPublicate.php',
            data: {
                key: 'cat',
                data: data
            },
            dataType: 'text',
            success: function (response) {
                var resp = JSON.parse(response);

                console.log(resp)

                // if (resp.msg === 'signUp') {
                //     window.location.reload()
                // } else if (resp.msg === 'User Error') {
                //     $('#formSignUp').after('<span style="color: red" class="error">This user already exists. </span>');
                // }
            }
        })
    }

    //$('#formSignUp').after('<span style="color: red" class="error">Incorrect login or password. </span>');

});

