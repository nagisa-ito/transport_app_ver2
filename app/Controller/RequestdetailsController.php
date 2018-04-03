<?php

	class RequestDetailsController extends AppController {
		//helperという機能を使うための合言葉のようなもの
		public $helpers = array('Html', 'Form');

		public function index() {
			$params = array(
				'order' => 'id desc'
			);
						//↓変数
			$this->set('requestdetails', $this->RequestDetail->find('all'));

			$column_names = array(
				'申請id',
				'申請者',
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

	}
