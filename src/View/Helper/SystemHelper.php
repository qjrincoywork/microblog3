<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\ORM\TableRegistry;

class SystemHelper extends Helper {    

    public function getFullNameById($id) {
        $user = TableRegistry::get('Users');
        $data = $user->findById($id)->first();
        $middleInitial = empty($data['middle_name']) ? '' : substr($data['middle_name'], 0, 1).". ";
        
        $fullName = ucwords($data['first_name'].' '.$middleInitial.$data['last_name'].' '.$data['suffix']);
        
        return $fullName;
    }
    
    public function getSharedPost($postId) {
        $post = new Post();
        $post->virtualFields['post_ago'] = "CASE
                                                WHEN Post.created between date_sub(now(), INTERVAL 120 second) and now() 
                                                    THEN 'Just now'
                                                WHEN Post.created between date_sub(now(), INTERVAL 60 minute) and now() 
                                                    THEN concat(minute(TIMEDIFF(now(), Post.created)), ' minutes ago')
                                                WHEN datediff(now(), Post.created) = 1 
                                                    THEN 'Yesterday'
                                                WHEN Post.created between date_sub(now(), INTERVAL 24 hour) and now() 
                                                    THEN concat(hour(TIMEDIFF(NOW(), Post.created)), ' hours ago')
                                                ELSE concat(datediff(now(), Post.created),' days ago')
                                            END";
        $data = $post->find('first', [
            'conditions' => ['Post.id' => $postId, 'Post.deleted' => 0]
        ]);
        
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
    
    public function getUserPic($userId) {
        $user = new User();
        $userProfile = new UserProfile();
        $userProfile->virtualFields['image'] = "CASE 
                                                    WHEN UserProfile.image IS NULL
                                                    THEN
                                                        CASE
                                                        WHEN UserProfile.gender = 0
                                                            THEN '/img/default_avatar_f.svg'
                                                            ELSE '/img/default_avatar_m.svg'
                                                        END
                                                    ELSE concat('/',UserProfile.image)
                                                END";
        $data = $user->find('first', [
            'conditions' => ['User.id' => $userId]
        ]);
        
        $myImageSrc = $data['UserProfile']['image'];
        return $myImageSrc;
    }
    
    public function getDateJoined($userId) {
        $user = new User();
        $data = $user->find('first', [
            'fields' => ['User.created'],
            'conditions' => ['User.id' => $userId]
        ]);
        
        $joined = date(' M Y', strtotime($data['User']['created']));
        return $joined;
    }
    
    public function postReaction($postId, $userId, $reaction) {
        $hasReacted = false;
        $post = TableRegistry::get($reaction);
        $data = $post->find('all',[
            'conditions' => [$reaction.'.user_id' => $userId, 
                             $reaction.".post_id" => $postId,
                             $reaction.".deleted" => 0]
        ])->first();
        
        if($data) {
            $hasReacted = true;
        }
        
        return $hasReacted;
    }

    public function isFollowing($myId, $followingId) {
        $isFollowing = false;
        $follow = new Follow();

        $data = $follow->find('first', [
            'conditions' => ['Follow.user_id' => $myId, 'Follow.following_id' => $followingId]
        ]);
        
        if(!empty($data) && !$data['Follow']['deleted']) {
            $isFollowing = true;
        }
        
        return $isFollowing;
    }
}