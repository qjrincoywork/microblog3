<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Http\Session;

/**
 * Posts Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PostsTable&\Cake\ORM\Association\BelongsTo $Posts
 * @property \App\Model\Table\PostsTable&\Cake\ORM\Association\HasMany $Posts
 *
 * @method \App\Model\Entity\Post get($primaryKey, $options = [])
 * @method \App\Model\Entity\Post newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Post[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Post|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Post[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Post findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PostsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('posts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Posts', [
            'foreignKey' => 'post_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Posts', [
            'foreignKey' => 'post_id',
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'post_id',
        ]);
        $this->hasMany('Likes', [
            'foreignKey' => 'post_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', [
                'isMine' => [
                    'rule' => 'isMine',
                    'provider' => 'table',
                    'message' => __("Unable to process action.")
                ]
            ])
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->maxLength('content', 140)
            ->notEmptyString('content', 'This field is required.');

        $validator
            ->add('image', [
                'imageType' => [
                    'rule' => 'imageType',
                    'provider' => 'table',
                    'allowEmpty' => true,
                    'message' => __('Please input a valid image.')
                ]
            ])
            ->scalar('image')
            ->maxLength('image', 255)
            ->allowEmptyFile('image');

        $validator
            ->integer('deleted')
            ->notEmptyString('deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['post_id'], 'Posts'));

        return $rules;
    }

    public function isMine($value, $context) {
        $session = new Session();
        $userId = $session->read('Auth.User.id');
        $data = $this->find('all', [
            'conditions' => ['Posts.id' => $context['data']['id'], 'Posts.user_id' => $userId]
        ])->first();
        
        if($data) {
            return true;
        }
        
        return false;
    }
    
    public function imageType($value, $context)
    {
        if($context['data']['image'] != 'undefined' && isset($context['data']['image']['type'])) {
            $types = ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'];
            if(in_array($context['data']['image']['type'], $types)) {
                return true;
            }
        } else {
            return true;
        }
        return false;
    }
}
