<?php 

	class UsersController extends AppController {
		public $helpers = array('Html', 'Form');

		public function index() 
		{
			//findにパラメータを与えることもできる。
			$params = array(
				'order' => 'modified desc',
				'limit' => 2
			);
			//$this->set('users', $this->User->find('all', $params));

			$this->set('users', $this->User->find('all'));
			$this->set('title_for_layout', 'ユーザー一覧');

			$this->set('select_users', $this->User->find('list', array(
				'fields' => array('id', 'yourname')
			)));
		}

		

	}

?>