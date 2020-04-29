<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class LikesController extends AppController
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
    
    public function add($postId) {
        $id = $this->request->getSession()->read('Auth.User.id');
        
        $exists = $this->Likes->find('all', [
                                        'conditions' => [
                                            ['Likes.post_id' => $postId],
                                            ['Likes.user_id' => $id]
                                        ]
                                    ])->first();
                  
        if(!$exists) {
            $like = $this->Likes->newEntity();
            $like->post_id = $postId;
            $like->user_id = $id;
            $result = $this->Likes->save($like);
        }
        $datum = ['success' => (isset($result)) ? true : false];
        return $this->jsonResponse($datum);
    }
    
    public function delete($postId) {
        $id = $this->request->getSession()->read('Auth.User.id');
        
        $exists = $this->Likes->find('all', [
                                        'conditions' => [
                                            ['Likes.post_id' => $postId],
                                            ['Likes.user_id' => $id]
                                        ]
                                    ])->first();
                  
        if($exists) {
            $status = $exists->deleted ? 0 : 1;
            $exists->deleted = $status;
            $result = $this->Likes->save($exists);
        }
        $datum = ['success' => (isset($result)) ? true : false];
        return $this->jsonResponse($datum);
    }
}
