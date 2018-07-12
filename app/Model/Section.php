<?php
    App::uses('AppModel', 'Model');

    class Section extends AppModel
    {
        public $validate = array(
            'name' => array('rule' => 'notEmpty'),
            'from' => array('rule' => 'notEmpty'),
            'to'   => array('rule' => 'notEmpty'),
            'cost' => array('rule' => 'alphaNumeric'),
        );
    }


