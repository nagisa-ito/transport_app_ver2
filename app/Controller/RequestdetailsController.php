<?php

	class RequestDetailsController extends AppController {
		//helperという機能を使うための合言葉のようなもの
		public $helpers = array('Html', 'Form');

		public function index() {
			$select_user_id = $_POST['data']['Requestdetail']['user_id'];
			$this->set('select_user_id', $select_user_id);

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
			$params['conditions'] = array('RequestDetail.user_id' => $select_user_id);
			$each_user_request_details = $this->RequestDetail->find('all', $params);
			$this->set('each_user_request_details', $each_user_request_details);
			/************************************/

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

		}

		public function add($select_user_id)
		{
			$this->set('select_user_id', $select_user_id);

			$this->loadModel('Transportation');
			$this->set('transportation_id_list', $this->Transportation->find('list', array( 'fields' => 'transportation_name')));

			if($this->request->is('post')){
				debug($this->request->data);
				if($this->RequestDetail->save($this->request->data)){
					$this->Session->setFlash('Success!');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('failed!');
				}
			}
		}
	}
