<?php
header("Content-Type:text/plain");
system("../artisan migrate:refresh --seed");
?>
