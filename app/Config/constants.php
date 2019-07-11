<?php
$config = array();

// 経路分類
Configure::write('trans_category', [
    0 => '通勤費',
    1 => '定期代',
    2 => '営業交通費',
]);

// 片道か往復か
Configure::write('oneway_or_round', [
    0 => '片道',
    1 => '往復',
]);

Configure::write('column_names_request_details', [
    '申請id',
    '日付',
    '分類',
    '経路',
    '交通手段',
    '訪問先',
    '利用区間',
    '費用',
    '備考',
]);
