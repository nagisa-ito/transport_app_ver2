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

        public function getEachUserTotalCost($user_id_list)
        {
            //各月、各ユーザごとの合計費用を抽出するためのsql文
			$sql = "Select request_details.user_id, users.yourname, DATE_FORMAT(request_details.date, '%Y-%m') as date, sum(request_details.cost) as total_cost
			From request_details
			left join users on request_details.user_id = users.id
			where users.id in ($user_id_list)
			and request_details.is_delete != true
			Group by request_details.user_id, DATE_FORMAT(request_details.date, '%Y-%m')";

            return $sql;
        }

        public function getGroupByMonth($login_user_id)
        {
            //申請を月ごとに分けてカウントする
            $this->unbindModel(array('hasOne' => array('Transportation')));
            $sql = "select confirm_months.year_month as date, COUNT(*) as count, sum(request_details.cost) as total_cost,
                    confirm_months.is_confirm, confirm_months.user_id, confirm_months.is_no_request
                    from confirm_months
                    left join request_details
                    on confirm_months.year_month = DATE_FORMAT(request_details.date, '%Y-%m')
                    and confirm_months.user_id = request_details.user_id
                    where confirm_months.user_id = $login_user_id
                    group by confirm_months.year_month
                    order by confirm_months.year_month DESC";

            $group_by_month = $this->query($sql);
            return $group_by_month;
        }

	}

?>
