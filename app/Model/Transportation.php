<?php  
	class Transportation extends AppModel
	{
		public $actsAs = array('Containable');
		//var $primaryKey = 'transportation_id';
		var $hasMany = 'RequestDetail';

	}

?>
