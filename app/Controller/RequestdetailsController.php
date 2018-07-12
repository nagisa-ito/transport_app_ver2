<?php

    class RequestDetailsController extends AppController {
        //helperという機能を使うための合言葉のようなもの
        public $helpers = array('Html', 'Form');
        public $oneway_or_round = array('往復' => '往復', '片道' => '片道');
        public $uses = array('RequestDetail', 'User', 'Department', 'TravelSection', 'ConfirmMonth', 'Section');
        
        public function beforeFilter()
        {
            parent::beforeFilter();
            $departments = $this->Department->find('list', array('fields' => 'department_name'));
            $this->set('departments', $departments);
        }

        public function index($login_user_id = null, $year_month = null, $is_admin = 0)
        {
            if(!isset($login_user_id)){
                //ログインしたユーザーを変数に格納
                $login_user_id = $this->Auth->user('id');
                $this->set('login_user',$this->Auth->user());
            } else {
                $this->loadModel('User');
                $login_user = $this->User->find('all', array('conditions' => array('id' => $login_user_id)));
                $login_user = Hash::extract($login_user, '{n}.{s}');
                $this->set('login_user', $login_user[0]);
            }

            $this->set('year_month', $year_month);
            $this->set('login_user_id', $login_user_id);

            /*** transportation.idでjoinする ******/
            $params = array();
            $params['recursive'] = -1;
            $params['joins'][] = array(
                'type' => 'LEFT',
                'table' => 'transportations',
                'alias' => 'Transportation',
                'conditions' => '`RequestDetail` . `transportation_id` = `Transportation` . `id`'
            );
            $params['fields'] = array('*');
            $params['order'] = array('RequestDetail.id' => 'ASC');
            $params['conditions'] = array(
                'RequestDetail.user_id' => $login_user_id,
                "DATE_FORMAT(RequestDetail.date, '%Y-%m')" => $year_month,
                //削除フラグが1になっていないデータを抽出
                'RequestDetail.is_delete' => false
            );
            $each_user_request_details = $this->RequestDetail->find('all', $params);
            $this->set('each_user_request_details', $each_user_request_details);
            /************************************/

            $total_cost = array_column($each_user_request_details, "RequestDetail");
            $total_cost = array_sum(array_column($total_cost, 'cost'));
            $is_confirm = $this->ConfirmMonth->isConfirmMonth($year_month, $login_user_id);

            $column_names = array(
                '申請id',
                '日付',
                '訪問先',
                '交通手段',
                '区間',
                '費用',
                '状態',
                '',
                '備考',
            );
            $this->set(compact('column_names', 'total_cost', 'is_admin', 'is_confirm'));
        }

        public function add($login_user_id = null, $year_month = null, $is_admin = 0)
        {
            $this->getAutocompleteContents();
            $this->set('login_user',$this->Auth->user());
            $this->set('oneway_or_round', $this->oneway_or_round);
            $this->set(compact('login_user_id', 'is_admin'));

            $this->loadModel('Transportation');
            $this->set('transportation_id_list', $this->Transportation->find('list', array( 'fields' => 'transportation_name')));

            if($this->request->is('post')){

                $this->request->data['RequestDetail']['user_id'] = $login_user_id;

                if($this->RequestDetail->save($this->request->data)){
                        $ymd = Hash::get($this->request->data, 'RequestDetail.date');
                        preg_match('/^[0-9]{4}-[0-9]{2}/', $ymd, $year_month);

                        $this->Session->setFlash('Success!', 'default', ['class' => 'alert alert-warning']);
                        if($this->params['admin']){
                            if(isset($this->request->data['add'])){
                                $this->redirect(array('controller' => 'requestdetails', 'action' => "index", $login_user_id, $year_month[0]));
                            } else {
                                $this->redirect(array('controller' => 'requestdetails', 'action' => "add", $login_user_id));
                            }
                        } else {
                            if(isset($this->request->data['add'])){
                                $this->redirect(array('controller' => 'requestdetails', 'action' => "index" ,$login_user_id, $year_month[0]));
                            } else {
                                $this->redirect(array('controller' => 'requestdetails', 'action' => "add", $login_user_id));
                            }
                        }

                } else {
                    $this->Session->setFlash('failed!', 'default', ['class' => 'alert alert-warning']);
                }
            }
        }

        public function delete($delete_request_id = null, $login_user_id = null, $year_month = null)
        {
            $this->set('login_user',$this->Auth->user());
            if ($this->request->is('get')) {
                throw new MethodNotAllowedException();
            }

            //ajax処理
            if($this->request->is('ajax')) {
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
                if($this->params['admin']){
                    $this->redirect(array('controller' => 'requestdetails', 'action' => "/index/$login_user_id/$year_month"));
                } else {
                    $this->redirect(array('controller' => 'requestdetails', 'action' => "/index/$login_user_id/$year_month"));
                }
            }
        }

        //deleteした時のtotal_cost再計算用関数
        private function ReCalcTotalCost($login_user_id = null, $year_month = null) {
            //指定のuser_idとyear_monthのパラメータ指定
            $param = 'user_id =' . $login_user_id . "and DATE_FORMAT(date, '%Y-%m') = " . $year_month;
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

        public function edit($edit_request_id = null, $login_user_id = null, $year_month = null, $is_admin = 0)
        {
            $this->getAutocompleteContents();
            $this->set('login_user',$this->Auth->user());
            //色々と変数をセット
            $this->set('oneway_or_round', $this->oneway_or_round);
            $this->loadModel('Transportation');
            $this->set('transportation_id_list', $this->Transportation->find('list', array( 'fields' => 'transportation_name')));
            $this->set(compact('login_user_id', 'is_admin'));

            //Transportationモデルとの連携を一時的に解除
            $this->RequestDetail->unbindModel(array('hasOne' => array('Transportation')));

            $this->RequestDetail->id = $edit_request_id;
            if($this->request->is('get')) {
                $this->request->data = $this->RequestDetail->read();
            } else {
                if($this->RequestDetail->save($this->request->data)){
                    $this->Session->setFlash('Success!', 'default', ['class' => 'alert alert-warning']);
                    $this->redirect(array('action' => "index/$login_user_id/$year_month"));
                } else {
                    $this->Session->setFlash('Failed!', 'default', ['class' => 'alert alert-warning']);
                }
            }
        }

        public function admin_index($login_user_id, $year_month)
        {
            $this->index($login_user_id, $year_month, 1);
            $this->render('index');
        }

        public function admin_edit($edit_request_id = null, $login_user_id = null, $year_month = null)
        {
            $this->edit($edit_request_id, $login_user_id, $year_month, 1);
            $this->render('edit');
        }

        public function admin_add($login_user_id = null, $year_month = null)
        {
            $this->add($login_user_id, $year_month, 1);
            $this->render('add');
        }

        public function admin_delete($delete_request_id = null, $login_user_id = null, $year_month = null){
            $this->delete($delete_request_id, $login_user_id, $year_month);
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

    }
