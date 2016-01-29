/*
 * Autocompletion (for city)
 */
$(document).ready(function () {
//    $(function () {
$("#autocomplete").autocomplete({ // Permet l'autocomplétion des villes
    source: function (request, response) {
        $.ajax({
            url: base_url + "my_hikes/suggestions",
            data: {
                term: $("#autocomplete").val()
            },
            dataType: "json",
            type: "POST",
            success: function (data) {
                response(data);
            }
        });
    },
    minLength: 2
});

/*
 * To animate pseudo (sign out, my account)
 */
$("#connected-box").click(function () {
    if ($("#account").css("display") == "block") {
        $('#account').fadeOut(200);

        $('#connected-box').animate({
            bottom: '0'
        }, {
            duration: 300,
            easing: 'swing'
        });
    } else {
        $('#connected-box').animate({
                bottom: '38px'
            }, // what we are animating
            {
                duration: 300, // how fast we are animating
                easing: 'swing', // the type of easing
                complete: function () { // the callback
                    $("#account").fadeIn(500);
                }
            });
    }
});
    });
//});