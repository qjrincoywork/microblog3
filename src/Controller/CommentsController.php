<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 *
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
{
    
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Posts');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Posts'],
        ];
        $comments = $this->paginate($this->Comments);

        $this->set(compact('comments'));
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('main');
        if($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout(false);
        }
    }
    /**
     * View method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => ['Users', 'Posts'],
        ]);

        $this->set('comment', $comment);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id)
    {
        $comment = $this->Comments->newEntity();
        if($this->request->is('post')) {
            $datum['success'] = false;
            $id = $this->request->getSession()->read('Auth.User.id');
            $postData = $this->request->getData();
            $postData['user_id'] = $id;
            $comment = $this->Comments->patchEntity($comment, $postData);
            
            if(!$comment->getErrors()) {
                if ($this->Comments->save($comment)) {
                    $datum['success'] = true;
                }
            } else {
                $errors = $this->formErrors($comment);
                $datum['errors'] = $errors;
            }
            
            return $this->jsonResponse($datum);
        }
        
        $data = $this->Posts->find('all', [
            'contain' => ['Users'],
            'conditions' => ['Posts.id' => $id],
        ])->first();
        
        $this->set(compact('data', 'comment'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $comment = $this->Comments->get($id);
        if($this->request->is(['put', 'patch'])) {
            $datum['success'] = false;
            $id = $this->request->getSession()->read('Auth.User.id');
            $postData = $this->request->getData();
            $postData['user_id'] = $id;
            $comment = $this->Comments->patchEntity($comment, $postData);
            
            if(!$comment->getErrors()) {
                if ($this->Comments->save($comment)) {
                    $datum['success'] = true;
                }
            } else {
                $errors = $this->formErrors($comment);
                $datum['errors'] = $errors;
            }
            
            return $this->jsonResponse($datum);
        }
        
        $this->set(compact('comment'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id)
    {
        $comment = $this->Comments->get($id);
        
        if($this->request->is(['put', 'patch'])) {
            $datum['success'] = false;
            
            $id = $this->request->getSession()->read('Auth.User.id');
            $postData = $this->request->getData();
            $postData['deleted'] = 1;
            $postData['user_id'] = $id;

            $comment = $this->Comments->patchEntity($comment, $postData, ['validate' => 'Delete']);
            
            if(!$comment->getErrors()) {
                if ($this->Comments->save($comment)) {
                    $datum['success'] = true;
                }
            } else {
                $errors = $this->formErrors($comment);
                $datum['errors'] = $errors;
            }
            
            return $this->jsonResponse($datum);
        }
        $this->set(compact('comment'));
    }
}
