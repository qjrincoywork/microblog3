<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\ORM\TableRegistry;

class SystemHelper extends Helper {    

    public function getFullNameById($id) {
        $user = TableRegistry::get('Users');
        $data = $user->get($id);
        $middleInitial = empty($data['middle_name']) ? '' : substr($data['middle_name'], 0, 1).". ";
        $fullName = ucwords($data['first_name'].' '.$middleInitial.$data['last_name'].' '.$data['suffix']);
        
        return $fullName;
    }
    
    public function getSharedPost($postId) {
        $post = TableRegistry::get('Posts');
        $data = $post->find('all', [
                                'contain' => ['Users'],
                                'conditions' => ['Posts.deleted' => 0,'Posts.id' => $postId]
                            ])->first();
        return $data;
    }
    
    public function reactionCount($postId, $reaction) {
        $model = TableRegistry::get($reaction);
        $count = $model->find('all',[
            'conditions' => [$reaction.".post_id" => $postId,
                             $reaction.".deleted" => 0]
        ])->count();
        
        return $count;
    }
    
    public function getUserPic($id) {
        $user = TableRegistry::get('Users')->get($id);
        $myImageSrc = $user->profile_image;
        return $myImageSrc;
    }
    
    public function getDateJoined($userId) {
        $user = TableRegistry::get('Users');
        $data = $user->find('all', [
                                'fields' => ['User.created'],
                                'conditions' => ['User.id' => $userId]
                            ])->first();
        
        $joined = date(' M Y', strtotime($data->created));
        return $joined;
    }
    
    public function postReaction($postId, $userId, $reaction) {
        $post = TableRegistry::get($reaction);
        $data = $post->find('all',[
                                'conditions' => [$reaction.'.user_id' => $userId, 
                                                $reaction.".post_id" => $postId,
                                                $reaction.".deleted" => 0]
                            ])->first();
        
        $hasReacted = ($data) ? true : false;
        return $hasReacted;
    }

    public function likedBefore($postId, $userId, $reaction) {
        $post = TableRegistry::get($reaction);
        $data = $post->find('all',[
                                'conditions' => [$reaction.'.user_id' => $userId, 
                                                $reaction.".post_id" => $postId]
                            ])->first();
        
        $hasReacted = ($data) ? true : false;
        return $hasReacted;
    }

    public function isFollowing($myId, $followingId) {
        $follow = TableRegistry::get('Follows');
        
        $data = $follow->find('all', [
                                'conditions' => [
                                    ['Follows.user_id' => $myId],
                                    ['Follows.following_id' => $followingId],
                                    ['Follows.deleted' => 0]
                                ]
                            ])->first();
                            
        $isFollowing = (!empty($data)) ? true : false;
        return $isFollowing;
    }

    public function hadFollowed($myId, $followingId) {
        $follow = TableRegistry::get('Follows');

        $data = $follow->find('all', [
            'conditions' => [
                ['Follows.user_id' => $myId],
                ['Follows.following_id' => $followingId]
            ]
        ])->first();
        
        $hadFollowed = (!empty($data)) ? true : false;
        return $hadFollowed;
    }
}