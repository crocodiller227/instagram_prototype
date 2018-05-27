$(function () {
    $("body").on('click', ".show_user_click", function () {
        $(location).attr('href', `${$('body').attr('data-root-url')}user/${$(this).attr('id').replace(/user_id_/gi, '')}`);
    });
});