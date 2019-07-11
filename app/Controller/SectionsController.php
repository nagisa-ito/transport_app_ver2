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

        // 表示する区間マスタ一覧のユーザーidを指定
        $this->paginate = array('conditions' => array('Section.user_id' => $this->Auth->user('id')));

        // 保存
        if ($this->request->is('post')) {
            $this->request->data['Section']['user_id'] = $this->Auth->user('id');
            $this->add($this->request->data);
            return;
        }

        // 検索
        if (!empty($this->request->query('search_word'))) {
            $search_words = mb_convert_kana($this->request->query['search_word'], "s");
            $search_words = explode(' ', $search_words);
            foreach ($search_words as $word) {
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
        if ($this->Section->save($this->request->data)) {
            $this->Session->setFlash('保存に成功しました', 'default', ['class' => 'alert alert-success']);
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('保存に失敗しました', 'default', ['class' => 'alert alert-danger']);
        }
    }
    
    public function delete($id)
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->request->is('ajax')) {
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
