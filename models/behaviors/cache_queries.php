<?php
/**
 * Behavior to allow quick and easy manipulation of a model's cacheQueries setting.
 *
 * @author Dennis S. Hennen (dennis@28studios.com)
 * @copyright
 * @link
 */
class CacheQueriesBehavior extends ModelBehavior {

   /**
    * Store stack of cache query settings for each model.
    *
    * @var array
    * @access private
    */
   var $__cacheStack = array();


   /**
    * Set the value of cacheQueries, remembering the old value.
    *
    * @param object $Model Model using the behavior
    * @param Boolean $cacheQueries Value to set cacheQueries to.
    * @return void
    * @access public
    */
   function setCacheQueries(&$Model, $cacheQueries) {
      $this->__cacheStack[$Model->name][] = $Model->cacheQueries;
      $Model->cacheQueries = $cacheQueries;
   }


   /**
    * Restore last value of cacheQueries before setCacheQueries() call.
    *
    * @param object $Model Model using the behavior
    * @return void
    * @access public
    */
   function resetCacheQueries(&$Model) {
      $Model->cacheQueries = array_pop($this->__cacheStack[$Model->name]);
   }


   /**
    * Runs before a find() operation. Checks for cacheQueries option and sets cacheQueries
    * accordingly for Model.
    *
    *   Model->find('all', array('cacheQueries' => false));
    *
    * is the same as
    *
    *   $cacheQueries = Model->cacheQueries;
    *   Model->cacheQueries = false;
    *   Model->find('all');
    *   Model->cacheQueries = $cacheQueries;
    *
    * @param object $Model Model using the behavior
    * @param array $query Query parameters as set by cake
    * @return array
    * @access public
    */
   function beforeFind(&$Model, $query) {
      $cacheQueries = $Model->cacheQueries;
      if (isset($query['cacheQueries'])) {
         $cacheQueries = $query['cacheQueries'];
      }
      $Model->setCacheQueries($cacheQueries);
      unset($query['cacheQueries']);
      return $query;
   }


   /**
    * Reset cacheQueries after find is complete.
    *
    * @param object $Model Model using the behavior
    * @param array $results Results of the find operation
    * @param bool $primary true if this is the primary model that issued the find operation, false otherwise
    * @return void
    * @access public
    */
   public function afterFind(&$Model, $results, $primary) {
      $Model->resetCacheQueries();
   }
}

?>
