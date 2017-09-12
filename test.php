<?php

require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use MC2P\APIClient;

$a = new APIClient('asa', 'asa');
echo $a;

?>