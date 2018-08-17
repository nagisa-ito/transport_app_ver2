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
        
        public function index($is_admin = 0)
        {
            $conditions = array();
            
            if($this->request->is('post')) {
                $this->add($this->request->data);
            } elseif($this->request->query('search_word')) {
                $search_words = mb_convert_kana($this->request->query['search_word'], "s");
                $search_words = explode(' ', $search_words);
                foreach($search_words as $word) {
                    $conditions[] = array('Section.name LIKE' => "%$word%");
                }
            }
            $this->Paginator->settings = $this->paginate;
            $sections = $this->Paginator->paginate('Section', $conditions);
            $this->set(compact('sections', 'is_admin'));
        }
        
        public function admin_index()
        {
            $this->index();
            $this->set($is_admin, 1);
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
        
        public function delete($id) {
            if ($this->request->is('get')) {
                throw new MethodNotAllowedException();
            }

            if($this->request->is('ajax')) {
                $this->Section->id = $id;
                $this->Section->delete($id);
                $this->autoRender = false;
                $this->autoLayout = false;
                $response = array('id' => $id);
                $this->header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        }
    }