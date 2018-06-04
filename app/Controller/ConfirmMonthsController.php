<?php
    class ConfirmMonthsController extends AppController
    {
        public $helpers = array('Html', 'Form');

        public function beforeFilter()
        {
            parent::beforeFilter();
        }

        public function add ($year_month, $user_id, $is_no_request = 0)
        {
            if($this->request->is('ajax')) {
                //データセット
                $this->ConfirmMonth->set(array(
                    'year_month' => $year_month,
                    'user_id' => $user_id,
                    'is_confirm' => 1,
                    'is_no_request' => $is_no_request
                ));

                if($this->ConfirmMonth->save()) {
                    $this->autoRender = false;
                    $this->autoLayout = false;
                    $response = array('year_month' => $year_month, 'user_id' => $user_id);
                    $this->header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                }
            }
        }
    }

?>
