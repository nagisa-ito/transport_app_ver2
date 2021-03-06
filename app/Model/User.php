<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel
{
    //バリデーション
    public $validate = array(
        'username' => array(
            'rule' => 'isUnique',
            'message' => 'すでに使用されているメールアドレスです。'
        ),
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
        'password_confirm' => array(
            'rule' => 'notEmpty',
            'message' => '入力は必須です。'
        ),
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
            $this->data[$this->alias]['password'] =
                $passwordHasher->Hash($this->data[$this->alias]['password']);
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
    
    /**
     * 指定された部署に所属するユーザー一覧を取得する。
     *
     * @param $department_id 部署id
     * case7 :  全部署指定、その時のみパラメータ指定なし
     * @return $user_ids ユーザーid一覧
     */
    public function getUserIdsByDepartmentId($department_id, $status)
    {
        if ($department_id != 0) {
            $param['conditions'] = array(
                'department_id' => $department_id,
                'status' => $status,
            );
        } else {
            $param['conditions'] = array('status' => $status);
        }

        $user_ids = $this->find('all', $param);
        $user_ids = Hash::extract($user_ids, '{n}.User.id');
        return $user_ids;
    }


    public function getMonthlyRequests($view_user_id)
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
                user_id = {$view_user_id}
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
        $query = "
            SELECT
                *
            FROM
            (
                SELECT
                    users.yourname,
                    IFNULL(requests.for_appointment, 0) as for_appointment,
                    IFNULL(requests.for_go_work, 0) as for_go_work,
                    IFNULL(requests.total_cost, 0) as total_cost
                FROM
                (
                    SELECT
                        user_id,
                        SUM(IF(request_details.trans_type = 2, request_details.cost, 0)) as for_appointment,
                        SUM(IF(request_details.trans_type != 2, request_details.cost, 0)) as for_go_work,
                        SUM(request_details.cost) as total_cost
                    FROM
                        request_details
                    WHERE
                        request_details.is_delete != 1
                    AND
                        DATE_FORMAT(request_details.date, '%Y-%m') = '{$date}'
                    GROUP BY
                        user_id
                ) AS requests
                RIGHT JOIN
                    users
                ON
                    users.id = requests.user_id
                WHERE
                    users.role != 'admin'
                AND
                    users.id IN ({$user_ids})
                ORDER BY
                    users.department_id
            ) AS data
        ";
        $result = $this->query($query);
        $result = Hash::extract($result, '{n}.{s}');
        return $result;
    }
}
