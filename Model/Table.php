<?php

namespace T4\FYITable\Model;


use T4\FYITable\Model\Traits\AttributesSupportingTrait;
use T4\FYITable\Model\Traits\JsonSerializableDeserializableTrait;

/**
 * Class Table
 * @package App\Model\Table
 *
 * A table where you can add assoc array data
 *
 * Columns are dynamically registered so you can add rows with variable length,
 * but get back data by all shared columns
 */
class Table implements \JsonSerializable
{
    use JsonSerializableDeserializableTrait;

    /**
     * The class using the JsonSerializableDeserializableTrait trait MUST have a constant SerializableProps
     * Map of json property name to object property name (for serialization)
     *
     * @var array = ['attributes' => 'attributes', 'columns' => 'columns', 'rows' => 'rows', 'footer_rows' => 'footerRows']
     */
    protected const SERIALIZABLE_PROPS = ['attributes' => 'attributes', 'columns' => 'columns', 'rows' => 'rows', 'footer_rows' => 'footerRows'];

    use AttributesSupportingTrait;

    /**
     * @var Column[] = ['handle' => Column, 'handle2' => Column]
     */
    protected $columns = [];

    /**
     * @var Row[]
     */
    protected $rows = [];

    /**
     * Rows in the footer, to show totals and so on..
     * @var Row[]
     */
    protected $footerRows = [];

    /**
     * Adds a column but does not overwrite if any present
     * @param $handle
     * @param $name
     * @return Column|mixed
     */
    public function addColumn(string $handle, ?string $name = null)
    {
        return $this->columns[$handle] ?? $this->columns[$handle] = new Column($this, $handle, $name);
    }

    public function removeColumn($handle): self
    {
        $handle = $handle instanceof Column ? $handle->getHandle() : $handle;
        if (isset($this->columns[$handle])) {
            unset($this->columns[$handle]);
        }
        return $this;
    }

    /**
     * @param string $handle
     * @return Column|mixed|null
     */
    public function getColumn(string $handle) {
        return $this->columns[$handle] ?? null;
    }

    /**
     * @param array $data = ['column_handle' => 'value', 'column_handle2' => 'value2']
     * @return Row
     */
    public function addRowFromArray(array $data): Row
    {
        return $this->rows[] = new Row($this, $data);
    }

    /**
     * @param array $data = ['column_handle' => 'value', 'column_handle2' => 'value2']
     * @return Row
     */
    public function addFooterRowFromArray(array $data): Row
    {
        return $this->footerRows[] = new Row($this, $data);
    }

    /**
     * @return Row
     */
    public function createRow(): Row
    {
        return new Row($this);
    }

    /**
     * @param Row $row
     * @return $this
     */
    public function addRow(Row $row) : Table
    {
        $this->rows[] = $row;
        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return Row[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @return Row[]
     */
    public function getFooterRows(): array
    {
        return $this->footerRows;
    }

    /**
     * @param string | Column $handle
     * @param mixed $default
     * @return array
     */
    public function getDataForColumn($handle, $default = null): array
    {
        $handle = $handle instanceof Column ? $handle->getHandle() : $handle;
        $result = [];
        foreach ($this->rows as $row) {

            $result[] = $row->getValue($handle, $default);
        }
        return $result;
    }

    /**
     * Returns pivotted data, i.e. returns array with column handles and all columns
     * @param mixed $default
     * @return array = ['column_handle' => ['val1', 'val2'], 'column2_handle' => ['val3', 'val4']]
     */
    public function getPivotData($default = null): array
    {
        $result = [];
        foreach ($this->columns as $column) {
            $result[$column->getHandle()] = $this->getDataForColumn($column->getHandle(), $default);
        }
        return $result;
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->rows);
    }


    /**
     * Deserialize
     *
     * @param array $json
     * @return static
     */
    public static function createFromArray(array $json) : self {
        $inst = new static();
        foreach (static::SERIALIZABLE_PROPS as $jsonProp => $propName) {
            switch ($propName) {
                case 'columns':
                    $jsonColumns = $json[$jsonProp] ?? [];
                    foreach ($jsonColumns as $jsonColumn) {
                        $column = new Column($inst, '', null);
                        $column->hydrateWithArray($jsonColumn);
                        $inst->columns[$column->getHandle()] = $column;
                    }
                    break;
                case 'rows':
                    $jsonRows = $json[$jsonProp] ?? [];
                    foreach ($jsonRows as $jsonRow) {
                        $row = new Row($inst);
                        $row->hydrateWithArray($jsonRow);
                        $inst->rows[] = $row;
                    }
                    break;
                case 'footerRows':
                    $jsonRows = $json[$jsonProp] ?? [];
                    foreach ($jsonRows as $jsonRow) {
                        $row = new Row($inst);
                        $row->hydrateWithArray($jsonRow);
                        $inst->footerRows[] = $row;
                    }
                    break;
                default:
                    $inst->{$propName} = array_key_exists($jsonProp, $json) ? $json[$jsonProp] : $inst->{$propName};
            }
        }
        return $inst;
    }


}