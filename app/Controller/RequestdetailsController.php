<?php

	class RequestDetailsController extends AppController {
		//helperという機能を使うための合言葉のようなもの
		public $helpers = array('Html', 'Form');
		public $oneway_or_round = array('往復' => '往復', '片道' => '片道');

		public function beforeFilter()
		{
			parent::beforeFilter();
		}

		public function index($select_user_id, $year_month)
		{
			//$select_user_id = $_POST['data']['Requestdetail']['user_id'];
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
			$params['conditions'] = array('RequestDetail.user_id' => $select_user_id, "DATE_FORMAT(RequestDetail.date, '%Y-%m')" => $year_month);
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
			$this->set('oneway_or_round', $this->oneway_or_round);
			$this->set('select_user_id', $select_user_id);

			$this->loadModel('Transportation');
			$this->set('transportation_id_list', $this->Transportation->find('list', array( 'fields' => 'transportation_name')));

			if($this->request->is('post')){
				if($this->RequestDetail->save($this->request->data)){
					$this->Session->setFlash('Success!');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('failed!');
				}
			}
		}

		public function delete($delete_request_id)
		{
			if ($this->request->is('get')) {
            	throw new MethodNotAllowedException();
        	}
        	if ($this->RequestDetail->delete($delete_request_id)) {
            	$this->Session->setFlash('Deleted!');
            	$this->redirect(array('action'=>'index'));
        	}
		}

		public function edit($edit_request_id, $select_user_id)
		{
			//色々と変数をセット
			$this->set('oneway_or_round', $this->oneway_or_round);
			$this->loadModel('Transportation');
			$this->set('transportation_id_list', $this->Transportation->find('list', array( 'fields' => 'transportation_name')));
			$this->set('select_user_id', $select_user_id);

			//Transportationモデルとの連携を一時的に解除
			$this->RequestDetail->unbindModel(array('hasOne' => array('Transportation')));

			$this->RequestDetail->id = $edit_request_id;
			if($this->request->is('get')) {
				$this->request->data = $this->RequestDetail->read();
			} else {
				if($this->RequestDetail->save($this->request->data)){
					$this->Session->setFlash('Success!');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('Failed!');
				}
			}
		}
	}
