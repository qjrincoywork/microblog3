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
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('main');
        if($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout(false);
        }
    }
    
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
                                        'conditions' => ['Posts.id' => $id]
                                  ])->first();
        
        $this->set(compact('data', 'comment'));
    }
    
    public function edit($id)
    {
        $comment = $this->Comments->get($id);
        if($this->request->is(['put', 'patch'])) {
            $datum['success'] = false;
            $id = $this->request->getSession()->read('Auth.User.id');
            $postData = $this->request->getData();
            $postData['user_id'] = $id;
            $comment = $this->Comments->patchEntity($comment, $postData);
            
            if($comment->getErrors()) {
                if(array_key_exists('id', $comment->getErrors())) {
                    $datum['error'] = $comment->getError('id.isMine');
                } else {
                    $errors = $this->formErrors($comment);
                    $datum['errors'] = $errors;
                }
            } else {
                if ($this->Comments->save($comment)) {
                    $datum['success'] = true;
                }
            }
            
            return $this->jsonResponse($datum);
        }
        $this->set(compact('comment'));
    }

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
            
            if($comment->getErrors()) {
                if(array_key_exists('id', $comment->getErrors())) {
                    $datum['error'] = $comment->getError('id.isMine');
                } else {
                    $errors = $this->formErrors($comment);
                    $datum['errors'] = $errors;
                }
            } else {
                if ($this->Comments->save($comment)) {
                    $datum['success'] = true;
                }
            }
            
            return $this->jsonResponse($datum);
        }
        $this->set(compact('comment'));
    }
}
