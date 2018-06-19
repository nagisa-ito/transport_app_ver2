<?php

    class UsersController extends AppController
    {
        public $helpers = array('Html', 'Form', 'Csv');
        public $uses = array('RequestDetail', 'User', 'Department');
        public $each_user_monthly_costs = array();

        public function beforeFilter()
        {
            parent::beforeFilter();
                $this->Layout = '';
                $this->response->disableCache();
        }

        public function login()
        {
            //フォームにデータがあった場合のみログイン処理を行い、指定のページへリダイレクトする。
            if($this->request->is('post')) {
                if($this->Auth->login()) {
                    $role = $this->Auth->user('role');
                    if(isset($role) && $role === 'admin'){
                        $this->redirect(array(
                            'admin' => true,
                            'controller' => 'users',
                            'action' => 'user_lists',
                        ));
                    } else {
                        $this->redirect($this->Auth->redirect());
                    }
                } else {
                    $this->Session->setFlash('Invalid username or password, try again', 'default', ['class' => 'alert alert-warning']);
                }
            }
        }

        public function logout()
        {
            $this->redirect($this->Auth->logout());
        }

        public function index($login_user_id = null)
        {
            if(!isset($login_user_id)){
                //ログインしたユーザーを変数に格納
                $login_user_id = $this->Auth->user('id');
                $this->set('login_user',$this->Auth->user());
            } else {
                $login_user = $this->User->find('all', array('conditions' => array('id' => $login_user_id)));
                $login_user = Hash::extract($login_user, '{n}.{s}');
                $this->set('login_user', $login_user[0]);
            }

            $group_by_month = $this->RequestDetail->getGroupByMonth($login_user_id);
            $departments = $this->Department->find('list', array('fields' => 'department_name'));

            $this->set(compact('departments', 'group_by_month', 'login_user_id'));
        }

        public function add()
        {
            $this->set('department_id_list', $this->Department->find('list', array( 'fields' => 'department_name')));

            if($this->request->is('post')){
                if($this->User->save($this->request->data)){
                    $this->Session->setFlash('Success!', 'default', ['class' => 'alert alert-warning']);
                    $this->redirect(array('action' => 'login'));
                } else {
                    $this->Session->setFlash('failed!', 'default', ['class' => 'alert alert-warning']);
                }
            }
        }

        public function admin_login()
        {
            $this->login();
        }

        public function admin_logout()
        {
            $this->logout();
        }

        public function admin_index()
        {
            $users = $this->User->find('all');
            $users = Hash::extract($users, '{n}.{s}');

            $departments = $this->Department->find('list', array('fields' => 'department_name'));
            $this->set(compact('departments', 'users'));

        }

        public function admin_user_lists($department_id = null, $search_year_month = null)
        {
            $department_id = 7;
            $search_year_month = date('Y-m');
            $department_id_list = $this->Department->find('list', array( 'fields' => 'department_name'));
            array_push($department_id_list, '全て');

            if($this->request->is('post')) {
                $department_id = $this->request->data['User']['department_id'];
                $search_year_month = $this->request->data['User']['date'];
            }

            $users = $this->User->getUserIdsByDepartmentId($department_id);
            $user_ids = implode(',', $users);

            //各月、各ユーザごとの合計費用を抽出する
            $this->each_user_monthly_costs = $this->RequestDetail->getEachUserMonthlyCost($user_ids, $search_year_month);
            $each_user_monthly_costs = $this->each_user_monthly_costs;

            $this->set(compact('department_id_list', 'department_id','search_year_month'));
            $this->set(compact('each_user_monthly_costs'));
        }

        public function admin_user_requests($user_id){
            $this->index($user_id);
            $this->render('index');
        }

        public function admin_csv_download($department_id, $date) {
            $department = '_';
            $this->layout = false;

            $user_ids = $this->User->getUserIdsByDepartmentId($department_id);
            $user_ids = implode(',', $user_ids);

            $csv_output_data = $this->RequestDetail->getCsvDownloadTotalCost($user_ids, $date);
            $season_ticket = $this->RequestDetail->getCsvDownloadSeasonTicket($user_ids, $date, 1, 'season_ticket');
            $season_ticket = Hash::extract($season_ticket, '{n}.season_ticket.total_cost');
            $NOT_season_ticket = $this->RequestDetail->getCsvDownloadSeasonTicket($user_ids, $date, 0, 'NOT_season_ticket');
            $NOT_season_ticket = Hash::extract($NOT_season_ticket, '{n}.NOT_season_ticket.total_cost');

            foreach($season_ticket as $key => $value) {
                $csv_output_data[$key]['season_ticket'] = $value;
                $csv_output_data[$key]['NOT_season_ticket'] = $NOT_season_ticket[$key];
            }

            $data = $this->RequestDetail->sortCsvOutputData($csv_output_data);

            if($department_id != 7) {
                $this->Department->id = $department_id;
                $department .= $this->Department->field('department_name');
            }

            $print_date = str_replace('-', '_', $date);
            $filename = "交通費" . $department . "_" . $print_date;
            $header = array('社員id', '部署', '名前', '件数', '営業交通費', '定期代', '合計金額');
            $this->set(compact('filename', 'header', 'data'));
        }

    }

?>
