<?php
class RequestDetailsController extends AppController {
    //helperという機能を使うための合言葉のようなもの
    public $helpers = array('Html', 'Form');
    public $uses = array('RequestDetail', 'User', 'Department', 'ConfirmMonth', 'Section', 'Transportation');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $departments = $this->Department->find('list', array('fields' => 'department_name'));
        $this->set('departments', $departments);
    }

    public function index($view_user_id = null, $year_month = null, $is_admin = 0)
    {
        $user = $this->Auth->user();
        $this->setValueRequestDetails($user, $user['id'], $year_month);
    }

    /**
     * adminユーザーが一般ユーザーの申請を確認する
     * @param int $view_user_id 閲覧するユーザーのid
     * @param string $year_month 年月
     */
    public function admin_index($view_user_id, $year_month)
    {
        $user = $this->User->find('first', [
            'conditions' => ['User.id' => $view_user_id],
        ]);
        $user = Hash::get($user, 'User');

        $this->setValueRequestDetails($user, $view_user_id, $year_month);
        $this->render('index');
    }

    private function setValueRequestDetails($user, $view_user_id, $year_month)
    {
        $requests = $this->RequestDetail->getRequests($view_user_id, $year_month);
        $total_cost = $this->RequestDetail->getTotalCost($view_user_id, $year_month);
        $is_confirm = $this->ConfirmMonth->isConfirmMonth($year_month, $view_user_id);
        $column_names = Configure::read('column_names_request_details');

        $this->set(compact(
            'user',
            'year_month',
            'requests',
            'total_cost',
            'is_confirm',
            'column_names'
        ));
    }

    /**
     * 申請追加用メソッド。
     * @param $view_user_id adminユーザーでログインした時に、どのユーザーの申請を見ているかのid
     * @param $year_month
     */
    public function add($view_user_id = null, $year_month = null, $request_id = null)
    {
        $user = $this->Auth->user();

        // 訪問先補完用メソッド呼び出し
        $this->getAutocompleteContents();
        $transportations = $this->Transportation->find('list', array( 'fields' => 'transportation_name'));
        $this->set(compact('user', 'transportations'));

        if (empty($this->request->data)) {
            return;
        }

        if ($this->RequestDetail->save($this->request->data)) {
            // 登録した月の一覧にリダイレクトするため、yyyy-mmを取得
            $ymd = Hash::get($this->request->data, 'RequestDetail.date');
            preg_match('/^[0-9]{4}-[0-9]{2}/', $ymd, $year_month);

            $url = [
                'controller' => 'request_details',
                'action' => isset($this->request->data['add_repeat']) ? 'add' : 'index',
                $user['id'],
                $year_month[0],
            ];

            $this->Session->setFlash('保存されました。', 'default', ['class' => 'alert alert-success']);
            return $this->redirect($url);
        }

        $this->Session->setFlash('保存に失敗しました。', 'default', ['class' => 'alert alert-danger']);
    }

    public function admin_add($view_user_id = null, $year_month = null)
    {
        $user = $this->User->find('first', [
            'conditions' => ['User.id' => $view_user_id],
        ]);
        $user = Hash::get($user, 'User');

        // 訪問先補完用メソッド呼び出し
        $this->getAutocompleteContents();

        if (!empty($this->request->data)) {
            if ($this->RequestDetail->save($this->request->data)) {
                // 登録した月の一覧にリダイレクトするため、yyyy-mmを抽出する
                $ymd = Hash::get($this->request->data, 'RequestDetail.date');
                preg_match('/^[0-9]{4}-[0-9]{2}/', $ymd, $year_month);

                // リダイレクト先オプション
                $url = [
                    'controller' => 'request_details',
                    'action'     => isset($this->request->data['add_repeat']) ? 'add' : 'index',
                    $user['id'],
                    $year_month[0],
                ];
                
                $this->Session->setFlash('保存されました。', 'default', ['class' => 'alert alert-success']);
                return $this->redirect($url);
            } else {
                $this->Session->setFlash('保存に失敗しました。', 'default', ['class' => 'alert alert-danger']);
            }
        }

        $transportations = $this->Transportation->find('list', array( 'fields' => 'transportation_name'));
        $this->set(compact('user', 'transportations'));
        $this->render('add');
    }

    public function edit($user_id, $year_month, $request_id)
    {
        $user = $this->Auth->user();

        $this->RequestDetail->id = $request_id;

        if (!empty($this->request->data)) {
            if ($this->RequestDetail->save($this->request->data)) {
                $this->Session->setFlash('変更を保存しました。', 'default', ['class' => 'alert alert-success']);
                return $this->redirect(['action' =>  'index', $user['id'], $year_month]);
            } else {
                $this->Session->setFlash('変更に失敗しました。', 'default', ['class' => 'alert alert-warning']);
            }
        }

        $this->request->data = $this->RequestDetail->read();

         // 区間マスタ情報保管用
         $this->getAutocompleteContents();
         $transportations = $this->Transportation->find('list', array( 'fields' => 'transportation_name'));
         $this->set(compact('user', 'transportations'));
    }

    public function admin_edit($view_user_id, $year_month, $request_id)
    {
        $user = $this->User->find('first', [
            'conditions' => ['User.id' => $view_user_id],
        ]);
        $user = Hash::get($user, 'User');

        $this->RequestDetail->id = $request_id;

        if (!empty($this->request->data)) {
            if ($this->RequestDetail->save($this->request->data)) {
                $this->Session->setFlash('変更を保存しました。', 'default', ['class' => 'alert alert-success']);
                return $this->redirect(['action' =>  'index', $user['id'], $year_month]);
            } else {
                $this->Session->setFlash('変更に失敗しました。', 'default', ['class' => 'alert alert-warning']);
            }
        }

        $this->request->data = $this->RequestDetail->read();

        // 区間マスタ情報保管用
        $this->getAutocompleteContents();
        $transportations = $this->Transportation->find('list', array( 'fields' => 'transportation_name'));
        $this->set(compact('user', 'transportations'));
        $this->render('edit');
    }

    public function delete($delete_request_id = null, $login_user_id = null, $year_month = null)
    {
        $this->set('login_user', $this->Auth->user());
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        //ajax処理
        if ($this->request->is('ajax')) {
            $this->RequestDetail->id = $delete_request_id;
            $this->RequestDetail->saveField('is_delete', true);
            $this->autoRender = false;
            $this->autoLayout = false;
            $total_cost = $this->ReCalcTotalCost($login_user_id, $year_month);
            $response = array('request_id' => $delete_request_id, 'user_id' => $login_user_id, 'total_cost' => $total_cost);
            $this->header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }

        if ($this->RequestDetail->delete($delete_request_id)) {
            $this->Session->setFlash('Deleted!', 'default', ['class' => 'alert alert-warning']);
            if ($this->params['admin']) {
                $this->redirect(array(
                    'controller' => 'requestdetails',
                    'action' => "/index/{$login_user_id}/{$year_month}"
                ));
            } else {
                $this->redirect(array(
                    'controller' => 'requestdetails',
                    'action' => "/index/{$login_user_id}/{$year_month}"
                ));
            }
        }
    }

    public function admin_delete($delete_request_id = null, $login_user_id = null, $year_month = null)
    {
        $this->delete($delete_request_id, $login_user_id, $year_month);
    }

    //deleteした時のtotal_cost再計算用関数
    private function ReCalcTotalCost($login_user_id = null, $year_month = null)
    {
        //指定のuser_idとyear_monthのパラメータ指定
        $param = "user_id = {$login_user_id} and DATE_FORMAT(date, '%Y-%m') = '{$year_month}'";
        $this->RequestDetail->unbindModel(array('hasOne' => array('Transportation')));

        $total_cost = $this->RequestDetail->find('all', array(
            'fields' => array("DATE_FORMAT(date, '%Y-%m') as date", 'sum(cost)'),
            'conditions' => array(
                'user_id' => $login_user_id,
                "DATE_FORMAT(date, '%Y-%m')" => $year_month,
                'is_delete' => false
            ),
        ));
        $total_cost = Hash::extract($total_cost, '{n}.{n}');
        return number_format($total_cost[0]['sum(cost)']);
    }

    private function getAutocompleteContents()
    {
        $sections = $this->Section->find('list', array('fields' => 'name'));
        $sections = json_encode($sections);

        $from_stations = $this->Section->find('list', array('fields' => 'from'));
        $to_stations = $this->Section->find('list', array('fields' => 'to'));
        $stations = array_values(array_unique(array_merge_recursive($from_stations, $to_stations)));
        $stations = json_encode($stations);

        $this->set(compact('sections', 'stations'));
    }

    public function search_travel_section($name = null)
    {
        //ajax処理
        if($this->request->is('ajax')) {
            $travel_section = $this->Section->find('first', array(
                'conditions' => array('name' => $name),
            ));

            $this->autoRender = false;
            $this->autoLayout = false;
            $response = $travel_section;
            $this->header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }

    public function admin_requestdetail_csv_download($user_id, $year_month)
    {
        $this->layout = false;

        // SJIS変換用クロージャ
        $convert = function($convert_data) {
            mb_convert_variables('SJIS', 'UTF-8', $convert_data);
        };

        // ヘッダーの情報
        $cost_head = array(
            '通勤費', '定期代', '営業交通費', '合計',
        );
        $head = array(
            'id', '日付', '分類', '経路', '交通手段', '訪問先',
            '出発駅', '到着駅', '費用', '備考',
        );

        // 各申請を取得
        $requests = $this->RequestDetail->getRequests($user_id, $year_month);
        $requests = $this->sortRequestDataStructure($requests);

        // 申請の合計を取得
        $total_costs = $this->RequestDetail->getEachStatusTotalCost($user_id, $year_month);

        // ユーザー情報処理
        $username = $this->User->find('first', array(
            'conditions' => array('User.id' => $user_id),
            'fields' => array('User.yourname'),
        ));
        $username = Hash::get($username, 'User.yourname');

        // 文字コード変換
        $convert($cost_head);
        $convert($head);
        $convert($requests);
        $convert($total_costs);

        $filename = $year_month . '-' . $username;
        $this->set(compact('head', 'requests', 'filename'));
        $this->set(compact('total_costs', 'cost_head'));
    }

    /*
        * findしてきたデータの構造を、出力したいカラムの順番に並び替える。
        * @param $requests 取得したデータ
        * @return $data ソート後のデータ
        */
    protected function sortRequestDataStructure($requests)
    {
        $keys = array(
            'id', 'date', 'trans_type', 'oneway_or_round', 'transportation_name',
            'client', 'from_station', 'to_station', 'cost', 'overview',
        );
        $order = array_fill_keys($keys, null);

        $trans_category = Configure::read('trans_category');
        $oneway_or_round = Configure::read('oneway_or_round');
        foreach ($requests as $request) {
            // 数字で管理しているレコードを日本語表記にする
            $request['trans_type'] = $trans_category[$request['trans_type']];
            $request['oneway_or_round'] = $oneway_or_round[$request['oneway_or_round']];

            // 表示用配列に格納
            $data[] = array_replace($order, $request);
        }

        return $data;
    }
}
