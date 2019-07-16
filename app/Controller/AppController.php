<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array(),
            'loginRedirect' => array('controller' => 'users', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'flash' => array(
                'element' => 'alert',
                'key' => 'auth',
                'params' => array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger',
                )
            ),
            'authorize' => array('Controller') //認証
        ),
    );

    public function beforeFilter()
    {
        $this->Auth->allow('login', 'logout');
        if(isset($this->params['prefix']) && $this->params['prefix'] === 'admin') {
            $this->_setAdminParameter();
        }
        AuthComponent::$sessionKey = 'Auth.User';
        parent::beforeFilter();

        // ログインしているかの判定
        if (!in_array($this->action, ['login', 'logout']) && !$this->Auth->user()) {
            $this->Session->setFlash('ログアウトされました。', 'default', ['class' => 'alert alert-danger']);
            return $this->redirect([
                'controller' => 'users',
                'action'     => 'login',
                'admin'      => false,
            ]);
        }
    }

    private function _setAdminParameter()
    {
        $this->Auth->loginAction = [
            'controller' => 'users',
            'action' => 'login',
            'admin' => true
        ];

        $this->Auth->loginRedirect = [
            'controller' => 'users',
            'action' => 'index',
            'admin' => true
        ];

        $this->Auth->logoutRedirect = [
            'controller' => 'users',
            'action' => 'login',
            'admin' => true
        ];

        AuthComponent::$sessionKey = 'Auth.Admin';
    }

    //誰かが他の人の申請を編集したり削除したりするのを防ぐように、アプリケーションをセキュアにする。
    public function isAuthorized($user) {
    // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
        return true;
        }
        // デフォルトは拒否
        return false;
    }

}
