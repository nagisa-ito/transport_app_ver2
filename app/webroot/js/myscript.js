$(function(){
    setTimeout(function(){
        $('#flashMessage').fadeOut('slow');
    }, 800);
});

$(function() {
    $('a.delete').click(function(e) {
        if (confirm('削除しますか？')) {
                            /* /deleteアクションへのパス/削除する申請のid/現在のユーザのid/ */
            $.post('/transport_expense_app/requestdetails/delete/'
                    + $(this).data('request_id') + '/' + $(this).data('user_id') + '/' + $(this).data('year_month') ,{},
                function(response) {
                    $('#request_'+response.request_id).fadeOut();
                    $('#total_cost').html(response.total_cost);
            }, "json");
        }
        return false;
    });
});

//申請確定ボタン
$(function() {
    $('a.confirm').click(function(e) {
        if(confirm('確定してもよろしいですか？')) {
            $.post('/transport_expense_app/confirmmonths/add/'
                + $(this).data('year_month') + '/' + $(this).data('user_id'), {},
            function(response) {
                confirm('確定しました。');
                $('.confirm_state').show();
            }, "json");
        }
        return false;
    });
});

$(function() {
    $('#no_request').click(function(e) {
        var year_month = $('#no_request_month').val();
        var user_id = $('#no_request_user_id').val();
        $.post('/transport_expense_app/confirmmonths/add/' + year_month + '/' + user_id + '/1', {},
            function(response) {
                confirm('確定しました。');
                location.reload();
            }, "json");
    });
});

$('#datepicker').datepicker({
    format: 'yyyy-mm-dd',
    language: 'ja'
});


$('#YearMonth').datepicker({
      format: 'yyyy-mm',
      language: 'ja',       // カレンダー日本語化のため
      minViewMode : 1
  });

$('#no_request_month').datepicker({
        format: 'yyyy-mm',
        language: 'ja',       // カレンダー日本語化のため
        minViewMode : 1
});

//指定の部署以外のデータを非表示
$('#department_id').change(function() {
    var department_id = $('#department_id option:selected').val();
    var ele = $('a.myset').data('department_id');
    if(ele == department_id){
        $('a.myset').hide();
    }
}).change();

$(function() {
    var $input = $('#input');
    var $output = $('#output2');
    $input.on('change', function(event) {
    $output.text($input.val());
    });
});