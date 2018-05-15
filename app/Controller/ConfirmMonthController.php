<?php
    class ConfirmMonthController extends AppController
    {
        public function is_no_request($login_user_id = null, $no_request_month = null)
        {
            if($this->request->is('ajax')) {
                $response = $no_request_month;
                $this->header('Content-Type: application/json');
                echo json_encode();
            }
        }
    }

?>
