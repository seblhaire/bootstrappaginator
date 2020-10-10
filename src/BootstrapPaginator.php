<?php
namespace Seblhaire\BootstrapPaginator;

use Illuminate\Support\Facades\Facade;

class BootstrapPaginator extends Facade{
	protected static function getFacadeAccessor() {
		return 'BootstrapPaginatorService';
	}
}
