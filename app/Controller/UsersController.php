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
        $admin_url = [
            'admin' => true,
            'controller' => 'users',
            'action'     => 'user_lists',
        ];

        // 既にログインしている場合に自動的にページ遷移する
        if ($this->Auth->user()) {
            $url = $this->Auth->user('role') == 'admin' ? $admin_url : $this->Auth->redirect();
            return $this->redirect($url);
        }

        if (empty($this->request->data)) {
            return;
        }

        // ログイン成功
        if ($this->Auth->login()) {
            $url = $this->Auth->user('role') == 'admin' ? $admin_url : $this->Auth->redirect();
            return $this->redirect($url);
        }

        $this->Session->setFlash(
            'メールアドレスまたはパスワードが違います。',
            'default',
            ['class' => 'alert alert-danger']
        );
    }

    public function logout()
    {
        $this->Session->destroy();
        $this->redirect($this->Auth->logout());
    }

    /**
     * 申請一覧表示
     * @param int $view_user_id ビューで表示されるユーザーid
     * @param boolean $is_admin adminか否か
     */
    public function index()
    {
        $user = $this->Auth->user();

        // 月ごとの申請を抽出
        $group_by_month = $this->User->getMonthlyRequests($user['id']);
        $departments = $this->Department->find('list', array('fields' => 'department_name'));

        $this->set(compact('departments', 'group_by_month', 'user'));
    }

    /**
     * adminユーザーが一般ユーザーの申請を確認する
     * @param int $view_user_id 閲覧するユーザーのid
     */
    public function admin_user_requests($view_user_id)
    {
        // adminユーザーは全ユーザーの情報を確認できる
        $user = $this->User->find('first', [
            'conditions' => ['User.id' => $view_user_id],
        ]);
        $user = Hash::get($user, 'User');

        // 月ごとの申請を抽出
        $group_by_month = $this->User->getMonthlyRequests($user['id']);
        $departments = $this->Department->find('list', array('fields' => 'department_name'));

        $this->set(compact('departments', 'group_by_month', 'user'));
        $this->render('index');
    }

    public function add()
    {
        $this->set('department_id_list', $this->Department->find('list', array( 'fields' => 'department_name')));

        if ($this->request->is('post')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(
                    '登録に成功しました。',
                    'default',
                    array('class' => 'alert alert-success')
                );
                $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->setFlash(
                    '登録に失敗しました。',
                    'default',
                    array('class' => 'alert alert-danger')
                );
            }
        }
    }

    public function edit($user_id, $is_admin = 0)
    {
        $this->User->id = $user_id;
        if ($this->request->is('get')) {
            $this->request->data = $this->User->read();
        } else {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(
                    '編集に成功しました。',
                    'default',
                    array('class' => 'alert alert-success')
                );
            } else {
                $this->Session->setFlash(
                    '編集に失敗しました。',
                    'default',
                    array('class' => 'alert alert-danger')
                );
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

    public function admin_edit($user_id)
    {
        $this->User->id = $user_id;
        if ($this->request->is('get')) {
            $this->request->data = $this->User->read();
        } else {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(
                    '編集に成功しました。',
                    'default',
                    array('class' => 'alert alert-success')
                );
            } else {
                $this->Session->setFlash(
                    '編集に失敗しました。',
                    'default',
                    array('class' => 'alert alert-danger')
                );
            }
        }

        $this->set('department_id_list', $this->Department->find('list', array( 'fields' => 'department_name')));
        $this->set(compact('user'));
    }

    const DEPARTMENTS_ALL = 0;
    const ENROLLMENT = 1; // ステータス: 在籍
    const ENROLLMENT_NOT = 2; // ステータス: 退社

    public function admin_user_lists($department_id = null, $search_year_month = null)
    {
        // 部署選択項目設定
        $departments = $this->Department->find('list', array( 'fields' => 'department_name'));
        $departments[self::DEPARTMENTS_ALL] = '全て';
        ksort($departments);

        // 各検索条件をセット
        $search_year_month = isset($this->request->data['User']['date']) ?
            $this->request->data['User']['date'] : date('Y-m', strtotime("-1 month"));
        $department_id = isset($this->request->data['User']['department_id']) ?
            $this->request->data['User']['department_id'] : self::DEPARTMENTS_ALL;
        $status = isset($this->request->data['User']['status']) ?
            $this->request->data['User']['status'] : self::ENROLLMENT;

        // 対象のユーザーを検索
        $user_ids = $this->User->getUserIdsByDepartmentId($department_id, $status);

        //各月、各ユーザごとの合計費用を抽出する
        $each_user_monthly_costs = array();
        if (!empty($user_ids)) {
            $user_ids = implode(',', $user_ids);
            $each_user_monthly_costs = $this->User->getEachUserMonthlyCost($user_ids, $search_year_month);
        }

        $this->set(compact(
            'departments',
            'department_id',
            'search_year_month',
            'each_user_monthly_costs',
            'status'
        ));
    }

    /**
     * csvファイルに出力
     * @param $department_id (7: 全部署)
     * @param $date (Y-m)
     */
    public function admin_csv_download($department_id, $date, $status)
    {
        $this->layout = false;

        $department_name = '全部署';
        $top = array('項目名', '合計');

        // ファイル名、ヘッダ設定
        // 部署の指定があった場合は部署名を上書き
        if ($department_id != 7) {
            $this->Department->id = $department_id;
            $department_name = $this->Department->field('department_name');
        }
        $output_date = date('Y年m月', strtotime($date));
        $filename = "交通費_{$department_name}_{$output_date}";
        $header = array('項目名', '立替経費(営業交通費)', '非課税通勤費(定期代)', '合計');

        // 指定した部署のユーザーid一覧を取得
        $user_ids = $this->User->getUserIdsByDepartmentId($department_id, $status);
        $user_ids = implode(',', $user_ids);

        // 上記で取得したユーザーの申請一覧を抽出
        $data = $this->User->getOutputCsvData($user_ids, $date);

        // 全体の合計
        $total_for_appointment = array(
            '立替経費(営業交通費)',
            array_sum(array_column($data, 'for_appointment')),
        );
        $total_for_go_work = array(
            '非課税交通費(定期代)',
            array_sum(array_column($data, 'for_go_work')),
        );
        $sorted_data = call_user_func_array('array_map', array_merge(array(null), $data));

        // SJIS変換
        mb_convert_variables('SJIS', 'UTF-8', $sorted_data);
        mb_convert_variables('SJIS', 'UTF-8', $header);
        mb_convert_variables('SJIS', 'UTF-8', $total_for_appointment);
        mb_convert_variables('SJIS', 'UTF-8', $total_for_go_work);
        mb_convert_variables('SJIS', 'UTF-8', $top);

        $this->set(compact(
            'filename',
            'header',
            'sorted_data',
            'total_for_appointment',
            'total_for_go_work',
            'top'
        ));
    }

    public function reset_passwd($someone = null)
    {
        $mode = $_SERVER['APP_ENV'] == "development" ? 'smtp' : 'sakura';

        if (!empty($this->request->data)) {
            $someone = $this->existsUser($this->request->data['Token']['mail_address']);
            if ($someone) {
                //パスワード変更
                $password = $this->random();
                if (!$this->saveNewPassword($someone['User']['id'], $password)) {
                    $this->Session->setFlash(
                        'パスワードのリセットに失敗しました。',
                        'default',
                        array('class' => 'alert alert-danger')
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
                    $this->Session->setFlash(
                        'パスワード変更のメールを送信しました。メールを確認してください。',
                        'default',
                        array('class' => 'alert alert-success')
                    );
                    $this->redirect(array('action' => 'login'));
                } catch (Exception $e) {
                    $this->log($e->getMessage());
                    $this->Session->setFlash(
                        'メールの送信に失敗しました。',
                        'default',
                        array('class' => 'alert alert-danger')
                    );
                }
            } else {
                $this->Session->setFlash(
                    'メールアドレスが存在しません。',
                    'default',
                    array('class' => 'alert alert-danger')
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

