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
        $datum['success'] = false;
        $id = $this->request->getSession()->read('Auth.User.id');
        
        $exists = $this->Likes->find('all', [
            'conditions' => [
                    ['Likes.post_id' => $postId,
                     'Likes.user_id' => $id
                ]
            ]
        ])->first();
        
        if(!$exists) {
            $datum['success'] = true;
            $like = $this->Likes->newEntity();
            $like->post_id = $postId;
            $like->user_id = $id;
            $this->Likes->save($like);
        } else {
            $datum['success'] = true;
            $status = $exists->deleted ? 0 : 1;
            $exists->deleted = $status;
            $this->Likes->save($exists);
        }
        
        return $this->jsonResponse($datum);
    }
}
