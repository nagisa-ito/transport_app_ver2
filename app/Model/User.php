<?php

	App::uses('AppModel', 'Model');
	App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

	class User extends AppModel
	{
		//バリデーション
		public $validate = array(
			'username' => array('rule' => 'notEmpty'),
			'password' => array('rule' => array('minLength', '7'), 'message' => '7文字以上で登録してください'),
			'yourname'  => array('rule' => 'notEmpty'),
			'pass_from_station' => array('rule' => 'alphaNumeric'),
			'pass_to_station' => array('rule' => 'alphaNumeric')
		);

		//パスワードの暗号化
		public function beforeSave($options = array())
		{
			parent::beforeSave($options);

			if(isset($this->data[$this->alias]['password'])) {
				$passwordHasher = new SimplePasswordHasher();
				$this->data[$this->alias]['password'] = $passwordHasher->Hash($this->data[$this->alias]['password']);
			}

			return true;
		}

	}

?>
