<?php
require(__DIR__ . '/../../vendor/autoload.php');

use T4\FYITable\Model\Table;

$table = new Table();
$table->addColumn('col1', 'Column1');
$table->addColumn('col2', 'Column2');

$row = $table->createRow();
$row->addValue('col1', 'value for column 1');
$row->addValue('dyn col', 'A dynamically generated column');
$table->addRow($row);

echo print_r($row->getValues());
/*
Note that the row values also include col2, and now every Row->getValues method also return the dynamically added column
Array
(
    [col1] => value for column 1
    [col2] =>
    [dyn col] => A dynamically generated column
)
*/


$row = $table->createRow();
$row->addValues(['col1' => 'col1', 'col2' => 'row 2 col 1']);
$table->addRow($row);

// create a row with values from an array
$table->addRowFromArray(['col1' => 'row3 col1', 'col2' => 'row2 col 1']);






// show serialized data
echo json_encode($table, JSON_PRETTY_PRINT);

/*
```
{
    "attributes": [],
    "columns": {
        "col1": {
            "handle": "col1",
            "name": "Column1",
            "attributes": []
        },
        "col2": {
            "handle": "col2",
            "name": "Column2",
            "attributes": []
        },
        "dyn col": {
            "handle": "dyn col",
            "name": null,
            "attributes": []
        }
    },
    "rows": [
        {
            "attributes": [],
            "keys": {
                "col1": true,
                "col2": true
            },
            "values": {
                "col1": "row3 col1",
                "col2": "row2 col 1"
            }
        }
    ],
    "footer_rows": []
}
```
*/