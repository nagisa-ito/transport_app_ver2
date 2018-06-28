<?php

    class ConfirmMonth extends AppModel {
        public function isConfirmMonth($date, $user_id)
        {
            $is_confirm = $this->find('all', array(
                'conditions' => array('ConfirmMonth.user_id' => $user_id, 'ConfirmMonth.year_month' => $date),
            ));

            return empty($is_confirm) ? 0 : 1;
        }
    }

?>
