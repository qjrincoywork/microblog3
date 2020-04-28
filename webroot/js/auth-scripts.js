if(document.getElementById('flashMessage') !== null) {
    window.setTimeout("document.getElementById('flashMessage').style.display='none';", 3000);
}

var fxAuth = {
    'UIHelper': function () {
        console.log('fxAuth UIHelper');

        $("[data-toggle='tooltip']").tooltip();
        //form error message remove on input
        $('.form-control').on('input change', function () {
            $(this).next('.errors').fadeOut();
            $(this).closest('.form-group').nextAll('.help-block').fadeOut();
        });

        if ($('input[type=radio]').is(':checked') == false) {
            $('input[type=radio]').on('change', function () {
                $(this).closest('.form-group').nextAll('.help-block').fadeOut();
            });
        }
    },
    'displayFormErrorMessages': function(jsonError) {
        $.each(jsonError.errors, function(fieldName, message){
            $("[id="+fieldName+"]").addClass('is-invalid');
            
            if (message.length == 1) {
                $("[id="+fieldName+"]").nextAll(".help-block").remove();
                $("[id="+fieldName+"]").after("<span class='help-block'>" + message[0] + "</span>")
            } else {
                for (i = 0; i < message.length - 1; i++) {
                    $("[id="+fieldName+"]").nextAll(".help-block").fadeOut();
                    if (i == message.length - 1) {
                        console.log('w/o br');
                        $("[id="+fieldName+"]").after("<span class='help-block'>" + message[i] + "<br></span>")
                    } else {
                        console.log('w/ br');
                        $("[id="+fieldName+"]").after("<span class='help-block'>" + message[i] + "</span>")
                    }

                }
            }
        });
    }
};

$(function () {
    fxAuth.UIHelper();
    $("body").on("click", ".register_user, .login_use", function (event) {
        
        event.preventDefault();
        event.stopPropagation();
        var form = $(this).closest("form").not(".form-login"),
            action = form.attr("action"),
            className = $(this).attr("class").split(" ")[0],
            url = $(this).attr("href"),
            modal = false,
            csrfToken = $('meta[name="csrf-token"]').attr('content'),
            me = this,
            fd = new FormData();

        if (action == undefined) {
            posting = $.get(url);
        } else {
            form.find("input, select").each(function () {
                if ($(this).attr("type") != "file") {
                    fd.append($(this).attr("name"), $(this).val());
                } else {
                    fd.append($(this).attr("name"), $(this)[0].files[0]);
                }
            });
            
            posting = $.ajax({
                type: "post",
                url: action,
                data: fd,
                headers: {
                    "X-CSRF-Token": csrfToken
                },
                cache: false,
                processData: false,
                contentType: false
            });
        }

        posting.done(function (data) {
            if(data.success){
                if(className == 'register_user') {
                    fx.displayNotify("User", 
                                     "Register Successful, your activation link has been sent to your email.", 
                                     "success");
                    setTimeout(function () {
                        location.reload()
                    }, 3000);
                } else {
                    window.location = '/users/home';
                }
            } else {
                if(className == 'register_user') {
                    if (data.errors) {
                        fxAuth.displayFormErrorMessages(data, form);
                    } else {
                        fx.displayNotify("User", "Registration failed.", "danger");
                    }
                } else {
                    if (data.errors) {
                        fxAuth.displayFormErrorMessages(data, form);
                    } else {
                        fx.displayNotify("", data.error, "danger");
                    }
                    // fx.displayNotify("User", data.errors, "danger");
                }
            }
        })
    });
});
