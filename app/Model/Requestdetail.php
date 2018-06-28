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

        public function getEachUserMonthlyCost($user_ids, $search_year_month)
        {
            //各月、各ユーザごとの合計費用を抽出するためのsql文
            $sql = "
                SELECT *
                FROM
                (
                    SELECT users.id as id, users.yourname, users.department_id, monthly_requests.date,
                        monthly_requests.count, monthly_requests.total_cost, monthly_requests.is_confirm,
                        monthly_requests.is_no_request
                    FROM users
                    LEFT OUTER JOIN
                    (
                        SELECT *
                        FROM
                        (
                            SELECT confirm_requests.user_id, DATE_FORMAT(confirm_requests.date, '%Y-%m') as date,
                                COUNT(*) as count, SUM(cost) as total_cost, confirm_requests.is_confirm,
                                confirm_requests.is_no_request
                            FROM
                            (
                                SELECT request_details.*, confirm_months.is_confirm, confirm_months.is_no_request
                                FROM
                                (
                                    SELECT request_details.id, request_details.user_id, request_details.date, request_details.cost
                                    FROM request_details
                                    WHERE is_delete != 1
                                    AND user_id IN($user_ids)
                                )AS request_details
                                LEFT OUTER JOIN confirm_months
                                ON request_details.user_id = confirm_months.user_id
                                AND DATE_FORMAT(request_details.date, '%Y-%m') = confirm_months.year_month
                                WHERE DATE_FORMAT(request_details.date, '%Y-%m') = '$search_year_month'
                            )AS confirm_requests
                            GROUP BY DATE_FORMAT(confirm_requests.date, '%Y-%m')
                        )AS monthly_request_costs
                    )AS monthly_requests
                    ON users.id = monthly_requests.user_id
                    WHERE users.id IN($user_ids)
                    AND users.role != 'admin'
                )AS monthly_request_users
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
                FROM
                (
                    SELECT monthly_costs.*, confirm_months.is_confirm, confirm_months.is_no_request
                    FROM
                    (
                        SELECT user_id, DATE_FORMAT(date, '%Y-%m') as date, COUNT(*) as count, SUM(cost) as total_cost
                        FROM request_details
                        WHERE is_delete != 1
                        AND user_id = $login_user_id
                        GROUP BY DATE_FORMAT(date, '%Y-%m')
                    )AS monthly_costs
                    LEFT OUTER JOIN confirm_months
                    ON monthly_costs.user_id = confirm_months.user_id
                    AND monthly_costs.date = confirm_months.year_month
                )AS monthly_request_status
                ORDER BY monthly_request_status.date DESC
            ";
            $group_by_month = $this->query($sql);
            
            $sql = "
                SELECT *
                FROM
                (
                    SELECT user_id, `year_month` as date, is_confirm, is_no_request
                    FROM confirm_months
                    WHERE user_id = $login_user_id
                    AND is_no_request = 1
                ) AS monthly_request_status
            ";
            $no_request_month = $this->query($sql);
            $result = $this->mergeArrayAndSortByDate($group_by_month, $no_request_month);

            return $result;
        }

        /*
        * CSVに出力するデータのうち、定期と営業交通費の合計を返す
        * @param array $user_ids
        * @param string $date
        * @return array
        */
        public function getCsvDownloadTotalCost($user_ids, $date)
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
                            WHERE request_details.is_delete != true
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
            ORDER BY output_csv_data.id ASC
            ";
            $group_by_month = $this->query($sql);
            $group_by_month = Hash::extract($group_by_month, '{n}.{s}');
            
            return $group_by_month;
        }

        /*
        * CSVに出力するデータのうち、定期または営業交通費を返す
        * @param array $user_ids
        * @param string $date
        * @param boolean $is_season_ticket
        * @return array
        */
        public function getCsvDownloadSeasonTicket($user_ids, $date, $is_season_ticket, $name)
        {
            $condition = "ORDER BY " . $name . ".id ASC";
            $sql = "
                SELECT *
                FROM
                (
                    SELECT each_month_user_lists.*, departments.department_name
                    FROM
                    (
                        SELECT users.id, users.yourname, users.department_id,
                            user_request_lists.total_cost,  user_request_lists.count
                        FROM
                        (
                            SELECT all_month_requests.*
                            FROM
                            (
                                SELECT request_details.user_id, DATE_FORMAT(request_details.date, '%Y-%m') as date, sum(request_details.cost) as total_cost, COUNT(*) as count
                                FROM request_details
                                LEFT JOIN users ON request_details.user_id = users.id
                                WHERE request_details.is_season_ticket = $is_season_ticket
                                AND request_details.is_delete != true
                                GROUP BY request_details.user_id, DATE_FORMAT(request_details.date, '%Y-%m')
                            ) AS all_month_requests
                            LEFT JOIN confirm_months
                            ON all_month_requests.user_id = confirm_months.user_id
                            AND all_month_requests.date = confirm_months.year_month
                            WHERE all_month_requests.date = '$date'
                        ) AS user_request_lists
                        RIGHT JOIN users
                        ON user_request_lists.user_id = users.id
                        WHERE users.role != 'admin'
                        AND users.id IN ($user_ids)
                    )   AS each_month_user_lists
                    INNER JOIN departments
                    ON departments.id = each_month_user_lists.department_id 
                ) AS $name
                ORDER BY $name.id ASC
            ";
            $data = $this->query($sql);
            return $data;
        }
        
        public function sortCsvOutputColumn($data)
        {
            $sort_standard = array(
                'id',
                'department_name',
                'yourname',
                'date',
                'count',
                'NOT_season_ticket',
                'season_ticket',
                'total_cost',
            );
            
            $sorted_data = array();
            $sort = array_fill_keys($sort_standard, null);
            foreach($data as $value) {
                array_push($sorted_data, array_replace($sort, $value));
            }
            
            return $sorted_data;
        }
        
        public function mergeArrayAndSortByDate($array_A, $array_B) {
            $result = array_merge($array_A, $array_B);
            $result = Hash::extract($result, '{n}.{s}');
            
            foreach($result as $key => $value) {
                $sort[$key] = $value['date'];
            }
            array_multisort($sort, SORT_DESC, $result);
            
            return $result;
        }
    }

?>
