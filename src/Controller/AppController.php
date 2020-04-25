<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        // $this->loadComponent('Session');
        $this->loadComponent('Paginator');
        $this->loadComponent('Auth', [
            'loginAction' => ['controller' => 'users', 'action' => 'login'],
            'logoutRedirect' => ['controller' => 'users', 'action' => 'register'],
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ]
                ]
            ],
            'storage' => 'Session'
        ]);
        // $this->loadComponent('Csrf');
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        // $this->loadComponent('Security');
    }

    public function beforeFilter(Event $event) {
        $this->Auth->allow(['register', 'activation', 'logout', 'testEmail']);
    }

    public function beforeRender(Event $event) {
        $auth = $this->request->getSession()->read('Auth.User');
        $myId = $this->request->getSession()->read('Auth.User.id');
        $systemLogo = "/img/microbloglogo.png";
        
        $this->set(compact('auth', 'myId', 'systemLogo'));
    }

    public function formErrors($data) {
        $errors = [];
        foreach($data->getErrors() as $key => $val) {
            $errors[$key] = array_values($val);
        }
        return $errors;
    }

    public function jsonResponse($data){
        $jsonData = json_encode($data);
        $response = $this->response->withType('json')->withStringBody($jsonData);
        return $response;
    }
}
