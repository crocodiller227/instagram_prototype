$(document).ready(function () {
    let create_comment_div = function (avatar_url, comment) {
        return $(`
            <div class="comment" >
                <div class="comment_avatar user_avatar_min show_user_click" id="${$('body').data('current-user-id')}" style="background-image: url(${avatar_url})"></div>
                <div class="comment_text">${comment}</div>
            </div>
`);
    };
    $('.comments').on('click', '.add_comment_btn', function () {
        let element = $(this);
        let comment = element.closest('.comment_form').children('.comment_input').val();
        let post_id = element.data('post-id');
        let comments_div = element.closest('.comments');
        let user_avatar_url = element.data('user-avatar-url');
        let root_url = $('body').data('root-url');
        $.post(`${root_url}post/add_comment/${post_id}`, {comment:comment}).done(function (request) {
            console.log(request.comment);
            if(comments_div.children('.grey_headers')){
                comments_div.children('.grey_headers').remove();
                comments_div.children('.input-group').before(create_comment_div(user_avatar_url, comment))

            }
        }).fail();
    });
});
