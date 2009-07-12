<?php

/**
 * Simple article model for testing CacheQueries behavior.
 */
class Article extends Model {
   public $actsAs = array('CacheQueries.CacheQueries');
}

/**
 * Test class for CacheQueries behavior.
 */
class CacheQueriesTest extends CakeTestCase {

   var $fixtures = array(
      'plugin.cache_queries.articles',
   );

   /**
    * @var Article
    */
   var$Article = null;

   function startTest() {
      $this->Article =& ClassRegistry::init('Article');
   }

   function endTest() {
      unset($this->Article);
      ClassRegistry::flush();
   }

   function testSetResetCacheQueries() {
      $this->Article->cacheQueries = false;
      $this->Article->setCacheQueries(true);
      $this->assertTrue($this->Article->cacheQueries);
      $this->Article->setCacheQueries(false);
      $this->assertFalse($this->Article->cacheQueries);

      $this->Article->resetCacheQueries();
      $this->assertTrue($this->Article->cacheQueries);
      $this->Article->resetCacheQueries();
      $this->assertFalse($this->Article->cacheQueries);
   }
   
   function testCacheQueries() {
      $this->Article->cacheQueries = true;


      // confirm query is caching when cacheQueries is true and behavior isn't triggered

      $article = $this->Article->find('first', array(
         'conditions' => array('Article.id' => 1),
      ));
      $this->assertEqual('article 1', $article['Article']['body']);

      $this->Article->save(array('Article' => array(
          'id' => 1,
          'body' => 'new body',
      )));

      $article = $this->Article->find('first', array(
         'conditions' => array('Article.id' => 1),
      ));
      $this->assertEqual('article 1', $article['Article']['body']);



      // confirm query is NOT caching when cacheQueries is true and behavior IS triggered

      $article = $this->Article->find('first', array(
         'conditions' => array('Article.id' => 1),
         'cacheQueries' => false,
      ));
      $this->assertEqual('new body', $article['Article']['body']);
      $this->assertTrue($this->Article->cacheQueries);

      $this->Article->save(array('Article' => array(
          'id' => 1,
          'body' => 'another body'
      )));

      $article = $this->Article->find('first', array(
         'conditions' => array('Article.id' => 1),
         'cacheQueries' => false,
      ));
      $this->assertEqual('another body', $article['Article']['body']);
      $this->assertTrue($this->Article->cacheQueries);
   }
}

?>
