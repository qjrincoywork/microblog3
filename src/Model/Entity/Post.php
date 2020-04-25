<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Time;
/**
 * Post Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $content
 * @property string|null $image
 * @property int $post_id
 * @property int $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Post[] $posts
 */
class Post extends Entity
{
    protected $_virtual = ['post_ago'];

    protected function _getPostAgo()
    {
        if(isset($this->_properties['created'])) {
            $now = new Time;
            $ago = new Time($this->_properties['created']);
            
            $diff = $now->diff($ago);
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
            
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
            
            $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }
    }
    
    protected $_accessible = [
        'id' => false,
        '*' => true
    ];
}
