<?php
    App::uses('CakeEmail', 'Network/Email');

    class UsersController extends AppController
    {
        public $helpers = array('Html', 'Form', 'Csv');
        public $uses = array('User', 'Department');

        public function beforeFilter()
        {
            parent::beforeFilter();
                $this->Layout = '';
                $this->response->disableCache();
        }

        public function login()
        {
            $admin_param = array(
                'admin' => true,
                'controller' => 'users',
                'action' => 'user_lists',
            );
            
            // 既にログインしている場合
            if($this->Auth->loggedIn()) {
                if($this->Auth->user('role') == 'admin') {
                    $this->redirect($admin_param);
                } else {
                    $this->redirect($this->Auth->redirect());
                }
            }
            
            // フォームからデータが送られてきた場合
            if(!empty($this->request->data)) {
                if($this->Auth->login()) {
                    if($this->Auth->user('role') == 'admin') {
                        $this->redirect($admin_param);
                    } else {
                        $this->redirect($this->Auth->redirect());
                    }
                } else {
                    $this->Session->setFlash('メールアドレスまたはパスワードが違います。',
                                                'default',
                                                ['class' => 'alert alert-warning']
                    );
                }
            }
        }

        public function logout()
        {
            $this->redirect($this->Auth->logout());
        }

        public function index($login_user_id = null, $is_admin = 0)
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
            $group_by_month = $this->User->getMonthlyRequests($login_user_id);
            $departments = $this->Department->find('list', array('fields' => 'department_name'));

            $this->set(compact('departments', 'group_by_month', 'login_user_id', 'is_admin'));
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

        public function edit($user_id, $is_admin = 0)
        {
            $this->User->id = $user_id;
            if($this->request->is('get')) {
                $this->request->data = $this->User->read();
            } else {
                if($this->User->save($this->request->data)) {
                    $this->Session->setFlash('Success!', 'default', ['class' => 'alert alert-success']);
                } else {
                    $this->Session->setFlash('Failed!', 'default', ['class' => 'alert alert-danger']);
                }
            }

            $this->set('department_id_list', $this->Department->find('list', array( 'fields' => 'department_name')));
            $this->set(compact('is_admin', 'user'));
        }
        
        public function admin_login()
        {
            $this->login();
            $this->render('login');
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
    
        public function admin_edit($user_id)
        {
            $this->edit($user_id, 1);
            $this->render('edit');
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
            $each_user_monthly_costs = $this->User->getEachUserMonthlyCost($user_ids, $search_year_month);
            
            $this->set(compact('department_id_list', 'department_id','search_year_month'));
            $this->set(compact('each_user_monthly_costs'));
        }

        public function admin_user_requests($user_id)
        {
            $this->index($user_id, 1);
            $this->render('index');
        }

        public function admin_csv_download($department_id, $date)
        {
            $department = '_';
            $this->layout = false;

            $user_ids = $this->User->getUserIdsByDepartmentId($department_id);
            $user_ids = implode(',', $user_ids);

            if($department_id != 7) {
                $this->Department->id = $department_id;
                $department .= $this->Department->field('department_name');
            }

            $data = $this->User->getOutputCsvData($user_ids, $date);
            mb_convert_variables('SJIS', 'UTF-8', $data);

            $print_date = str_replace('-', '_', $date);
            $filename = "交通費" . $department . "_" . $print_date;
            $header = array('社員id', '部署', '名前', '件数', '通勤費', '定期代', '営業交通費', '合計金額');
            mb_convert_variables('SJIS', 'UTF-8', $header);
            $this->set(compact('filename', 'header', 'data'));
        }

        public function reset_passwd($someone = null)
        {
            $mode = $_SERVER['APP_ENV'] == "development" ? 'smtp' : 'sakura';

            if(!empty($this->request->data)) {
                $someone = $this->existsUser($this->request->data['Token']['mail_address']);
                if($someone) {
                    //パスワード変更
                    $password = $this->random();
                    if(!$this->saveNewPassword($someone['User']['id'], $password)) {
                        $this->Session->setFlash('パスワードのリセットに失敗しました。',
                                                'default',
                                                ['class' => 'alert alert-danger']
                        );
                    }
                    // メール送信
                    try {
                        $mode = $_SERVER['APP_ENV'] == "development" ? 'smtp' : 'sakura';
                        $email = new CakeEmail($mode);
                        $email->to($someone['User']['username'])
                              ->emailFormat('html')
                              ->template('mail_template')
                              ->viewVars(array(
                                'name' => $someone['User']['yourname'],
                                'password' => $password,
                              ))
                              ->subject('[交通費管理アプリ] パスワードをリセットしました')
                              ->send();
                        $this->Session->setFlash('パスワード変更のメールを送信しました。メールを確認してください。',
                                                'default',
                                                ['class' => 'alert alert-success']
                        );
                        $this->redirect(array('action' => 'login'));
                    } catch(Exception $e) {
                        $this->log($e->getMessage());
                        $this->Session->setFlash('メールの送信に失敗しました。',
                                                'default',
                                                ['class' => 'alert alert-danger']
                        );
                    }
                } else {
                    $this->Session->setFlash('メールアドレスが存在しません。',
                                                'default',
                                                ['class' => 'alert alert-danger']
                    );
                }
            }
        }

        private function saveNewPassword($id, $password)
        {
            $this->User->id = $id;
            $this->request->data = $this->User->read();
            $this->request->data['User']['password'] = $password;
            $this->request->data['User']['password_confirm'] = $password;
            
            return $this->User->save($this->request->data) ? true : false;
        }
        
        private function existsUser($address)
        {
            $someone = $this->User->find('first', array(
                'conditions' => array('User.username' => $address)
            ));
            return empty($someone) ? false : $someone;
        }
        
        private function random($length = 10)
        {
            return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
        }
    }

?>
