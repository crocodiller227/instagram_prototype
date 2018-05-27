$(document).ready(function () {
    let user_min = $('.user_min');
    let body = $('body');
    let createFollowButton = function (id, action) {
        let element;
        if (action === 'follow') {
            element = $(`<button class="btn btn-info follow_user" id="follow_user_${id}">Follow</button>`);
        } else if (action === 'unfollow') {
            element = $(`<button class="btn btn-success unfollow_user" id="unfollow_user_${id}">Unfollow</button>`);
        }
        return element;
    };

    user_min.on('click', '.follow_user', function () {
        let elem_id = $(this).attr('id');
        let element = $(`#${elem_id}`);
        let user_id = elem_id.replace(/follow_user_/gi, '');
        let element_parent = element.parent();
        $.post(`${body.data('root-url') + 'follow/' + user_id}`, function (response) {
            element.notify(response.message, "success");
            element.remove();
            element_parent.append(createFollowButton(user_id, 'unfollow'));
        }).fail(function () {
            element.notify('An error occurred while trying to follow. Try again', "warning");
        })
    });

    user_min.on('click', '.unfollow_user', function () {
        let elem_id = $(this).attr('id');
        let element = $(`#${elem_id}`);
        let user_id = elem_id.replace(/unfollow_user_/gi, '');
        let element_parent = element.parent();
        $.ajax({
            url: `${body.data('root-url') + 'unfollow/' + user_id}`,
            type: 'DELETE',
            success: function (response) {
                element.notify(response.message, "success");
                element.remove();
                element_parent.append(createFollowButton(user_id, 'follow'));
            },
            error: function () {
                element.notify('An error occurred while trying to unfollow. Try again', "warning");
            }
        });
    });
});