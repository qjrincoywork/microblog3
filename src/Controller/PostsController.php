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
    }
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('main');
        if($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout(false);
        }
    }
    
    public function view($id)
    {
        $data = $this->Posts->find('all', ['contain' => ['Users'],
                                           'conditions' => ['Posts.id' => $id,'Posts.deleted' => 0],
                ])->first();
        
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

    public function add()
    {
        $post = $this->Posts->newEntity();
        $datum['success'] = false;
        if ($this->request->is('post')) {
            $id = $this->request->getSession()->read('Auth.User.id');
            
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            $post->user_id = $id;
            if($this->request->getData()['image'] == 'undefined') {
                $post->image = null;
            } else {
                $uploadFolder = "img/".$id;
                
                if(!file_exists($uploadFolder)) {
                    mkdir($uploadFolder);
                }

                $path = $uploadFolder."/".$this->request->getData()['image']['name'];
                
                if(move_uploaded_file($this->request->getData()['image']['tmp_name'], $path)) {
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
    
    public function edit($id)
    {
        $post = $this->Posts->get($id, [
            'contain' => ['Users'],
        ]);
        if ($this->request->is(['put', 'patch'])) {
            $postData = $this->request->getData();
            $datum['success'] = false;
            $id = $this->request->getSession()->read('Auth.User.id');
            
            if($postData['image'] == 'undefined') {
                unset($postData['image']);
                $post = $this->Posts->patchEntity($post, $postData);
            } else {
                $post = $this->Posts->patchEntity($post, $postData);
                $uploadFolder = "img/".$id;
                
                if(!file_exists($uploadFolder)) {
                    mkdir($uploadFolder);
                }

                $path = $uploadFolder."/".$postData['image']['name'];
                
                if(move_uploaded_file($postData['image']['tmp_name'], $path)) {
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
            $userId = $this->request->getSession()->read('Auth.User.id');
            $postData = $this->request->getData();
            
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
