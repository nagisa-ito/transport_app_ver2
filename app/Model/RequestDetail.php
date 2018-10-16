<?php
    App::uses('AppModel', 'Model');

    class RequestDetail extends AppModel
    {
        public $helpers = array('Html', 'Form', 'Csv');

        public $validate = array(
            'date' => array('rule' => 'notEmpty'),
            'from_station' => array('rule' => 'notEmpty'),
            'to_station' => array('rule' => 'notEmpty'),
            'cost' => array('rule' => 'naturalNumber'),
        );
        
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
                    user_id = $user_id
                AND
                    is_delete != 1
                AND
                    DATE_FORMAT(date, '%Y-%m') = '$year_month'
            ";

            $result = $this->query($sql);
            $result = Hash::extract($result, '{n}.{n}.{s}');
            return $result[0];
        }

    }
?>
