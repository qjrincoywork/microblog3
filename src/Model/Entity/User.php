<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\Http\Session;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $token
 * @property int $is_online
 * @property int $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class User extends Entity
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
        'username' => true,
        'password' => true,
        'token' => true,
        'is_online' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true,
    ]; */
    protected $_virtual = ['full_name', 'profile_image', 'joined'];

    protected function _getFullName()
    {
        $session = new Session;
        $id = $session->read('Auth.User.id');
        if($id) {
            $middleInitial = empty($this->_properties['middle_name']) ? '' : substr($this->_properties['middle_name'], 0, 1).". ";
            $fullName = ucwords($this->_properties['first_name'].' '.$middleInitial.$this->_properties['last_name'].' '.$this->_properties['suffix']);
            
            return $fullName;
        }
    }
    
    protected function _getProfileImage()
    {
        $session = new Session;
        $id = $session->read('Auth.User.id');
        if($id) {
            if(!$this->_properties['image']) {
                if($this->_properties['gender']) {
                    $image = '/img/default_avatar_m.svg';
                } else {
                    $image = '/img/default_avatar_f.svg';
                }
            } else {
                $image = '/'.$this->_properties['image'];
            }
            return $image;
        }
    }
    
    protected function _getJoined()
    {
        $session = new Session;
        $id = $session->read('Auth.User.id');
        if($id) {
            if(isset($this->_properties['created'])) {
                $joined = date(' M Y', strtotime($this->_properties['created']));
                return $joined;
            }
        }
    }
    
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
    
    protected $_hidden = [
        'password',
        'token',
    ];
    
    protected function _setPassword($password) {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
