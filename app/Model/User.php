<?php
    App::uses('AppModel', 'Model');
    App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

    class User extends AppModel
    {
        //バリデーション
        public $validate = array(
            'username' => array('rule' => 'notEmpty'),
            'password' => array(
                array(
                    'rule' => array('minLength', '7'),
                    'message' => '7文字以上で登録してください'
                ),
                array(
                    'rule' => 'passwordConfirm',
                    'message' => 'パスワードが一致していません'
                ),
            ),
            'password_confirm' => array('rule' => 'notEmpty', 'message' => 'パスワードが一致していません'),
            'yourname'  => array('rule' => 'notEmpty'),
            'pass_from_station' => array('rule' => 'alphaNumeric'),
            'pass_to_station' => array('rule' => 'alphaNumeric')
        );

        //パスワードの暗号化
        public function beforeSave($options = array())
        {
            parent::beforeSave($options);

            if(isset($this->data[$this->alias]['password'])) {
                $passwordHasher = new SimplePasswordHasher();
                $this->data[$this->alias]['password'] = $passwordHasher->Hash($this->data[$this->alias]['password']);
            }

            return true;
        }

        public function passwordConfirm($check)
        {
            //２つのパスワードフィールドが一致する事を確認する
            if($this->data['User']['password'] === $this->data['User']['password_confirm']) {
                return true;
            } else {
                return false;
            }
        }
        
        public function getUserIdsByDepartmentId($department_id) {
            $param = array();
            $param['fields'] = array('id');
            if($department_id != 7) {
                $param['conditions'] = array('department_id' => $department_id);
            }
            $user_ids = $this->find('all', $param);
            $user_ids = Hash::extract($user_ids, '{n}.User.id');
            
            return $user_ids;
        }


        public function getMonthlyRequests($login_user_id)
        {
            $sql = "
                SELECT
                    user_id,
                    date,
                    IFNULL(MAX(req_count), 0) AS req_count,
                    IFNULL(MAX(total_cost), 0) AS total_cost,
                    is_confirm
                FROM
                (
                    (
                        SELECT
                            request_details.user_id,
                            DATE_FORMAT(request_details.date, '%Y-%m') as date,
                            COUNT(request_details.id) as req_count,
                            SUM(request_details.cost) as total_cost,
                            IFNULL(confirm_months.is_confirm, 0) as is_confirm
                        FROM
                            request_details
                        LEFT JOIN
                            confirm_months
                        ON
                            confirm_months.user_id = request_details.user_id
                        AND
                            confirm_months.year_month = DATE_FORMAT(request_details.date, '%Y-%m')
                        WHERE
                            request_details.is_delete != 1
                        GROUP BY
                            request_details.user_id,
                            DATE_FORMAT(request_details.date, '%Y-%m'),
                            is_confirm
                    )
                    UNION ALL
                    (
                        SELECT
                            user_id,
                            confirm_months.year_month,
                            NULL,
                            NULL,
                            is_confirm
                        FROM
                            confirm_months
                    )
                )
                AS
                    monthly_requests
                WHERE
                    user_id = $login_user_id
                GROUP BY
                    date,
                    is_confirm
            ";
            $this->log(['getmonthly_sql'=>$sql]);
            $sql = "SELECT * FROM ($sql) AS monthly_requests ORDER BY date DESC";
            $result = $this->query($sql);
            $result = Hash::extract($result, '{n}.{s}');
            return $result;
        }

        public function getEachUserMonthlyCost($user_ids, $search_year_month)
        {
            //各月、各ユーザごとの合計費用を抽出するためのsql文
            $sql = "
            SELECT
                all_users_monthly_requests.*,
                IFNULL(confirm_months.is_confirm, 0) as is_confirm
            FROM
            (
                SELECT
                    users.id,
                    users.yourname as username,
                    IFNULL(monthly_requests.date, '$search_year_month') as date,
                    IFNULL(monthly_requests.req_count, 0) as req_count,
                    IFNULL(monthly_requests.total_cost, 0) as total_cost
                FROM
                (
                    SELECT
                        users.id as user_id,
                        DATE_FORMAT(date, '%Y-%m') as date,
                        COUNT(request_details.id) as req_count,
                        SUM(cost) as total_cost
                    FROM
                        users
                    LEFT JOIN
                        request_details
                    ON
                        users.id = request_details.user_id
                    WHERE
                        request_details.is_delete != 1
                    AND
                        DATE_FORMAT(date, '%Y-%m') = '$search_year_month'
                    GROUP BY
                        users.id, DATE_FORMAT(date, '%Y-%m')
                    )
                AS
                    monthly_requests
                RIGHT JOIN
                    users
                ON
                    users.id = monthly_requests.user_id
                WHERE
                    users.role != 'admin'
                AND
                    users.id IN ($user_ids)
            )
            AS
                all_users_monthly_requests
            LEFT JOIN
                confirm_months
            ON
                all_users_monthly_requests.id = confirm_months.user_id
            AND
                all_users_monthly_requests.date = confirm_months.year_month
            ORDER BY id ASC
            ";
            $sql = "SELECT * FROM ($sql) AS all_users_monthly_requests";
            $result = $this->query($sql);
            $result = Hash::extract($result, '{n}.{s}');
            return $result;
        }

        /*
        * CSVに出力するデータを返す
        * @param array $user_ids
        * @param string $date
        * @return array
        */
        public function getOutputCsvData($user_ids, $date)
        {
            $sql = "
                SELECT
                    users.id,
                    departments.department_name,
                    users.yourname,
                    IFNULL(requests.req_count, 0) as req_count,
                    IFNULL(requests.regular, 0) as regular,
                    IFNULL(requests.not_regular, 0) as not_regular,
                    IFNULL(requests.total_cost, 0) as total_cost
                FROM
                    (
                        SELECT
                            users.id as user_id,
                            COUNT(request_details.id) as req_count,
                            SUM(IF(request_details.is_season_ticket = 1, request_details.cost, 0)) as regular,
                            SUM(IF(request_details.is_season_ticket = 0, request_details.cost, 0)) as not_regular,
                            SUM(request_details.cost) as total_cost
                        FROM
                            users
                        LEFT JOIN
                            request_details
                        ON
                            users.id = request_details.user_id
                        WHERE
                            request_details.is_delete != 1
                        AND
                            DATE_FORMAT(request_details.date, '%Y-%m') = '$date'
                        GROUP BY
                            DATE_FORMAT(request_details.date, '%Y-%m'), users.id
                    )
                AS
                    requests
                RIGHT JOIN
                    users
                ON
                    users.id = requests.user_id
                LEFT JOIN
                    departments
                ON
                    users.department_id = departments.id
                WHERE
                    users.role != 'admin'
                AND
                    users.id IN($user_ids)
            ";
            $sql = "SELECT * FROM ($sql) AS data";
            $result = $this->query($sql);
            $result = Hash::extract($result, '{n}.{s}');
            return $result;
        }
    }
?>
