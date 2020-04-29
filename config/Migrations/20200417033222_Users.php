<?php
use Migrations\AbstractMigration;

class Users extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('username', 'string', ['collation' => 'utf8_bin', 'null' => false,'limit' => 255])
              ->addColumn('password', 'string', ['null' => false,'limit' => 255])
              ->addColumn('token', 'string', ['null' => false,'limit' => 255])
              ->addColumn('email', 'string', ['collation' => 'utf8_bin', 'null' => false, 'limit' => 255])
              ->addColumn('image', 'string', ['default' => null, 'null' => true, 'limit' => 255])
              ->addColumn('first_name', 'string', ['null' => false, 'limit' => 255])
              ->addColumn('last_name', 'string', ['null' => false, 'limit' => 255])
              ->addColumn('middle_name', 'string', ['null' => true, 'limit' => 255])
              ->addColumn('suffix', 'string', ['null' => true, 'limit' => 255])
              ->addColumn('gender', 'integer', ['null' => false,'limit' => 2, 'comment' => '0-female; 1-male;'])
              ->addColumn('is_online', 'integer', ['default' => 2, 'null' => false,'limit' => 5, 'comment' => '0-offline; 1-online; 2-pending_activation;'])
              ->addColumn('deleted', 'integer', ['default' => 0, 'null' => false,'limit' => 2])
              ->addColumn('created', 'datetime', ['default' => null, 'null' => false])
              ->addColumn('modified', 'datetime', ['default' => null, 'null' => false])
              ->create();
    }
}