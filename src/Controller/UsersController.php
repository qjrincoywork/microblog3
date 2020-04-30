<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\ORM\TableRegistry;
/**
 * Users Controller
 *
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Posts');
        $this->loadModel('Follows');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('main');
        
        if($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout(false);
        }
    }

    public function getPosts($conditions) {
        $this->paginate = [
            'Posts' => [
                'contain' => ['Users'],
                'conditions' => [
                    $conditions,
                ],
                'limit' => 4,
                'order' => [
                    'created' => 'desc',
                ],
            ]
        ];
        
        return $this->paginate($this->Posts);
    }
    
    public function home()
    {
        $id = $this->request->getSession()->read('Auth.User.id');
        $following = $this->Follows->find()
                                   ->select('Follows.following_id')
                                   ->where(['Follows.user_id' => $id, 'Follows.deleted' => 0])
                                   ->toArray();
        $ids = [];
        foreach($following as $key => $val) {
            $ids[] = $val['following_id'];
        }
        $ids[] = $id;
        
        $data = $this->getPosts(['Posts.deleted' => 0, 'Posts.user_id IN' => $ids]);
        $post = $this->Posts->newEntity();
        $this->set(compact('post', 'data'));
    }

    public function login()
    {
        if($this->request->getSession()->read('Auth.User.id')) {
            return $this->redirect(['action' => 'home']);
        }
        
        $this->viewBuilder()->setLayout('default');
        if($this->request->is('post')) {
            $user = $this->Auth->identify();
            
            if($user) {
                if($user['is_online'] == 2) {
                    $this->Flash->error(__('Please activate your account first.'));
                } else {
                    $userData = $this->Users->get($user['id']);
                    $userData->set(['is_online' => 1]);
                    
                    if($this->Users->save($userData)) {
                        $this->Auth->setUser($user);
                        return $this->redirect($this->Auth->redirectUrl("/users/home"));
                    }
                }
            } else {
                $this->Flash->error(__('Invalid username or password.'));
            }
        }
    }
    
    public function sendEmail($userName, $fullName, $to, $token) {
        try {
            $activationUrl = (isset($_SERVER['HTTPS']) === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/users/activation/" . $token;
            $subject = "Microblog Account Activation";

            $email = new Email('gmail');
            $email->setFrom([$to => 'Microblog 3'])
                    ->setEmailFormat('html')
                    ->setTo($to)
                    ->setSubject($subject)
                    ->setViewVars(['name' => $fullName, 
                                   'email' => $to,
                                   'username' => $userName, 
                                   'url' => $activationUrl])
                    ->viewBuilder()
                    ->setLayout('activation')
                    ->setTemplate('default');
            return $email->send();
        } catch (\Throwable $th) {
            echo $th;
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function register()
    {
        if($this->request->getSession()->read('Auth.User.id')) {
            return $this->redirect(['action' => 'home']);
        }
        
        $this->viewBuilder()->setLayout('default');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $datum['success'] = false;
            $postData = $this->request->getData();
            $mytoken = Security::hash(Security::randomBytes(32));
            $postData['token'] = $mytoken;
            $user = $this->Users->patchEntity($user, $postData, ['validate' => 'Register']);
            
            if(!$user->getErrors()) {
                if ($this->Users->save($user)) {
                    $fullName = $user->last_name.', '.$user->first_name.' '.$user->middle_name;
                    $userName = $user->username;
                    $to = $user->email;
                    if($this->sendEmail($userName, $fullName, $to, $mytoken)) {
                        $this->Flash->success(__('Email has been sent to activate your account.'));
                        return $this->redirect(['action' => 'register']);
                    }
                }
            }
        }
        $this->set('user', $user);
    }

    public function activation($token) {
        if(!$token) {
            throw new NotFoundException();
            $this->Flash->error(__('Invalid token'));
        }
        $user = $this->Users->find('all', ['conditions' => ['Users.token' => $token]])->first();
        
        if(!$user) {
            throw new NotFoundException();
            $this->Flash->error(__('Invalid token!'));
        }
        
        if(isset($user['is_online']) && $user['is_online'] == 2) {
            $user->set(['is_online' => 0]);
            $this->Users->save($user);
            $this->Flash->success(__('Account successfully verified!, You can now login'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        } else {
            $this->Flash->error(__('Account was already verified!'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }
    
    public function profile($id)
    {
        $conditions = [];
        if(!$id) {
            throw new NotFoundException();
        }
        $myId = $this->request->getSession()->read('Auth.User.id');
        
        if($myId != $id) {
            $conditions = ['Posts.user_id' => $id, 'Posts.deleted' => 0];
        } else {
            $conditions = ['Posts.user_id' => $id];
        }
        
        $profile = $this->Users->find('all', [
                                        'conditions' => ['Users.id' => $id, 'Users.is_online !=' => 2]
                                     ])->first();

        if(!$profile) {
            throw new NotFoundException();
        }
        
        $data = $this->getPosts($conditions);
        
        $this->set(compact('data', 'profile'));
    }

    public function search($user) {
        $conditions = [];
        if($user){
            $cond = [];
            $cond['first_name LIKE'] = "%" . trim($user) . "%";
            $cond['last_name LIKE'] = "%" . trim($user) . "%";
            $cond['email LIKE'] = "%" . trim($user) . "%";
            $cond['middle_name LIKE'] = "%" . trim($user) . "%";
            $cond['suffix LIKE'] = "%" . trim($user) . "%";
            $cond["CONCAT(first_name,' ',last_name) LIKE"] = "%" . trim($user) . "%";
            $conditions['OR'] = $cond;
        }
        $this->paginate = [
            'conditions' => [
                ['is_online !=' => 2],
                ['deleted' => 0],
                [$conditions],
            ],
            'limit' => 5,
            'order' => [
                'created' => 'desc',
            ],
        ];
        $data = $this->paginate($this->Users);
        
        $this->set(compact('data'));
    }
    
    public function edit() {
        $id = $this->request->getSession()->read('Auth.User.id');
        $user = $this->Users->get($id);
        if($this->request->is(['put', 'patch'])) {
            $datum['success'] = false;
            $postData = $this->request->getData();
            $user = $this->Users->patchEntity($user, $postData, ['validate' => 'Update']);
            
            $user->user_id = $id;
            if(!$user->getErrors()) {
                if ($this->Users->save($user)) {
                    $datum['success'] = true;
                }
            } else {
                $errors = $this->formErrors($user);
                $datum['errors'] = $errors;
            }
            
            return $this->jsonResponse($datum);
        }
        $this->set(compact('user'));
    }

    public function following() {
        $field = key($this->request->getQuery());
        $id = $this->request->getQuery()[$field];
        $data = [];
        $conditions = ['Follows.'.$field => $id,'Follows.deleted' => 0];
        
        if($field == 'user_id') {
            $column = 'following_id';
            $message = 'No user following';
        } else {
            $column = 'user_id';
            $message = "Don't have any follower";
        }
        
        $ids = $this->Follows->find('list', ['valueField' => $column])
                             ->where($conditions)->toArray();
                             
        if($ids) {
            $this->paginate = [
                'Users' => [
                    'conditions' => [
                        ['Users.is_online !=' => 2],
                        ['Users.deleted' => 0],
                        ['Users.id IN' => $ids],
                    ],
                    'limit' => 4,
                    'order' => [
                        'Users.created' => 'desc',
                    ],
                ]
            ];
            $data = $this->paginate();
        }
        
        $this->set(compact('message', 'data'));
    }

    public function editPicture() {
        $id = $this->request->getSession()->read('Auth.User.id');
        $user = $this->Users->get($id);
        if($this->request->is(['put', 'patch'])) {
            $datum['success'] = false;
            $postData = $this->request->getData();
            
            if($postData['image'] == 'undefined') {
                $postData['image'] = null;
                $user = $this->Users->patchEntity($user, $postData, ['validate' => 'Update']);
            } else {
                $user = $this->Users->patchEntity($user, $postData, ['validate' => 'Update']);
                $uploadFolder = "img/".$id;
                
                if(!file_exists($uploadFolder)) {
                    mkdir($uploadFolder);
                }
                
                $path = $uploadFolder."/".$postData['image']['name'];
                if(move_uploaded_file($postData['image']['tmp_name'], $path)) {
                    $user->image = $path;
                }
            }
            $user->user_id = $id;
            
            if(!$user->getErrors()) {
                if ($this->Users->save($user)) {
                    $datum['success'] = true;
                }
            } else {
                $errors = $this->formErrors($user);
                $datum['errors'] = $errors;
            }
            
            return $this->jsonResponse($datum);
        }
        $this->set(compact('user'));
    }

    public function changePassword() {
        $id = $this->request->getSession()->read('Auth.User.id');
        $user = $this->Users->get($id);
        
        if($this->request->is(['put', 'patch'])) {
            $datum['success'] = false;
            $postData = $this->request->getData();
            $user = $this->Users->patchEntity($user, $postData, ['validate' => 'Passwords']);
            
            if(!$user->getErrors()) {
                if ($this->Users->save($user)) {
                    $datum['success'] = true;
                }
            } else {
                $errors = $this->formErrors($user);
                $datum['errors'] = $errors;
            }
            
            return $this->jsonResponse($datum);
        }
        unset($user['password']);
        $this->set(compact('user'));
    }
    
    public function follow($followingId) {
        $id = $this->request->getSession()->read('Auth.User.id');
        $user = $this->Users->get($followingId);
        if($user) {
            $exists = $this->Follows->find('all', [
                                                'conditions' => [
                                                    ['Follows.following_id' => $followingId], 
                                                    ['Follows.user_id' => $id]
                                                ]
                                           ])->first();
                                           
            if(!$exists) {
                $follow = $this->Follows->newEntity();
                $follow->user_id = $id;
                $follow->following_id = $followingId;
                $result = $this->Follows->save($follow);
            }
        }
        $datum = ['success' => (isset($result)) ? true : false];
        return $this->jsonResponse($datum);
    }

    public function unfollow($followingId) {
        $id = $this->request->getSession()->read('Auth.User.id');
        $user = $this->Users->get($followingId);
        if($user) {
            $exists = $this->Follows->find('all', [
                                                'conditions' => [
                                                    ['Follows.following_id' => $followingId], 
                                                    ['Follows.user_id' => $id]
                                                ]
                                           ])->first();
                                           
            if($exists) {
                $status = $exists->deleted ? 0 : 1;
                $exists->deleted = $status;
                $result = $this->Follows->save($exists);
            }
        }
        $datum = ['success' => (isset($result)) ? true : false];
        return $this->jsonResponse($datum);
    }
}
