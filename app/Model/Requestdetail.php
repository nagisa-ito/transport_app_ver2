<?php  
	class RequestDetail extends AppModel
	{
		public $actsAs = array('Containable');
		var $hasOne = 'Transportation';
		
	}

?>
