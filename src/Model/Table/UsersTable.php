<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->hasMany('Posts', [
            'foreignKey' => 'user_id',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');
            
        $validator
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmptyString('username', 'This field is required.');
            
        $validator
            ->add('password', 'passwordRule-1',[
                'rule' => ['custom', "/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/"],
                'message' => 'The password does not meet the requirements.'
            ])
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'This field is required.');

        $validator
            /* ->add('confirm_password', 'custom', [
                'rule' => function ($value, $context) {
                    if ($context['data']['password'] !== $value){
                        return false;
                    }
                    return true;
                },
                'message' => 'Does not match with password.',
            ]) */
            ->add('confirm_password', 'no-misspelling', [
                'rule' => ['compareWith', 'password'],
                'message' => 'Passwords are not equal',
            ])
            ->scalar('confirm_password')
            ->maxLength('confirm_password', 255)
            ->requirePresence('confirm_password', ['create', 'update'])
            ->notEmptyString('confirm_password', 'This field is required.');

        /* $validator
            ->add('old_password', 'custom', [
                'rule' => function ($value, $context) {
                    if ($context['data']['password'] !== $value){
                        return false;
                    }
                    return true;
                },
                'message' => 'Does not match with password.',
            ])        
            ->scalar('old_password')
            ->maxLength('old_password', 255)
            ->requirePresence('old_password', 'update')
            ->notEmptyString('old_password', 'This field is required.'); */
            
        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'This field is required.');
            
        $validator
            ->scalar('image')
            ->maxLength('image', 255)
            ->allowEmptyFile('image');
            
        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 255)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name', 'This field is required.');
            
        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 255)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name', 'This field is required.');
            
        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 255)
            ->allowEmptyString('middle_name');
            
        $validator
            ->scalar('suffix')
            ->maxLength('suffix', 255)
            ->allowEmptyString('suffix');
            
        $validator
            ->integer('gender')
            ->requirePresence('gender', 'create')
            ->notEmptyString('gender', 'This field is required.');
            
        return $validator;
    }

    public function validationRegister(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');
            
        $validator
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmptyString('username', 'This field is required.');
            
        $validator
            ->add('password', 'passwordRule-1',[
                'rule' => ['custom', "/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/"],
                'message' => 'The password does not meet the requirements.'
            ])
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'This field is required.');

        $validator
            /* ->add('confirm_password', 'custom', [
                'rule' => function ($value, $context) {
                    if ($context['data']['password'] !== $value){
                        return false;
                    }
                    return true;
                },
                'message' => 'Does not match with password.',
            ]) */
            ->add('confirm_password', 'no-misspelling', [
                'rule' => ['compareWith', 'password'],
                'message' => 'Passwords are not equal',
            ])
            ->scalar('confirm_password')
            ->maxLength('confirm_password', 255)
            ->requirePresence('confirm_password', ['create', 'update'])
            ->notEmptyString('confirm_password', 'This field is required.');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'This field is required.');
            
        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 255)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name', 'This field is required.');
            
        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 255)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name', 'This field is required.');
            
        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 255)
            ->allowEmptyString('middle_name');
            
        $validator
            ->scalar('suffix')
            ->maxLength('suffix', 255)
            ->allowEmptyString('suffix');
            
        $validator
            ->integer('gender')
            ->requirePresence('gender', 'create')
            ->notEmptyString('gender', 'This field is required.');
            
        return $validator;
    }

    public function validationUpdate(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');
            
        $validator
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmptyString('username', 'This field is required.');
            

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'This field is required.');
            
        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 255)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name', 'This field is required.');
            
        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 255)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name', 'This field is required.');
            
        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 255)
            ->allowEmptyString('middle_name');
            
        $validator
            ->scalar('suffix')
            ->maxLength('suffix', 255)
            ->allowEmptyString('suffix');
            
        $validator
            ->integer('gender')
            ->requirePresence('gender', 'create')
            ->notEmptyString('gender', 'This field is required.');

        return $validator;
    }

    public function validationPasswords(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');
            
        $validator
            ->add('password', 'passwordRule-1',[
                'rule' => ['custom', "/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/"],
                'message' => 'The password does not meet the requirements.'
            ])
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'This field is required.');

        $validator
            ->add('confirm_password', 'custom', [
                'rule' => function ($value, $context) {
                    if ($context['data']['password'] !== $value){
                        return false;
                    }
                    return true;
                },
                'message' => 'Does not match with password.',
            ])
            ->scalar('confirm_password')
            ->maxLength('confirm_password', 255)
            ->requirePresence('confirm_password', ['create', 'update'])
            ->notEmptyString('confirm_password', 'This field is required.');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        
        return $rules;
    }
}
