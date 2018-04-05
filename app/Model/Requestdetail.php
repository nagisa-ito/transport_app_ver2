<?php 

	class RequestDetail extends AppModel {
		// var $primaryKey = 'transportation_id';

		// var $hasOne = array(
		// 	'Transportation' => array(
		// 		'className' => 'Transportation',
		// 		'foreignkey' => 'transportation_id'
		// 	)
		// );
		//$this->hasOne('Transportation')->setForeignKey('transportation_id');
		public $actsAs = array('Containable');
		//var $primaryKey = 'transportation_id';
		//var $primaryKey = 'transportation_id';
		// var $belongsTo = array(
		// 	'Transportation' => array(
		// 		'className' => 'Transportation',
		// 		'foreignKey' => 'transportation_id'
		// 	));

		//$this->belongsTo('Transportation')->setForeignKey('transportation_id');
		$this->belongsTo('Transportation');
	}

?>