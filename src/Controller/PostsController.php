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
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('main');
    }
    
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $posts = $this->paginate($this->Posts);

        $this->set(compact('posts'));
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
        // $data = $this->Posts->find($id)->first();
        $this->set('data', $data);
        $this->paginate = [
            'limit' => 3,
            'conditions' => ['Comments.post_id' => $id, 'Comments.deleted' => 0],
            'order' => [
                'Comments.created'
            ]
        ];
        $this->set('comments', $this->paginate('Comments'));
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
            
            $this->response->withType('application/json');
            $this->autoRender = false;
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
        if ($this->request->is(['patch', 'post', 'put'])) {
            /* $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.')); */
            return $this->jsonResponse($data);
        }
        $this->set(compact('post'));
        
        // $this->set(compact('post', 'users'));
        /* if ($this->request->is(['patch', 'post', 'put'])) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        // $users = $this->Posts->Users->find('list', ['limit' => 200]);
        $this->set(compact('post', 'users')); */
    }

    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $post = $this->Posts->get($id);
        if ($this->Posts->delete($post)) {
            $this->Flash->success(__('The post has been deleted.'));
        } else {
            $this->Flash->error(__('The post could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
