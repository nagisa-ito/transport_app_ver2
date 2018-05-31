<?php

	App::uses('AppModel', 'Model');
	App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

	class User extends AppModel
	{
		//バリデーション
		public $validate = array(
			'username' => array('rule' => 'notEmpty'),
			'password' => array(
				array(
					'rule' => array('minLength', '7'),
					'message' => '7文字以上で登録してください'
				),
				array(
					'rule' => 'passwordConfirm',
					'message' => 'パスワードが一致していません'
				),
			),
			'password_confirm' => array('rule' => 'notEmpty', 'message' => 'パスワードが一致していません'),
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

		public function passwordConfirm($check)
        {
            //２つのパスワードフィールドが一致する事を確認する
            if($this->data['User']['password'] === $this->data['User']['password_confirm']) {
                return true;
            } else {
                return false;
            }
        }

    }
?>
