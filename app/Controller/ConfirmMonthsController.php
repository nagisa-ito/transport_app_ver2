<?php
    class ConfirmMonthsController extends AppController
    {
        public $helpers = array('Html', 'Form');

        public function beforeFilter()
		{
			parent::beforeFilter();
        }

        public function add ($year_month, $user_id)
        {
            if($this->request->is('ajax')) {
                $this->ConfirmMonth->set(array(
                    'year_month' => $year_month,
                    'is_confirm' => true,
                    'user_id' => $user_id
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
