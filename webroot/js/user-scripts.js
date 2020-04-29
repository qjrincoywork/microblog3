if(document.getElementById('flashMessage') !== null) {
    window.setTimeout("document.getElementById('flashMessage').style.display='none';", 3000);
}

var fxUser = {
    'UIHelper': function () {
        $("[data-toggle='tooltip']").tooltip();
        
        $('.form-control').on('input change', function () {
            $(this).removeClass('is-invalid');
            $(this).nextAll('.help-block').fadeOut();
        });
    },
    'displayFormErrorMessages': function(errors, form) {
        $(form).find($(".errors")).remove();
        
        $.each(errors, function(fieldName, message){
            $(form).find("[id="+fieldName+"]").addClass('is-invalid');
            
            if (message.length == 1) {
                $(form).find("[id="+fieldName+"]").nextAll(".help-block").remove();
                $(form).find("[id="+fieldName+"]").after("<span class='help-block'>" + message[0] + "</span>")
            } else {
                for (i = 0; i < message.length - 1; i++) {
                    $(form).find("[id="+fieldName+"]").nextAll(".help-block").fadeOut();
                    if (i == message.length - 1) {
                        console.log('w/o br');
                        $(form).find("[id="+fieldName+"]").after("<span class='help-block'>" + message[i] + "<br></span>")
                    } else {
                        console.log('w/ br');
                        $(form).find("[id="+fieldName+"]").after("<span class='help-block'>" + message[i] + "</span>")
                    }
                }
            }
        });
    }
};

$(function () {
    fxUser.UIHelper();
    $("body").on("click", ".post_content, .like_post, .comment_post, .edit_comment, .delete_comment, .restore_comment,"+
                          ".edit_post, .share_post, .delete_post, .restore_post,"+
                          ".follow_user, .unfollow_user, .edit_profile, .get_follow," +
                          ".update_picture, .change_password, .cancel_upload," + 
                          ".preview_image, .edit_preview_image", function (event) {
        event.preventDefault();
        event.stopPropagation();
        
        var form = $(this).closest("form").not(".form-group"),
            action = form.attr("action"),
            formId = form.attr('id'),
            className = $(this).attr("class").split(" ")[0],
            url = $(this).attr("href"),
            csrfToken = $('meta[name="csrf-token"]').attr('content'),
            modal = false,
            me = this,
            fd = new FormData();
            
        switch (className) {
            case 'cancel_upload':
                $(form).find('#preview-image').html('');
                $(form).find("input[id='image']").val('');
                return false;
                break;
            case 'preview_image':
                $(form).find("input[id='image']").click();
                return false;
                break;
            case 'edit_preview_image':
                $(form).find("input[id='image']").click();
                return false;
                break;
            case 'like_post':
                fd.append("_method", "POST");
                fd.append("_csrfToken", csrfToken);
                
                posting = $.ajax({
                    type: "post",
                    url: url,
                    data: fd,
                    headers: {
                        "X-CSRF-Token": csrfToken
                    },
                    cache: false,
                    processData: false,
                    contentType: false
                });
                break;
            case 'follow_user':
            case 'unfollow_user':
                fd.append("_method", "POST");
                fd.append("_csrfToken", csrfToken);
                
                posting = $.ajax({
                    type: "post",
                    url: url,
                    data: fd,
                    headers: {
                        "X-CSRF-Token": csrfToken
                    },
                    cache: false,
                    processData: false,
                    contentType: false
                });
                break;
            default:
                if (action == undefined) {
                    if(className != 'get_follow') {
                        modal = true;
                    }
                    posting = $.get(url);
                } else {
                    form.find("input, file, select").each(function () {
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
                            "X-CSRF-Token": $('[name="_csrfToken"]').val()
                        },
                        cache: false,
                        processData: false,
                        contentType: false
                    });
                }
                break;
        }
        
        posting.done(function (data) {
            if(action == undefined) {
                if(modal) {
                    switch (className) {
                        /* case "comment_post":
                        case "share_post":
                            width = "modal-lg";
                            break; */
                        default:
                            width = undefined;
                            break;
                    }
    
                    fx.displayCustomizedDialogBox(
                        data,
                        className,
                        width
                    );
                    fx.modalUiHelper();
                } else {
                    switch (className) {
                        case "follow_user":
                        case "unfollow_user":
                            $("#mainContent").load(location.href);
                            break;
                        case "get_follow":
                            $("#profile-post-container").html(data);
                            break;
                        default:
                            $("#mainContent").load(location.href);
                            break;
                    }
                }
            } else {
                if(data.success) {
                    switch (className)
                    {
                        case "add_post":
                        case "edit_post":
                        case "comment_post":
                        case "edit_profile":
                        case "edit_comment":
                            suffix = "ed";
                            break;
                        default:
                            suffix = "d";
                            break;
                    }
                    
                    $(".modal").modal("hide");
                    var title = className.split("_")[1],
                        notifAction = className.split("_")[0];
                        switch (className)
                        {
                            case "edit_profile":
                            case "update_picture":
                                fx.displayNotify(
                                    title.charAt(0).toUpperCase() + title.slice(1),
                                    "Successfully " + notifAction + suffix + ".",
                                    "success"
                                );
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                                break;
                            case "edit_post":
                            case "share_post":
                            case "comment_post":
                            case "delete_post":
                            case "restore_post":
                            case "edit_comment":
                            case "delete_comment":
                            case "restore_comment":
                            case "change_password":
                                fx.displayNotify(
                                    title.charAt(0).toUpperCase() + title.slice(1),
                                    "Successfully " + notifAction + suffix + ".",
                                    "success"
                                );
                                setTimeout(function () {
                                    $("#mainContent").load(location.href);
                                }, 1000);
                                break;
                            default:
                                // $("#mainContent").load(location.href);
                                location.reload();
                                break;
                        }
                } else {
                    if(data.errors) {
                        fxUser.displayFormErrorMessages(data.errors, form);
                    } else {
                        fx.displayNotify("", data.error, "danger");
                    }
                }
            }
        }).fail(function (xhr, status, error) {
            fx.displayNotify("User", error, "danger");
        });
    });
    
    $('body').on('change', '.image_input, .add_image_input', function (event) {
        var form = $(this).closest("form").not(".form-group"),
            className = $(this).attr("class").split(" ")[0],
            fileType = this.files[0]['type'],
            validImageTypes = ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'],
            reader = new FileReader();
        if(className == 'image_input') {
            if ($.inArray(fileType, validImageTypes) >= 0) {
                fx.imageReadUrl(this);
            } else {
                fx.displayNotify("Invalid", "file input", "danger");
            }
        } else {
            if (this.files && this.files[0]) {
                if ($.inArray(fileType, validImageTypes) >= 0) {
                    reader.onload = function (e) {
                        image =  "<img src='"+ e.target.result +"' alt='your image'>" +
                                    "<button href='#' class='cancel_upload' title='remove'>" +
                                        "<i class='fas fa-times-circle'></i>" +
                                    "</button>";
                        $(form).find('.preview-image').html(image);
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    fx.displayNotify("Invalid", "file input", "danger");
                }
            }
        }
    });

    $('#search').on('keypress',function(e) {
        if(e.which == 13) {
            var value = $(this).val(),
                csrfToken = $('meta[name="csrf-token"]').attr('content'),
                fd = new FormData(),
                url = $(this).attr("href");
            if(!value) {
                fx.displayNotify(
                    "Search field",
                    "can't be empty",
                    "danger"
                );
            } else {
                fd.append("_method", "GET");
                fd.append("_csrfToken", csrfToken);
                
                posting = $.ajax({
                    type: "post",
                    url: url + '/' + value,
                    data: fd,
                    headers: {
                        "X-CSRF-Token": csrfToken
                    },
                    cache: false,
                    processData: false,
                    contentType: false
                });
                
                posting.done(function (data) {
                    $("#mainContent").html(data);
                })
            }
        }
    });
});
