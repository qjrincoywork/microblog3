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
        // $this->loadComponent('Security', ['blackHoleCallback' => 'blackHole']);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('main');
        
        if($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout(false);
        }
    }

    /* public function blackHole($error = '', SecurityException $exception = null)
    {
        if ($exception instanceof SecurityException && $exception->getType() === 'secure') {
            return $this->redirect('https://' . env('SERVER_NAME') . Router::url($this->request->getRequestTarget()));
        }

        throw $exception;
    } */
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
    
    public function testEmail() {
        try {
            $activationUrl = (isset($_SERVER['HTTPS']) === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            $subject = "Microblog Account Activation";
            $name = "Incoy, Quir John";
            $to = trim("quirjohnincoy.work@gmail.com");
            
            $message = "Dear <span style='color:#666666'>" . ucwords($name) . "</span>,<br/><br/>";
            $message .= "<b>Full Name:</b> " . ucwords($name) . "<br/>";
            $message .= "<b>Email Address:</b> " . $to . "<br/>";
            $message .= "<b>Activate your account by clicking </strong><a href='$activationUrl'>Activate Account now</a></strong></b><br/>";
            
            $email = new Email('gmail');
            $email->setFrom([$to => 'Microblog 3'])
                    ->setEmailFormat('html')
                    ->setTo($to)
                    ->setSubject($subject);
                    
            if($email->send($message)) {
                echo "Email sent";
            } else {
                echo "Email not sent";
            }
        } catch (\Throwable $th) {
            echo $th;
        }
    }
    
    public function send_email($userName, $fullName, $to, $token) {
        try {
            $activationUrl = (isset($_SERVER['HTTPS']) === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/users/activation/" . $token;
            $subject = "Microblog Account Activation";
            
            $message = "Dear <span style='color:#666666'>" . ucwords($fullName) . "</span>,<br/><br/>";
            $message .= "Your account has been created successfully.<br/>";
            $message .= "Please look at the details of your account below: <br/><br/>";
            $message .= "<b>Full Name:</b> " . ucwords($fullName) . "<br/>";
            $message .= "<b>Email Address:</b> " . trim($to) . "<br/>";
            $message .= "<b>Username:</b> " . $userName . "<br/>";
            $message .= "<b>Activate your account by clicking </strong><a href='$activationUrl'>Activate Account now</a></strong></b><br/>";
            $message .= "<br/>Thanks, <br/>YNS Team";

            $email = new Email('gmail');
            $email->setFrom([$to => 'Microblog 3'])
                    ->setEmailFormat('html')
                    ->setTo($to)
                    ->setSubject($subject)
                    ->send($message);
        } catch (\Throwable $th) {
            echo $th;
        }
    }

    public function logout()
    {
        $id = $this->request->getSession()->read('Auth.User.id');
        $user = $this->Users->get($id);
        $user->set(['is_online' => 0]);
        if($this->Users->save($user)) {
            return $this->redirect($this->Auth->logout());
        }
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
                    $fullName = $user['last_name'].', '.$user['first_name'].' '.$user['middle_name'];
                    $userName = $user['username'];
                    $to = $user['email'];
                    $this->send_email($userName, $fullName, $to, $mytoken);
                    $this->Flash->success(__('Email has been sent to activate your account.'));
                    return $this->redirect(['action' => 'register']);
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
        
        $user = $this->Users->find('all', [
                        'conditions' => ['Users.token' => $token]
        ])->first();
        
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
            $cond['Users.first_name LIKE'] = "%" . trim($user) . "%";
            $cond['Users.last_name LIKE'] = "%" . trim($user) . "%";
            $cond['Users.email LIKE'] = "%" . trim($user) . "%";
            $cond['Users.middle_name LIKE'] = "%" . trim($user) . "%";
            $cond['Users.suffix LIKE'] = "%" . trim($user) . "%";
            $cond["CONCAT(Users.first_name,' ',Users.last_name) LIKE"] = "%" . trim($user) . "%";
            $conditions['OR'] = $cond;
        }
        $this->paginate = [
            'conditions' => [
                ['Users.is_online !=' => 2],
                ['Users.deleted' => 0],
                [$conditions],
            ],
            'limit' => 5,
            'order' => [
                'Users.created' => 'desc',
            ],
        ];
        $data = $this->paginate($this->Users);
        var_dump($data);
        die('search');
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
                if(move_uploaded_file($postData['image']['tmp_name'],
                                        $path)) {
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
        $datum['success'] = false;
        $id = $this->request->getSession()->read('Auth.User.id');
        $user = $this->Users->get($followingId);
        if($user) {
            $exists = $this->Follows->find('all', [
                'conditions' => [
                        ['Follows.following_id' => $followingId,
                         'Follows.user_id' => $id]
                ]
            ])->first();
            
            if(!$exists) {
                $follow = $this->Follows->newEntity();
                $follow->user_id = $id;
                $follow->following_id = $followingId;
                $this->Follows->save($follow);
                $datum['success'] = true;
            } else {
                $status = $exists->deleted ? 0 : 1;
                $exists->deleted = $status;
                $this->Follows->save($exists);
                $datum['success'] = true;
            }
        }
        
        return $this->jsonResponse($datum);
    }
}
