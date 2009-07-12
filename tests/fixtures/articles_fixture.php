<?php
class ArticlesFixture extends CakeTestFixture {
   public $name = 'Article';
   public $table = 'articles';
   public $fields = array(
      'id' => array('type' => 'integer', 'null' => 0, 'null' => NULL, 'default' => NULL, 'length' => '11', 'key' => 'primary'),
      'body' => array('type' => 'string', 'null' => 1, 'null' => '1', 'default' => NULL, 'length' => 255),
   );
   public $records = array(
      array(
         'id' => 1,
         'body' => 'article 1',
      ),
      array(
         'id' => 2,
         'body' => 'article 2',
      ),
   );
}
?>