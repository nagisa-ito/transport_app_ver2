$(function() {
    $('a.delete_icon').click(function(e) {
        console.log($(this).data('id'));
        if(confirm('削除しますか？')) {
            $.post('/transportation_expenses_app_v2/sections/delete/' + $(this).data('id'), {},
                function(response) {
                    $('#section_'+response.id).fadeOut();
                }, "json");
        }
        return false;
    });
});
