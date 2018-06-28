$(function() {
    $('#search_travel_section').click(function(e) {
        $.post('/transport_expense_app/requestdetails/search_travel_section/'
                + $('#company_autocomplete').val(), {},
            function(response) {
                console.log(response.Company.id);
                if(!$.isEmptyObject(response)) {
                    $('#from_station_autocomplete').val(response.Company.from);
                    $('#to_station_autocomplete').val(response.Company.to);
                    $('#cost').val(response.Company.cost);
                }
            }, "json");
        return false;
    });
});

//企業名のサジェスト機能
$(function() {
    $("#company_autocomplete").autocomplete({
        source: Object.values(companies),
        autoFocus: true,
        delay: 500,
        minLength: 1
    });
});

//駅名のサジェスト機能
$(function() {
    $("#from_station_autocomplete").autocomplete({
        source: Object.values(stations),
        autoFocus: true,
        delay: 500,
        minLength: 1
    });
});

$(function() {
    $("#to_station_autocomplete").autocomplete({
        source: Object.values(stations),
        autoFocus: true,
        delay: 500,
        minLength: 1
    });
});

//往復であればフォームの値を2倍にする
$('#RequestDetailOnewayOrRound').change(function() {
    str = $('#RequestDetailOnewayOrRound option:selected').text();
    cost = $('#cost').val();
    
    if(str == '往復') {
        $('#cost').val(cost*2);
    } else {
        $('#cost').empty();
        $('#cost').val(cost/2);
    }
}).change();