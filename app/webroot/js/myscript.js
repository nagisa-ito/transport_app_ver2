$(function(){
    setTimeout(function(){
        $('#flashMessage').fadeOut('slow');
    }, 800);
});

$(function() {
    $('a.delete').click(function(e) {
        if (confirm('削除しますか？')) {
            $.post('/transport_app_ver2_1/requestdetails/delete/'+$(this).data('request-id'), {}, function(res) {
                $('#request_'+res.id).fadeOut();
            }, "json");
        }
        return false;
    });
});
