<?php

    class SectionsController extends AppController
    {
        public $helpers = array('Html', 'Form',);
        public $uses = array('RequestDetail', 'User', 'Department', 'Section');
        public $components = array('Paginator');
        public $paginate = array(
            'limit' => 5,
            'order' => array('Section.id' => 'ASC'),
        );
        
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
            
            $this->Paginator->settings = $this->paginate;
            $data = $this->Paginator->paginate('Section');
            $this->set(compact('data'));
        }
        
        public function admin_index()
        {
            $this->index();
            $this->render('index');
        }
    }