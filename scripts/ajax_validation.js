$(document).ready(function() {
    $('#formSignIn').submit(function (e) {
        e.preventDefault();
        var login = $('#loginIn').val();
        var password = $('#passwordIn').val();

        $(".error").remove();

        if (login.length < 1) {
            $('#loginIn').after('<span style="color: red" class="error">This field is required.</span>');
        }
        if (password.length < 1) {
            $('#passwordIn').after('<span style="color: red" class="error">This field is required.</span>');
        }
        if (password.length < 6) {
            $('#passwordIn').after('<span style="color: red" class="error">Password must be atleast 8 characterslong. </span>');
        }

        var data = {};

        data["login"] = login;
        data["password"] = password;

        if (login !== '' && password !== '') {
            $.ajax({
                type: 'post',
                url: '../includes/validationForm.php',
                data: {
                    key: 'in',
                    data: data
                },
                dataType: 'text',
                success:function(response) {
                    var resp = JSON.parse(response);

                    if (resp.msg === 'signIn') {
                        window.location.reload()
                    } else if (resp.msg === 'error') {
                        $('#formSignIn').after('<span style="color: red" class="error">Username/Password incorrect. </span>');
                    }
                }
            })
        }
    });


    $('#formSignUp').submit(function (e) {
        e.preventDefault();
        var login = $('#loginUp').val();
        var password = $('#passwordUp').val();

        $(".error").remove();

        if (login.length < 1) {
            $('#loginUp').after('<span style="color: red" class="error">This field is required.</span>');
        }
        if (password.length < 1) {
            $('#passwordUp').after('<span style="color: red" class="error">This field is required.</span>');
        }
        if (password.length < 6) {
            $('#passwordUp').after('<span style="color: red" class="error">Password must be atleast 8 characterslong. </span>');
        }

        var data = {};

        data["login"] = login;
        data["password"] = password;


        if (login !== '' && password !== '') {
            $.ajax({
                type: 'post',
                url: '../includes/validationForm.php',
                data: {
                    key: 'up',
                    data: data
                },
                dataType: 'text',
                success:function(response) {
                    var resp = JSON.parse(response);

                    if (resp.msg === 'signUp') {
                        window.location.reload()
                    } else if (resp.msg === 'error') {
                        $('#formSignUp').after('<span style="color: red" class="error">This user already exists. </span>');
                    }
                }
            })
        }
    });


    $('#logout').submit(function (e) {
        e.preventDefault();
            $.ajax({
                type: 'post',
                url: '../includes/validationForm.php',
                data: {
                    key: 'logout',
                },
                success:function() {
                    window.location.reload()
                }
            })
    });
});
