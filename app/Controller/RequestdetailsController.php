<?php

	class RequestDetailsController extends AppController {
		//helperという機能を使うための合言葉のようなもの
		public $helpers = array('Html', 'Form');

		public function index() {
			$this->set('requestdetails', $this->RequestDetail->find('all'));
		}

	}
