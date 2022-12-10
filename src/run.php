<?php

namespace AdventOfCode;

use AdventOfCode\Common\Console\Utils;

require_once __DIR__ . '/../vendor/autoload.php';

$time = microtime(true);

if (!$day = Utils::getDayNumberFromArgument()) {
    Utils::output('Please input day number!');
    return;
}
if (!is_numeric($day) || !$class = Utils::getClassByDayNumber($day, Utils::getIsExampleFromArgument())) {
    Utils::output('Invalid day value!');
    return;
}


$class->results();
Utils::output('Total runtime: ' . round(microtime(true) - $time, 2) . 'ms');
