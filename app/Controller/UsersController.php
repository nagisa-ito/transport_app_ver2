<?php

	class UsersController extends AppController {

		public $helpers = array('Html', 'Form');

		public function beforeFilter()
		{
			parent::beforeFilter();
				$this->Auth->allow('login', 'add');
				$this->Layout = '';
				$this->response->disableCache();
		}

		public function index($login_user_id = null)
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

			$this->loadModel('Department');
			$departments = $this->Department->find('list', array('fields' => 'department_name'));

			$this->set('departments', $departments);
			$this->set('group_by_month', $group_by_month);
			$this->set('login_user',$this->Auth->user());
		}

		public function admin_index()
		{

		}

		public function login()
		{
			//フォームにデータがあった場合のみログイン処理を行い、指定のページへリダイレクトする。
			if($this->request->is('post')) {
					debug($this->request->data);
				if($this->Auth->login()) {
					if($this->Auth->user('is_admin')){
						$this->redirect(['admin' => true, 'controller' => 'users', 'action' => 'index']);
					} else {
						$this->redirect($this->Auth->redirect());
					}
				} else {
					$this->Session->setFlash(__('Invalid username or password, try again'));
				}
			}
		}

		public function admin_login()
		{
			$this->login();
		}

		public function logout()
		{
			$this->redirect($this->Auth->logout());
		}

		public function admin_logout()
		{
			$this->logout();
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
