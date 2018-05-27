$(function () {
    $('#search_user_submit').on('click', function (event) {
        event.preventDefault();
        let username = $('#search_user_id').val();
        $.post(`${$('body').data('root-url')}search_user/${username}`, {}, function (response) {
            $('#search_user_id').notify(`Redirecting...`, "success");
            setTimeout(function () {
                window.location.replace(response.user_url);
            }, 1000);
        }).fail(function (response) {
            console.log(response);
            if (typeof response.responseJSON !== "undefined") {
                $('#search_user_id').notify(`${response.responseJSON.message}`, "warning");
            }
        })
    });
});