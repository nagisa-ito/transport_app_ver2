$(function(){
    setTimeout(function(){
        $('#flashMessage').fadeOut('slow');
    }, 800);
});

$(function() {
    $('a.delete').click(function(e) {
        if (confirm('削除しますか？')) {
                                            /* /deleteアクションへのパス/削除する申請のid/現在のユーザのid/ */
            $.post('/transport_app_ver2_1/requestdetails/delete/'
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

$(function() {
    $('#datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
    });
});

$('#YearMonth').datepicker({
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
