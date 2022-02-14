<?php namespace Seblhaire\BootstrapPaginator;

/**
 * Description of BootstrapPaginatorService
 *
 * @author seb
 */
class BootstrapPaginatorService implements BootstrapPaginatorContract{

  public function init($currentitem, $currentroute, $options = []){
      return new BootstrapPaginatorProvider($currentitem, $currentroute, $options);
  }

}
