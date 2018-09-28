<?php
    $config = array();

    // 経路分類
    Configure::write('trans_category', array(
        0 => '通勤費',
        1 => '定期代',
        2 => '営業交通費',
    ));

    // 片道か往復か
    Configure::write('oneway_or_round', array(
        0 => '片道',
        1 => '往復',
    ));
