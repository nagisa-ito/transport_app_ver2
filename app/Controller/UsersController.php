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
						$this->redirect(['admin' => true, 'controller' => 'users', 'action' => 'user_lists']);
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
            $group_by_month = $this->RequestDetail->getGroupByMonth($login_user_id);

            debug($group_by_month);
            debug($login_user_id);

			$this->loadModel('Department');
			$departments = $this->Department->find('list', array('fields' => 'department_name'));

            $this->set(compact('departments', 'group_by_month', 'login_user_id'));
		}

		public function add()
		{
			$this->loadModel('Department');
			$this->set('department_id_list', $this->Department->find('list', array( 'fields' => 'department_name')));

			if($this->request->is('post')){
				if($this->User->save($this->request->data)){
					$this->Session->setFlash('Success!', 'default', ['class' => 'alert alert-warning']);
					$this->redirect(array('action' => 'login'));
				} else {
					$this->Session->setFlash('failed!', 'default', ['class' => 'alert alert-warning']);
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

			$this->loadModel('Department');
			$departments = $this->Department->find('list', array('fields' => 'department_name'));
			$this->set(compact('departments', 'users'));

		}

		public function admin_user_lists($department_id = null, $search_year_month = null)
		{
			$this->loadModel('Department');
			$department_id_list = $this->Department->find('list', array( 'fields' => 'department_name'));
			array_push($department_id_list, '全て');

			if($this->request->is('post')) {

				if($this->request->data['User']['department_id'] != 7){
					//部署の絞りこみがあった場合、その条件で抽出
					$users = $this->User->find('all', array('conditions' => array('department_id' => $this->request->data['User']['department_id'])));
				} else {
					//ユーザー全員を抽出
					$users = $this->User->find('all');
				}

				//年月の指定があった場合はその年月を格納し、なければ今月のデータを格納する
				$search_year_month = $this->request->data['User']['date'];
				$department_id = $this->request->data['User']['department_id'];

			} else {
				$users = $this->User->find('all');
				$search_year_month = date('Y-m');
				$department_id = 7;
			}
			$users = Hash::extract($users, '{n}.User');
			$this->set(compact('users', 'search_year_month'));

			//その部署の人たちのid一覧を抽出して結合する
			$user_id_list = implode(',', array_column($users, 'id'));

            //各月、各ユーザごとの合計費用を抽出するためのsql文
            $this->loadModel('RequestDetail');
            $sql = $this->RequestDetail->getEachUserTotalCost($user_id_list);
            $each_user_month_costs = $this->RequestDetail->query($sql);

            $this->set(compact('each_user_month_costs', 'department_id_list', 'department_id'));
		}

		public function admin_user_requests($user_id){
			$this->index($user_id);
			$this->render('index');
		}

	}

?>
