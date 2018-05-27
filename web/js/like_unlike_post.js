$(document).ready(function () {
    let like_post_div = $('.like_post');
    let root_url = $('body').data('root-url');
    let createLikeButton = function (id, like_amount, action) {
        let element;
        if (action === 'like') {
            element = $(`<i class="far fa-thumbs-up like_click"></i>
                <span>${like_amount}</span>`);
        } else if (action === 'liked') {
            element = $(`<i class="fas fa-thumbs-up liked_click"></i>
                    <span>${like_amount}</span>`);
        }
        return element;
    };

    like_post_div.on('click', '.like_click', function () {
        let element = $(this);
        let element_parent = element.parent();
        let post_id = element_parent.attr('id').replace(/like_post_id_/gi, '');
        let like_amount_span = element_parent.children($('span'));
        let likes_amount = +like_amount_span.text();
        $.post(`${root_url}like/${post_id}`, function () {
            element_parent.children().remove();
            element_parent.append(createLikeButton(post_id, likes_amount + 1, 'liked'));
        }).fail(function () {
            element.notify('An error occurred while attempting to give like. Try it again', "warning");
        })
    });

    like_post_div.on('click', '.liked_click', function () {
        let element = $(this);
        let element_parent = element.parent();
        let post_id = element_parent.attr('id').replace(/like_post_id_/gi, '');
        let like_amount_span = element_parent.children($('span'));
        let likes_amount = +like_amount_span.text();
        $.ajax({
            url: `${root_url}unlike/${post_id}`,
            type: 'DELETE',
            success: function () {
                element_parent.children().remove();
                element_parent.append(createLikeButton(post_id, likes_amount - 1, 'like'));
            },
            error: function () {
                element.notify('An error occurred while trying to unfollow. Try again', "warning");
            }
        });
    });
});