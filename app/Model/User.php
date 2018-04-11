<?php

	App::uses('AppModel', 'Model');
	App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

	class User extends AppModel
	{

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
