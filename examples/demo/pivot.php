<?php
require(__DIR__ . '/../../vendor/autoload.php');

use T4\FYITable\Model\Table;


$table = new Table();
$table->addRowFromArray(['name' => 'Mike', 'win' => 3, 'loose' => 4]);
$table->addRowFromArray(['name' => 'Mike', 'win' => 2, 'loose' => 1]);
$table->addRowFromArray(['name' => 'John', 'win' => 5, 'loose' => 6]);


$pivot = $table->getPivotData();
print_r($pivot);

/*
(
    [name] => Array
        (
            [0] => Mike
            [1] => Mike
            [2] => John
        )

    [win] => Array
        (
            [0] => 3
            [1] => 2
            [2] => 5
        )

    [loose] => Array
        (
            [0] => 4
            [1] => 1
            [2] => 6
        )

)
*/
