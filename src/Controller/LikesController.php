<?php
namespace App\Controller;

use App\Controller\AppController;

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
    
    public function add() {
        die('hits likes Controller');
        $datum['success'] = false;
        $postData = $this->request->getData();
        pr($postData);
        /* $id = $this->request->getSession()->read('Auth.User.id');
        $like['Like']['post_id'] = $this->request->data['post_id'];
        $like['Like']['user_id'] = $id;
        
        $exists = $this->Like->find('first', [
            'conditions' => [
                    ['Like.post_id' => $like['Like']['post_id'],
                        'Like.user_id' => $like['Like']['user_id']
                ]
            ]
        ]);
        
        if(!$exists) {
            $this->Like->save($like);
        } else {
            $status = $exists['Like']['deleted'] ? 0 : 1;
            $exists['Like']['deleted'] = $status;
            $this->Like->save($exists);
        } */
        
        return $this->jsonResponse($datum);
    }
}
