@if (config('app.debug'))
<!--
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * !! THIS BANNER IS FOR INTERNAL USE ONLY !!
 * !! DISABLE DEBUG IN PRODUCTION !!
 *
 * @package  Dynq
 * @version {{ config('app.version') }}
 * @env     {{ app()->environment() }}
 * @debug   {{ config('app.debug') }}
 * @server  {{ gethostname() }}
 * @memory  {{ memory_get_usage(true) }}
 * @runtime {{ (microtime(true) - LARAVEL_START) }}
-->
@endif
