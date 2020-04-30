<?php

namespace T4\FYITable\Model;

use T4\FYITable\Model\Traits\AttributesSupportingTrait;
use T4\FYITable\Model\Traits\JsonSerializableDeserializableTrait;

class Column implements \JsonSerializable
{

    use JsonSerializableDeserializableTrait;

    /**
     * The class using the JsonSerializableDeserializableTrait trait MUST have a constant SerializableProps
     * Map of json property name to object property name (for serialization)
     *
     * @var array = ['handle' => 'handle', 'name' => 'name', 'attributes' => 'attributes']
     */
    protected const SERIALIZABLE_PROPS = ['handle' => 'handle', 'name' => 'name', 'attributes' => 'attributes'];

    use AttributesSupportingTrait;

    /**
     * @var string
     */
    protected $handle;

    /**
     * @var string | null
     */
    protected $name;

    public function __construct(Table $table, string $handle, ?string $name)
    {
        $this->handle = $handle;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Column
     */
    public function setName(?string $name): Column
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Return a name to display
     * @return string|null
     */
    public function displayName()
    {
        return $this->name ?? $this->handle;
    }

    public function __toString()
    {
        return $this->getHandle();
    }


}