<?php
    class RequestDetail extends AppModel
    {
        public $actsAs = array('Containable');

        var $hasOne = 'Transportation';

        public $validate = array(
            'date' => array(
                'rule' => 'date'
            ),
            'transportation_id' => array(
                'rule' => 'notEmpty'
            ),
            'from_station' => array(
                'rule' => 'alphaNumeric'
            ),
            'to_station' => array(
                'rule' => 'alphaNumeric'
            ),
            'cost' => array(
                'rule' => 'naturalNumber'
            ),
            'oneway_or_round' => array(
                'rule' => 'notEmpty'
            ),
        );

        public function getEachUserTotalCost($user_ids, $search_year_month)
        {
            //各月、各ユーザごとの合計費用を抽出するためのsql文
            $sql = "
            SELECT *
            FROM
            (
                SELECT users.id, users.yourname, users.department_id,user_request_lists.date,
                    user_request_lists.total_cost, user_request_lists.is_confirm, user_request_lists.is_no_request
                    FROM
                    (
                    SELECT all_month_requests.*, confirm_months.is_confirm, confirm_months.is_no_request
                    FROM
                    (
                        SELECT request_details.user_id, DATE_FORMAT(request_details.date, '%Y-%m') as date, sum(request_details.cost) as total_cost
                        FROM request_details
                        LEFT JOIN users ON request_details.user_id = users.id
                        AND request_details.is_delete != true
                        GROUP BY request_details.user_id, DATE_FORMAT(request_details.date, '%Y-%m')
                    )
                    AS all_month_requests
                    LEFT JOIN confirm_months
                    ON all_month_requests.user_id = confirm_months.user_id
                    AND all_month_requests.date = confirm_months.year_month
                    WHERE all_month_requests.date = '$search_year_month'
                    ) 
                    AS user_request_lists
                    RIGHT JOIN users
                    ON user_request_lists.user_id = users.id
                    WHERE users.role != 'admin'
                    AND users.id IN ($user_ids)
            )
            AS each_month_user_lists
            ";
            $result = $this->query($sql);
            $result = Hash::extract($result, '{n}.{s}');
            return $result;
        }

        //申請を月ごとに分けてカウントする
        public function getGroupByMonth($login_user_id)
        {
            $this->unbindModel(array('hasOne' => array('Transportation')));
            $sql = "
                SELECT *
                FROM(
                    SELECT request_details.user_id as user_id, DATE_FORMAT(request_details.date, '%Y-%m') as date, COUNT(*) as count, sum(request_details.cost) as total_cost,
                    confirm_months.is_confirm, confirm_months.is_no_request
                    FROM request_details
                    LEFT JOIN confirm_months
                    ON DATE_FORMAT(request_details.date, '%Y-%m') = confirm_months.year_month
                    WHERE request_details.is_delete != 1
                    AND request_details.user_id = 1
                    GROUP BY DATE_FORMAT(request_details.date, '%Y-%m')
                    ORDER BY DATE_FORMAT(request_details.date, '%Y-%m') DESC
                ) AS group_by_month
            ";
            $group_by_month = $this->query($sql);
            
            return $group_by_month;
        }

        public function outputCsvData($user_ids, $date)
        {
            $sql = "
            SELECT *
            FROM
            (
                SELECT each_month_user_lists.id, departments.department_name, each_month_user_lists.yourname,
                    each_month_user_lists.date, each_month_user_lists.count, each_month_user_lists.total_cost
                FROM
                (
                    SELECT users.id, users.yourname, users.department_id, user_request_lists.date,
                        user_request_lists.total_cost,  user_request_lists.count
                        FROM
                        (
                        SELECT all_month_requests.*
                        FROM
                        (
                            SELECT request_details.user_id, DATE_FORMAT(request_details.date, '%Y-%m') as date, sum(request_details.cost) as total_cost, COUNT(*) as count
                            FROM request_details
                            LEFT JOIN users ON request_details.user_id = users.id
                            AND request_details.is_delete != true
                            GROUP BY request_details.user_id, DATE_FORMAT(request_details.date, '%Y-%m')
                        )
                        AS all_month_requests
                        LEFT JOIN confirm_months
                        ON all_month_requests.user_id = confirm_months.user_id
                        AND all_month_requests.date = confirm_months.year_month
                        WHERE all_month_requests.date = '$date'
                        ) 
                        AS user_request_lists
                        RIGHT JOIN users
                        ON user_request_lists.user_id = users.id
                        WHERE users.role != 'admin'
                        AND users.id IN ($user_ids)
                )
                AS each_month_user_lists
                INNER JOIN departments
                ON departments.id = each_month_user_lists.department_id 
            ) AS output_csv_data
            ";
            $group_by_month = $this->query($sql);
            $group_by_month = Hash::extract($group_by_month, '{n}.{s}');
            
            return $group_by_month;
        }

    }

?>
