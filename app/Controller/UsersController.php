<?php

	class UsersController extends AppController {

		public $helpers = array('Html', 'Form');

		public function beforeFilter()
		{
			parent::beforeFilter();
				$this->Layout = '';
				$this->response->disableCache();
		}

		public function login()
		{
			//フォームにデータがあった場合のみログイン処理を行い、指定のページへリダイレクトする。
			if($this->request->is('post')) {
				if($this->Auth->login()) {
					$role = $this->Auth->user('role');
					if(isset($role) && $role === 'admin'){
						$this->redirect(['admin' => true, 'controller' => 'users', 'action' => 'index']);
					} else {
						$this->redirect($this->Auth->redirect());
					}
				} else {
					$this->Session->setFlash('Invalid username or password, try again', 'default', ['class' => 'alert alert-warning']);
				}
			}
		}

		public function logout()
		{
			$this->redirect($this->Auth->logout());
		}

		public function index($login_user_id = null)
		{

			if(!isset($login_user_id)){
				//ログインしたユーザーを変数に格納
				$login_user_id = $this->Auth->user('id');
				$this->set('login_user',$this->Auth->user());
			} else {
				$login_user = $this->User->find('all', array('conditions' => array('id' => $login_user_id)));
				$login_user = Hash::extract($login_user, '{n}.{s}');
				$this->set('login_user', $login_user[0]);
			}

			$this->loadModel('RequestDetail');
			$this->RequestDetail->unbindModel(array('hasOne' => array('Transportation')));

			//conditionsのパラメータ指定
			$param = 'user_id =' . $login_user_id;
			//申請を月ごとに分けてカウントする
			$group_by_month = $this->RequestDetail->find('all', array(
				'fields' => array("DATE_FORMAT(date, '%Y-%m') as date", 'COUNT(*) as count', 'sum(cost)'),
				'conditions' => $param,
				'group' => array("DATE_FORMAT(date, '%Y%m')"),
				'order' => array('date' => 'DESC')
			));
			$group_by_month = Hash::extract($group_by_month, '{n}.{n}');

			$this->loadModel('Department');
			$departments = $this->Department->find('list', array('fields' => 'department_name'));
			$this->set('departments', $departments);
			$this->set('group_by_month', $group_by_month);
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

		public function admin_login()
		{
			$this->login();
		}

		public function admin_logout()
		{
			$this->logout();
		}

		public function admin_index()
		{
			$users = $this->User->find('all');
			$users = Hash::extract($users, '{n}.{s}');
			$this->set('users', $users);

			$this->loadModel('Department');
			$departments = $this->Department->find('list', array('fields' => 'department_name'));
			$this->set('departments', $departments);

		}

		public function admin_user_lists($department_id)
		{
			$this->set('department_id', $department_id);
			$this->set('users', $this->User->find('all', array('conditions' => array('department_id' => $department_id))));
		}

		public function admin_user_requests($user_id){
			$this->index($user_id);
		}

	}

?>
