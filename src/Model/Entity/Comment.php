<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Time;

/**
 * Comment Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $content
 * @property int $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Post $post
 */
class Comment extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    /* protected $_accessible = [
        'user_id' => true,
        'post_id' => true,
        'content' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'post' => true,
    ]; */
    protected $_virtual = ['comment_ago'];

    protected function _getCommentAgo()
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
