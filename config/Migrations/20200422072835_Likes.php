<?php
use Migrations\AbstractMigration;

class Likes extends AbstractMigration
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
        $table = $this->table('likes');
        $table->addColumn('user_id', 'integer', ['null' => false])
              ->addColumn('post_id', 'integer', ['null' => false])
              ->addColumn('deleted', 'integer', ['default' => 0, 'null' => false,'limit' => 2])
              ->addColumn('created', 'datetime', ['default' => null, 'null' => false])
              ->addColumn('modified', 'datetime', ['default' => null, 'null' => false])
              ->create();
    }
}
