$(function() {
    $('#search_travel_section').click(function(e) {
        $.post('/transportation_expenses_app_v2/request_details/search_travel_section/'
                + $('#company_autocomplete').val(), {},
            function(response) {
                if(!$.isEmptyObject(response)) {
                    $('#from_station_autocomplete').val(response.Section.from);
                    $('#to_station_autocomplete').val(response.Section.to);
                    $('#cost').val(response.Section.cost);
                }
            }, "json");
        return false;
    });
});

//企業名のサジェスト機能
$(function() {
    $("#company_autocomplete").autocomplete({
        source: Object.values(sections),
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
// $(function() {
//     $('#set_oneway_or_round_add').change(function() {
//         var str = $('#set_oneway_or_round_add option:selected').text();
//         var cost = $('#cost').val();

//         if(str == '往復') {
//             $('#cost').val(cost*2);
//         } else {
//             $('#cost').empty();
//             $('#cost').val(cost/2);
//         }
//     }).change();
// });

// $(function() {
//     var edit_cost = $('#cost').val();
//     $('#set_oneway_or_round_edit').change(function() {
//         str = $('#set_oneway_or_round_edit option:selected').text();
//         if(str == '往復') {
//             $('#cost').val(edit_cost*2);
//         } else {
//             $('#cost').empty();
//             $('#cost').val(edit_cost);
//         }
//     }).change();
// });
