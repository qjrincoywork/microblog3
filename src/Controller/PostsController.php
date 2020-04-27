<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 *
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        // $this->loadComponent('Security', ['blackHoleCallback' => 'blackHole']);
        // $this->loadComponent('Security');
    }
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // $this->Security->requireSecure();
        $this->viewBuilder()->setLayout('main');
        // $this->Security->setConfig('unlockedActions', ['edit']);
        if($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout(false);
        }
    }
    
    // public function blackhole($errorType) {
    public function blackhole($type/* , SecurityException $exception */) {
        /* $errorMap['auth']   = 'form validation error, or a controller/action mismatch error.';
        $errorMap['csrf']   = 'CSRF error.';
        $errorMap['get']    = 'HTTP method restriction failure.';
        $errorMap['post']   = $errorMap['get'];
        $errorMap['put']    = $errorMap['get'];
        $errorMap['delete'] = $errorMap['get'];
        $errorMap['secure'] = 'SSL method restriction failure.';
        $errorMap['myMoreValuableErrorType']    = 'My custom and very ' . 
        'specific reason for the error type.'; */
        pr($type);
        die('blackhole');
        if ($exception instanceof SecurityException && $exception->getType() === 'secure') {
            return $this->redirect('https://' . env('SERVER_NAME') . Router::url($this->request->getRequestTarget()));
        }

        throw $exception;
        /* CakeLog::notice("Request to the '{$this->request->params['action']}' " . 
                    "endpoint was blackholed by SecurityComponent due to a {$errorMap[$errorType]}"); */
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id)
    {
        $data = $this->Posts->get($id, [
            'contain' => ['Users'],
        ]);
        
        $this->paginate = [
            'limit' => 3,
            'contain' => ['Users'],
            'conditions' => ['Comments.post_id' => $id, 'Comments.deleted' => 0],
            'order' => [
                'Comments.created'
            ]
        ];
        $comments = $this->paginate('Comments');
        $this->set(compact('data', 'comments'));
        $this->set('title', 'User Post');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $post = $this->Posts->newEntity();
        $datum['success'] = false;
        if ($this->request->is('post')) {
            $id = $this->request->getSession()->read('Auth.User.id');
            $username = $this->request->getSession()->read('Auth.User.username');
            
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            $post->user_id = $id;
            if($this->request->getData()['image'] == 'undefined') {
                $post->image = null;
            } else {
                $uploadFolder = "img/".$username;
                
                if(!file_exists($uploadFolder)) {
                    mkdir($uploadFolder);
                }

                $path = $uploadFolder."/".$this->request->getData()['image']['name'];
                
                if(move_uploaded_file($this->request->getData()['image']['tmp_name'],
                                      $path)) {
                    $this->request->getData()['image'] = $path;
                }
                $post->image = $path;
            }
            
            if(!$post->getErrors()) {
                if ($this->Posts->save($post)) {
                    $datum['success'] = true;
                }
            } else {
                $errors = $this->formErrors($post);
                $datum['errors'] = $errors;
            }
            
            return $this->jsonResponse($datum);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $post = $this->Posts->get($id, [
            'contain' => ['Users'],
        ]);
        if ($this->request->is(['put', 'patch'])) {
            $postData = $this->request->getData();
            $datum['success'] = false;
            $id = $this->request->getSession()->read('Auth.User.id');
            $username = $this->request->getSession()->read('Auth.User.username');
            
            if($postData['image'] == 'undefined') {
                unset($postData['image']);
                $post = $this->Posts->patchEntity($post, $postData);
            } else {
                $post = $this->Posts->patchEntity($post, $postData);
                $uploadFolder = "img/".$username;
                
                if(!file_exists($uploadFolder)) {
                    mkdir($uploadFolder);
                }

                $path = $uploadFolder."/".$postData['image']['name'];
                
                if(move_uploaded_file($postData['image']['tmp_name'],
                                      $path)) {
                        $post->image = $path;
                }
            }
            $post->user_id = $id;

            if($post->getErrors()) {
                if(array_key_exists('id', $post->getErrors())) {
                    $datum['error'] = $post->getError('id.isMine');
                } else {
                    $errors = $this->formErrors($post);
                    $datum['errors'] = $errors;
                }
            } else {
                if ($this->Posts->save($post)) {
                    $datum['success'] = true;
                }
            }
            
            return $this->jsonResponse($datum);
        }
        $this->set(compact('post'));
    }
    
    public function share($id) {
        $post = $this->Posts->newEntity();
        $datum['success'] = false;
        if($this->request->is('post')) {
            $postData = $this->request->getData();
            // $postData['created'] = date('Y-m-d H:i:s');
            $userId = $this->request->getSession()->read('Auth.User.id');
            $post = $this->Posts->patchEntity($post, $postData);
            $post->user_id = $userId;
            
            if(!$post->getErrors()) {
                if ($this->Posts->save($post)) {
                    $datum['success'] = true;
                }
            } else {
                $errors = $this->formErrors($post);
                $datum['errors'] = $errors;
            }
            
            return $this->jsonResponse($datum);
        }
        $data = $this->Posts->get($id, [
            'contain' => ['Users'],
        ]);
        $this->set(compact('data', 'post'));
    }

    public function delete($id)
    {
        $post = $this->Posts->get($id);
        if($this->request->is(['put', 'patch'])) {
            $datum['success'] = false;
            $postData = $this->request->getData();
            $userId = $this->request->getSession()->read('Auth.User.id');
            $post = $this->Posts->patchEntity($post, $postData);
            $post->user_id = $userId;
            
            if($post->getErrors()) {
                if(array_key_exists('id', $post->getErrors())) {
                    $datum['error'] = $post->getError('id.isMine');
                } else {
                    $errors = $this->formErrors($post);
                    $datum['errors'] = $errors;
                }
            } else {
                if ($this->Posts->save($post)) {
                    $datum['success'] = true;
                }
            }
            
            return $this->jsonResponse($datum);
        }
        $this->set(compact('post'));
    }
}
