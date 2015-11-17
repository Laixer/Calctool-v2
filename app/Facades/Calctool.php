<?php

namespace Calctool\Facades;

use Illuminate\Support\Facades\Facade;

class Calctool extends Facade {
	protected static function getFacadeAccessor() { return 'calctool'; }
}