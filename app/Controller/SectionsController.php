<?php

    class SectionsController extends AppController
    {
        public $helpers = array('Html', 'Form',);
        public $uses = array('RequestDetail', 'User', 'Department', 'Section');
        
        public function beforeFilter()
        {
            parent::beforeFilter();
                $this->Layout = '';
                $this->response->disableCache();
        }
        
        public function index()
        {
            $sections = $this->Section->find('all');
            $this->set(compact('sections'));
        }
        
        public function admin_index()
        {
            $this->index();
            $this->render('index');
        }
    }