<?php

	class RequestDetailsController extends AppController {
		//helperという機能を使うための合言葉のようなもの
		public $helpers = array('Html', 'Form');
		public $oneway_or_round = array('往復' => '往復', '片道' => '片道');

		public function beforeFilter()
		{
			parent::beforeFilter();
			$this->loadModel('Department');
			$departments = $this->Department->find('list', array('fields' => 'department_name'));
			$this->set('departments', $departments);
		}

		public function index($login_user_id = null, $year_month = null)
		{
			if(!isset($login_user_id)){
				//ログインしたユーザーを変数に格納
				$login_user_id = $this->Auth->user('id');
				$this->set('login_user',$this->Auth->user());
			} else {
				$this->loadModel('User');
				$login_user = $this->User->find('all', array('conditions' => array('id' => $login_user_id)));
				$login_user = Hash::extract($login_user, '{n}.{s}');
				$this->set('login_user', $login_user[0]);
			}

			$this->set('year_month', $year_month);
			$this->set('login_user_id', $login_user_id);

			/*** transportation.idでjoinする ******/
			$params = array();
			$params['recursive'] = -1;
			$params['joins'][] = array(
				'type' => 'LEFT',
				'table' => 'transportations',
				'alias' => 'Transportation',
				'conditions' => '`RequestDetail` . `transportation_id` = `Transportation` . `id`'
			);
			$params['fields'] = array('*');
			$params['order'] = array('RequestDetail.id' => 'ASC');
			$params['conditions'] = array('RequestDetail.user_id' => $login_user_id, "DATE_FORMAT(RequestDetail.date, '%Y-%m')" => $year_month);
			$each_user_request_details = $this->RequestDetail->find('all', $params);
			$this->set('each_user_request_details', $each_user_request_details);
			/************************************/

			$total_cost = array_column($each_user_request_details, "RequestDetail");
			$total_cost = array_sum(array_column($total_cost, 'cost'));

			$column_names = array(
				'申請id',
				'日付',
				'クライアント名',
				'交通手段',
				'区間',
				'費用',
				'片道or往復',
				'備考',
				'created',
				'modified',
			);
			$this->set('column_names', $column_names);
			$this->set('total_cost', $total_cost);
		}

		public function add($login_user_id = null, $year_month = null)
		{
			$this->set('login_user',$this->Auth->user());
			$this->set('oneway_or_round', $this->oneway_or_round);
			$this->set('login_user_id', $login_user_id);

			$this->loadModel('Transportation');
			$this->set('transportation_id_list', $this->Transportation->find('list', array( 'fields' => 'transportation_name')));

			if($this->request->is('post')){
				//added this line 認証
				$this->request->data['RequestDetail']['user_id'] = $login_user_id;
				if($this->RequestDetail->save($this->request->data)){
					debug($this->request->data);
						$ymd = Hash::get($this->request->data, 'RequestDetail.date');
						preg_match('/^[0-9]{4}-[0-9]{2}/', $ymd, $year_month);
						debug($year_month);
						$this->Session->setFlash('Success!', 'default', ['class' => 'alert alert-warning']);
						if($this->params['admin']){
							$this->redirect(array('controller' => 'requestdetails', 'action' => "index/$login_user_id/$year_month[0]"));
						} else {
							$this->redirect(array('controller' => 'requestdetails', 'action' => "index/$login_user_id/$year_month[0]"));
						}
				} else {
					$this->Session->setFlash('failed!', 'default', ['class' => 'alert alert-warning']);
				}
			}
		}

		public function delete($delete_request_id = null, $login_user_id = null, $year_month = null)
		{
			$this->log('delete開始');
			$this->set('login_user',$this->Auth->user());
			if ($this->request->is('get')) {
            	throw new MethodNotAllowedException();
        	}

			//ajax処理
			if($this->request->is('ajax')) {
				if($this->RequestDetail->delete($delete_request_id)) {
					$this->autoRender = false;
					$this->autoLayout = false;
					$response = array('id' => $delete_request_id);
					$this->header('Content-Type: application/json');
					echo json_encode($response);
					exit();
				}
			}

        	if ($this->RequestDetail->delete($delete_request_id)) {
            	$this->Session->setFlash('Deleted!', 'default', ['class' => 'alert alert-warning']);
				if($this->params['admin']){
					$this->redirect(array('controller' => 'requestdetails', 'action' => "/index/$login_user_id/$year_month"));
				} else {
					$this->redirect(array('controller' => 'requestdetails', 'action' => "/index/$login_user_id/$year_month"));
				}
        	}
		}

		public function edit($edit_request_id = null, $login_user_id = null, $year_month = null)
		{
			$this->set('login_user',$this->Auth->user());
			//色々と変数をセット
			$this->set('oneway_or_round', $this->oneway_or_round);
			$this->loadModel('Transportation');
			$this->set('transportation_id_list', $this->Transportation->find('list', array( 'fields' => 'transportation_name')));
			$this->set('login_user_id', $login_user_id);

			//Transportationモデルとの連携を一時的に解除
			$this->RequestDetail->unbindModel(array('hasOne' => array('Transportation')));

			$this->RequestDetail->id = $edit_request_id;
			if($this->request->is('get')) {
				$this->request->data = $this->RequestDetail->read();
			} else {
				if($this->RequestDetail->save($this->request->data)){
					$this->Session->setFlash('Success!', 'default', ['class' => 'alert alert-warning']);
					$this->redirect(array('action' => "index/$login_user_id/$year_month"));
				} else {
					$this->Session->setFlash('Failed!', 'default', ['class' => 'alert alert-warning']);
				}
			}
		}

		public function admin_index($login_user_id, $year_month)
		{
			$this->index($login_user_id, $year_month);
		}

		public function admin_edit($edit_request_id = null, $login_user_id = null, $year_month = null)
		{
			$this->edit($edit_request_id, $login_user_id, $year_month);
		}

		public function admin_add($login_user_id = null, $year_month = null)
		{
			$this->add($login_user_id, $year_month);
		}

		public function admin_delete($delete_request_id = null, $login_user_id = null, $year_month = null){
			$this->delete($delete_request_id, $login_user_id, $year_month);
		}

	}
