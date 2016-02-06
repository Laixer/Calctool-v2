#!/bin/bash

version=$(git describe)
cat >config/version.php <<EOL
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Versioning
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    */

    'describe' => '${version}',

];
EOL
