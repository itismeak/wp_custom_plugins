jQuery(function($) {
    $(document).ready(function() {
        $("form").submit(function(e) {

            e.preventDefault();
            var name = $('.name').val();
            var email = $('.mail').val();
            var psw = $('.psw').val();
            //var file = $('.file').val();
            var form = $(this);

            if (name != "" && email != "" && psw != "") {
                $.ajax({
                        type: "post",
                        url: cpm_object.ajax_url,
                        data: {
                            action: "set_form",
                            name: name,
                            mail: email,
                            psw: psw
                        }
                    })
                    .done(function() {
                        $(".error").html("Submited sucessfully").css("color", "green");
                        form[0].reset();
                    })
                    .error(function() {
                        alert("error");
                        $(".error").html("Something went wrong").css("color", "red");
                    });
            } else {
                $(".error").html("please fill all fields").css("color", "red");
                return false;
            }

        });
    });
});