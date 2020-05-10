<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 05/03/2020
 */

namespace T4\FYITable\Model\Traits;


trait AttributesSupportingTrait
{

    /**
     * Some extra data storage
     *
     * @var array =  ['key' => 'value']
     */
    protected $attributes = [];

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param $handle
     * @param $default
     * @return mixed
     */
    public function getAttribute(string $handle, $default = null)
    {
        return $this->attributes[$handle] ?? $default;
    }

    public function setAttribute(string $handle, $value): self
    {
        $this->attributes[$handle] = $value;
        return $this;
    }

}