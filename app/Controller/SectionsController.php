<?php

    class SectionsController extends AppController
    {
        public $helpers = array('Html', 'Form',);
        public $uses = array('RequestDetail', 'User', 'Department', 'Section');
        public $components = array('Paginator');
        public $paginate = array(
            'limit' => 25,
            'order' => array('Section.id' => 'DESC'),
        );
        
        public function beforeFilter()
        {
            parent::beforeFilter();
                $this->Layout = '';
                $this->response->disableCache();
        }
        
        public function index()
        {
            $conditions = array();
            
            if($this->request->is('post')) {
                $this->add($this->request->data);
            } elseif($this->request->is('get')) {
                $search_words = mb_convert_kana($this->request->query['search_word'], "s");
                $search_words = explode(' ', $search_words);
                foreach($search_words as $word) {
                    $conditions[] = array('Section.name LIKE' => "%$word%");
                }
            }
            $this->Paginator->settings = $this->paginate;
            $sections = $this->Paginator->paginate('Section', $conditions);
            $this->set(compact('sections'));
        }
        
        public function admin_index()
        {
            $this->index();
            $this->render('index');
        }
        
        public function admin_add()
        {
            $this->add();
        }
        
        public function add()
        {
            if($this->Section->save($this->request->data)){
                $this->Session->setFlash('Success!', 'default', ['class' => 'alert alert-warning']);
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('failed!', 'default', ['class' => 'alert alert-warning']);
            }
        }
    }