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

$(function() {
    $('#datepicker').datepicker({
        //dateFormat: 'yy-mm-dd',
    });
});

$(document).ready(function() {
    $('#myStat').circliful({
        animationStep: 12,
        percent: 100,
        foregroundColor: '#1bab9e',
        text: total_cost,
        noPercentageSign: true,
    });
});
