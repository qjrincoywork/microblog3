var fx = {
    "displayCustomizedDialogBox": function (data, id, width) {
        if (width != undefined) {
            $(".modal-dialog").removeClass("modal-lg modal-xl modal-sm h-auto");
            $(".modal-dialog").addClass(width);
        } else {
            $(".modal-dialog").removeClass("modal-lg modal-xl modal-sm h-auto");
        }

        $(".modal-body").html("");

        var fLabel = id.toUpperCase();

        $(".modal .modal-title").html(fLabel.replace(/_/g, " "));

        $(".modal-body").html(data);

        $(".modal").modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $(".modal").modal("show");

        if ($(".modal .modal-body").innerHeight() > $("body").innerHeight()) {
            $(".modal .modal-dialog").css("height", "100%");
        } else {
            $(".modal .modal-dialog").removeAttr('style');
        }

        if ($(".simplebar-content").length) {
            $("body").css("overflow", "hidden");
            $(".modal").on("shown.bs.modal", function () {
                $("body>.modal-backdrop.fade.show").css("display", "none");
                if ($(".simplebar-content>.modal-backdrop.fade.show").length != 1)
                    $('<div class="modal-backdrop fade show"></div>').appendTo(".simplebar-content");
                $(".simplebar-track").css("display", "none");
            });
            $(".modal").on("hide.bs.modal", function () {
                $(".modal-backdrop.fade.show").remove();
                $(".simplebar-track").css("display", "unset");
            });
        }
    },
    'UIHelper': function () {
        console.log('UIHelper');

        $("[data-toggle='tooltip']").tooltip();

        //form error message remove on input
        $('.form-control').on('input', function () {
            $(this).next('.errors').fadeOut();
        });

    },
    'modalUiHelper': function () {

        //form error message remove on input
        $('.form-control').on('input', function () {
            $(this).removeClass('is-invalid');
            $(this).nextAll('.help-block').fadeOut();
        });

        $('input[name=birth_date]').on('dp.change', function () {
            $(this).removeClass('is-invalid');
            $(this).nextAll('.help-block').fadeOut();
            $('input[name=age]').removeClass('is-invalid');
            $('input[name=age]').nextAll('.help-block').fadeOut();
        });

        //when modal close
        $('.modal').on('hidden.bs.modal', function (event) {
            switch ($('.modal-title').html().toLowerCase()) {
                case 're-assign':
                    $('.sortable-group').sortable("cancel");
                    break;
            }
            $('.modal-body').html('');
            $(this).data('bs.modal', null);
        });
    },
    "displayFormErrorMessages": function (jsonError) {
        $(".help-block").remove();
        console.log(jsonError);
        $.each(jsonError.errors, function (key, value) {
            $("#nf-" + key).addClass('is-invalid');
            console.log(key);
            if (value.length == 1) {
                $("#nf-" + key).after("<span class='help-block'>" + value + "</span>")
            } else {
                for (i = 0; i < value.length - 1; i++) {
                    if (i == value.length - 1) {
                        console.log('w/o br');
                        $("#nf-" + key).after("<span class='help-block'>" + value[i] + "<br></span>")
                    } else {
                        console.log('w/ br');
                        $("#nf-" + key).after("<span class='help-block'>" + value[i] + "</span>")
                    }

                }
            }
        });

        console.log('fx.displayFormErrorMessage');
    },
    'showFormErrors': function(errors) {
        $.each(errors, function(fieldName, message){
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
    },
    "displayNonModalFormErrorMessages": function (jsonError, form) {
        var content = "<ul class='errors'></ul>",
            errorWrapper = "<li>",
            endWrap = "</li>";

        $(form).find($(".errors")).remove();

        $.each(jsonError.errors, function (key, value) {

            if ($(form).find("#nf-" + key).closest("div").length != 0) {
                var container = $(form).find("#nf-" + key).closest("div");
            } else {
                container = $(form).find("label[for='nf-" + key + "']").parent();
            }

            $(container).append(content);

            $.each(value, function (key, value) {
                error = errorWrapper + value;
            });
            error += endWrap;

            $(container).find("ul").html(error);
        });
    },
    "displayNotify": function (title, mssg, type) {
        // console.log('displayNotify');
        $.notifyClose();
        $.notify({
            // options
            icon: 'glyphicon glyphicon-warning-sign',
            title: title,
            message: mssg,
        }, {
            // settings
            element: 'body',
            position: null,
            type: type,
            allow_dismiss: true,
            newest_on_top: true,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 20,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div style="text-align:center;" data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });

    },
    "displayError": function (title, mssg, type) {
        $.notify({
            // options
            icon: 'glyphicon glyphicon-warning-sign',
            title: title,
            message: mssg,
        }, {
            // settings
            element: 'body',
            position: null,
            type: type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 20,
            spacing: 10,
            z_index: 1031,
            delay: 30000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div style="text-align:center;" data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });

    },
    "imageReadUrl": function (input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    },
    "googleAPIAccess": function () {

        var options = {
            componentRestrictions: {
                country: "ph"
            }
        };
        var input = document.getElementById('nf-address');
        new google.maps.places.Autocomplete(input, options);

    },
    "googleAutocomplete": function () {
        google.maps.event.addDomListener(window, 'load', fx.googleAPIAccess());
    },

}

$(function () {
    fx.modalUiHelper();
    fx.UIHelper();
});

function showTime() {
    var time = new Date().toLocaleString("en-US", {
        timeZone: "Asia/Manila"
    });
    time = new Date(time);
    if (document.getElementById('time') != null) {
        document.getElementById('time').innerHTML = time.toLocaleTimeString();

        var date = new Date().toLocaleString("en-US", {
            timeZone: "Asia/Manila"
        });
        date = new Date(date);
        document.getElementById('date').innerHTML = date.toLocaleDateString();
    }
}

setInterval(showTime, 1000);
