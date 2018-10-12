<?php

    class ConfirmMonth extends AppModel {

        /*
         * @param $date    YYYY-mm
         * @param $user_id ユーザーid
         * そのユーザーがその月に申請した内容が、確定してあるか確認する。
         * @return boolean
         */
        public function isConfirmMonth($date, $user_id)
        {
            $is_confirm = $this->find('all', array(
                'conditions' => array('ConfirmMonth.user_id' => $user_id, 'ConfirmMonth.year_month' => $date),
            ));

            return empty($is_confirm) ? 0 : 1;
        }
    }

?>
