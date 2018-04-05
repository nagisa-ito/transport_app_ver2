<?php

	class RequestDetailsController extends AppController {
		//helperという機能を使うための合言葉のようなもの
		public $helpers = array('Html', 'Form');

		public function index() {
			$select_user_id = $_POST['data']['Requestdetail']['user_id'];
			$this->set('select_user_id', $select_user_id);

			//Transportation Model を使う
			$this->loadModel('Transportation');
			$params = 'RequestDetail.user_id =' . $select_user_id;
			$each_user_request_details = $this->Transportation->find('all', array(
																					'contain' => $params,
																					//'order' => array('RequestDetail.id' => 'asc')
																				));
			$this->set('each_user_request_details', $each_user_request_details);

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

			$test = $this->Requestdetail->find('all');
			$this->set('test', $test);
			
		}

		public function add()
		{
			$select_user_id = $_POST['data']['add_request']['select_user_id'];
			$this->set('select_user_id', $select_user_id);

			/*if($this->request->is('post')){
				if($this->Requestdetail->save($this->request->data)){
					$this->Session->setFlash('Success!');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('failed!');
				}*/
		}
		

	}
