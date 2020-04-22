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
    /* public $paginate = [
        'order' => [
            'Posts.created DESC'
        ],
        'limit' => 4
    ]; */

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Posts');
        // $this->loadComponent('Security', ['blackHoleCallback' => 'blackHole']);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // $this->Auth->allow(['register', 'activation', 'logout', 'testEmail']);
        $this->viewBuilder()->setLayout('main');
        // $this->Security->requireSecure();
    }

    /* public function blackHole($error = '', SecurityException $exception = null)
    {
        if ($exception instanceof SecurityException && $exception->getType() === 'secure') {
            return $this->redirect('https://' . env('SERVER_NAME') . Router::url($this->request->getRequestTarget()));
        }

        throw $exception;
    } */
    public function getPosts($conditions = null) {
        /* $this->Users->virtualFields['image'] = "CASE 
                                                        WHEN User.image IS NULL
                                                            THEN
                                                                CASE
                                                                WHEN User.gender = 0
                                                                    THEN '/img/default_avatar_f.svg'
                                                                    ELSE '/img/default_avatar_m.svg'
                                                                END
                                                        ELSE concat('/',User.image)
                                                    END";
        $this->Posts->virtualFields['post_ago'] = "CASE
                                                    WHEN Post.created between date_sub(now(), INTERVAL 120 second) and now() 
                                                        THEN 'Just now'
                                                    WHEN Post.created between date_sub(now(), INTERVAL 60 minute) and now() 
                                                        THEN concat(minute(TIMEDIFF(now(), Post.created)), ' minutes ago')
                                                    WHEN datediff(now(), Post.created) = 1
                                                        THEN 'Yesterday'
                                                    WHEN Post.created between date_sub(now(), INTERVAL 24 hour) and now() 
                                                        THEN concat(hour(TIMEDIFF(NOW(), Post.created)), ' hours ago')
                                                    ELSE concat(datediff(now(), Post.created),' days ago')
                                                END"; */
        // pr($this->Users->findById(1)->first());
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
        $data = $this->paginate($this->Posts);
        return $data;
    }
    
    public function home()
    {
        $id = $this->request->getSession()->read('Auth.User.id');
        /* $ids = $this->Follow->find('list', [
                    'fields' => ['Follow.following_id'],
                    'conditions' => ['Follow.user_id' => $userId, 'Follow.deleted' => 0]
        ]); */
        $ids[] = $id;
        $data = $this->getPosts(['Posts.deleted' => 0, 'Posts.user_id IN' => $ids]);
        // pr($data);
        // die('controller');
        $post = $this->Posts->newEntity();
        $this->set(compact('post', 'data'));
        // $this->set('data', $data);
        // $this->set(compact('users'));
    }

    public function login()
    {
        $this->viewBuilder()->setLayout('default');
        if($this->request->is('post')) {
            $user = $this->Auth->identify();
            if($user) {
                if($user['is_online'] == 2) {
                    $this->Flash->error('Please activate your account first');
                } else {
                    $this->Auth->setUser($user);
                    return $this->redirect($this->Auth->redirectUrl("/users/home"));
                }
            } else {
                $this->Flash->error('Invalid username or password');
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
            // $message .= "<b>Activate your account by clicking </strong><a href='$activationUrl'>Activate Account now</a></strong></b><br/>";
            
            // $email = new Email('default');
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
        $this->Flash->success('You are now logout');
        return $this->redirect($this->Auth->logout());
    }

    public function register()
    {
        $this->set('title', 'User Registration');
        $this->viewBuilder()->setLayout('default');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $mytoken = Security::hash(Security::randomBytes(32));
            $user['token'] = $mytoken;

            if(!$user->getErrors()) {
                if ($this->Users->save($user)) {
                    $fullName = $user['last_name'].', '.$user['first_name'].' '.$user['middle_name'];
                    $userName = $user['username'];
                    $to = $user['email'];
                    $this->send_email($userName, $fullName, $to, $mytoken);
                    $this->Flash->success(__('Email has been sent to activate your account.'));
                    return $this->redirect(['action' => 'register']);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set('user', $user);
    }

    public function activation($token) {
        if(!$token) {
            throw new NotFoundException();
            $this->Flash->error(__('Invalid token'));
        }
        
        $user = TableRegistry::getTableLocator()->get('Users')->find('all', [
                        'conditions' => ['Users.token' => $token]
        ])->first();

        if(!$user) {
            throw new NotFoundException();
            $this->Flash->error(__('Invalid token!'));
        }
        
        if(isset($user['is_online']) && $user['is_online'] == 2) {
            $id = $user['id'];
            $user->set(['id' => $id, 'is_online' => 0]);
            
            $this->Users->save($user);
            $this->Flash->success(__('Account successfully verified!, You can now login'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        } else {
            $this->Flash->error(__('Account was already verified!'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
