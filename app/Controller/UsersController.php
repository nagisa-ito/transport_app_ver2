<?php

	class UsersController extends AppController {

		public $helpers = array('Html', 'Form');

		public function index()
		{
			//ログインしたユーザーを変数に格納
			$login_user_id = $this->Auth->user('id');

			$this->loadModel('RequestDetail');
			$this->RequestDetail->unbindModel(array('hasOne' => array('Transportation')));

			//conditionsのパラメータ指定
			$param = 'user_id =' . $login_user_id;
			//申請を月ごとに分けてカウントする
			$group_by_month = $this->RequestDetail->find('all', array(
				'fields' => array("DATE_FORMAT(date, '%Y-%m') as date", 'COUNT(*) as count'),
				'conditions' => $param,
				'group' => array("DATE_FORMAT(date, '%Y%m')"),
				'order' => array('date' => 'DESC')
			));
			$group_by_month = Hash::extract($group_by_month, '{n}.{n}');

			$this->set('group_by_month', $group_by_month);
			$this->set('login_user',$this->Auth->user());
		}

		public function beforeFilter()
		{
			parent::beforeFilter();
				$this->Auth->allow('login', 'add');
		}

		public function login()
		{
			if($this->request->is('post')) {
				if($this->Auth->login()) {
					$this->redirect($this->Auth->redirect());
				} else {
					$this->Session->setFlash(__('Invalid username or password, try again'));
				}
			}
		}

		public function logout()
		{
			$this->redirect($this->Auth->logout());
		}

		public function add()
		{
			$this->loadModel('Department');
			$this->set('department_id_list', $this->Department->find('list', array( 'fields' => 'department_name')));

			if($this->request->is('post')){
				if($this->User->save($this->request->data)){
					$this->Session->setFlash('Success!');
					$this->redirect(array('action' => 'login'));
				} else {
					$this->Session->setFlash('failed!');
				}
			}
		}

	}

?>
