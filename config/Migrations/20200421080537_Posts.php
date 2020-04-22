<?php
use Migrations\AbstractMigration;

class Posts extends AbstractMigration
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
        $table = $this->table('posts');
        $table->addColumn('user_id', 'integer', ['null' => false])
              ->addColumn('content', 'text', ['default' => null, 'null' => true, 'limit' => 140])
              ->addColumn('image', 'string', ['default' => null, 'null' => true, 'limit' => 255])
              ->addColumn('post_id', 'integer', ['default' => null, 'null' => true])
              ->addColumn('deleted', 'integer', ['default' => 0, 'null' => false,'limit' => 2])
              ->addColumn('created', 'datetime', ['default' => null, 'null' => false])
              ->addColumn('modified', 'datetime', ['default' => null, 'null' => false])
              ->create();
    }
}
