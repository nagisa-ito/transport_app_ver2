<?php
    App::uses('AppModel', 'Model', 'ConfirmMonth');

    class RequestDetail extends AppModel
    {
        public $helpers = array('Html', 'Form', 'Csv');

        public $validate = array(
            'date' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '日付を入力してください',
                ),
                'notConfirm' => array(
                    'rule' => 'validateConfirmMonth',
                    'message' => '確定後のため申請が追加できません。管理者に直接依頼してください。')
            ),
            'from_station' => array(
                'rule' => 'notEmpty',
                'message' => '出発駅を入力してください'
            ),
            'to_station' => array(
                'rule' => 'notEmpty',
                'message' => '到着駅を入力してください'
            ),
            'cost' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => '費用を入力してください'
                ),
                'naturalNumber' => array(
                    'rule' => 'naturalNumber',
                    'message' => '数字を入力してください'
                ),
            )
        );

        public function validateConfirmMonth($check)
        {
            $this->ConfirmMonth = ClassRegistry::init('ConfirmMonth');

            // adminユーザーの場合は確定してようが追加できる
            if (AuthComponent::user('role') == 'admin') {
                return true;
            }
            
            // それ以外は未確定か確認する
            $user_id = AuthComponent::user('id');
            $checkdate = date('Y-m', strtotime($check['date']));

            return !$this->ConfirmMonth->isConfirmMonth($checkdate, $user_id);
        }
        
        public function getRequests($user_id =  null, $year_month = null)
        {
            $sql = "
                SELECT *
                FROM
                (
                    SELECT 
                        request_details.id,
                        request_details.date,
                        request_details.client,
                        transportations.transportation_name,
                        request_details.from_station,
                        request_details.to_station,
                        request_details.cost,
                        request_details.oneway_or_round,
                        request_details.trans_type,
                        request_details.overview
                    FROM
                        request_details
                    INNER JOIN
                        transportations
                    ON
                        request_details.transportation_id = transportations.id
                    WHERE
                        request_details.is_delete != 1
                    AND
                        request_details.user_id = $user_id
                    AND
                        DATE_FORMAT(request_details.date, '%Y-%m') = '$year_month'
                )
                AS requests
            ";

            $result = $this->query($sql);
            $result = Hash::extract($result, '{n}.{s}');
            return $result;
        }

        public function getTotalCost($user_id, $year_month)
        {
            $sql = "
                SELECT 
                    SUM(cost) as total_cost
                FROM
                    request_details
                WHERE
                    user_id = {$user_id}
                AND
                    is_delete != 1
                AND
                    DATE_FORMAT(date, '%Y-%m') = '{$year_month}'
            ";

            $result = $this->query($sql);
            $result = Hash::extract($result, '{n}.{n}.{s}');
            return $result[0];
        }

        /*
         * 各ユーザーのcsv出力項目(集計結果)を返す。
         * @param $user_id ユーザーid
         * @param $year_month YYYY-mm
         * @return array
         */
        public function getEachStatusTotalCost($user_id, $year_month)
        {
            $trans_type = Configure::read('trans_category');
            $sql = "
                SELECT 
                    IFNULL(SUM(cost), 0) as total_cost
                FROM
                    request_details
                WHERE
                    user_id = {$user_id}
                AND
                    is_delete != 1
                AND
                    DATE_FORMAT(date, '%Y-%m') = '{$year_month}'
            ";

            $cost = function($state) use ($sql) {
                $sql .= "AND trans_type = {$state}";
                $result = $this->query($sql);
                $result = Hash::get($result, "0.0.total_cost");
                return $result;
            };

            foreach (array_keys($trans_type) as $state) {
                $each_costs[$state] = $cost($state);
            }
            $each_costs[] = array_sum($each_costs);

            return $each_costs;
        }

    }
?>
