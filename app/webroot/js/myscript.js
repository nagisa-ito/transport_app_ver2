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
                    $('#myStat').empty();
                    $('#myStat').circliful({
                        animationStep: 12,
                        percent: 100,
                        foregroundColor: '#1bab9e',
                        text: '¥' + response.total_cost,
                        noPercentageSign: true,
                    });
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
    $('a.is_no_request').click(function(e) {
        var no_request_month = $('#no_request_month').val();
        $.post('/transport_expense_app/eachmonthrequests/index/' + $(this).data('user_id') + '/' + no_request_month, {},
            function(response) {
                console.log(response);
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

//往復であればフォームの値を2倍にする
$('#RequestDetailOnewayOrRound').change(function() {
    str = $('#RequestDetailOnewayOrRound option:selected').text();
    cost = $('#RequestDetailCost').val();
    if(str == '往復') {
        $('#RequestDetailCost').val(cost*2);
    } else {
        $('#RequestDetailCost').empty();
        $('#RequestDetailCost').val(cost/2);
    }
}).change();

//指定の部署以外のデータを非表示
$('#department_id').change(function() {
    var department_id = $('#department_id option:selected').val();
    var ele = $('a.myset').data('department_id');
    if(ele == department_id){
        $('a.myset').hide();
    }
}).change();
