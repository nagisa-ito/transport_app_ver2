<?php

	class RequestDetailsController extends AppController {
		//helperという機能を使うための合言葉のようなもの
		public $helpers = array('Html', 'Form');

		public function index() {
			$select_user_id = $_POST['data']['Requestdetail']['user_id'];
			$this->set('select_user_id', $select_user_id);

			$params = array(
				'order' => 'id desc'
			);
						//↓変数
			$requestdetails = $this->RequestDetail->find('all');
			$this->set('requestdetails', $requestdetails);

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

			$search_str = '{n}.RequestDetail[user_id=' . $select_user_id . ']';
			$extracted_request_details = Hash::extract($requestdetails, $search_str);
			$this->set('extracted_request_details', $extracted_request_details);

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
