<?php
	class RequestDetail extends AppModel
	{
		public $actsAs = array('Containable');

		var $hasOne = 'Transportation';

		public $validate = array(
			'date' => array(
				'rule' => 'date'
			),
			'client' => array(
				'rule' => 'notEmpty'
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
				'rule' => 'numeric'
			),
			'oneway_or_round' => array(
				'rule' => 'notEmpty'
			),
		);



	}

?>
