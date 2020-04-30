<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 03/03/2020
 */

namespace T4\FYITable\Model\Traits;



trait JsonSerializableDeserializableTrait
{

    /**
     * The class using the JsonSerializableDeserializableTrait trait MUST have a constant SerializableProps
     * Map of json property name to object property name (for serialization)
     *
     * @var array = ['jsonprop' => 'instanceProp', 'jsonprop2' => 'instanceProp2']
     */
    //protected const SERIALIZABLE_PROPS = [];

    /**
     * Serialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $json = [];
        foreach (static::SERIALIZABLE_PROPS as $jsonProp => $propName) {
            $json[$jsonProp] = $this->{$propName};
        }
        return $json;
    }

    public function hydrateWithArray(array $json) : self {
        foreach (static::SERIALIZABLE_PROPS as $jsonProp => $propName) {
            $this->{$propName} = array_key_exists($jsonProp, $json) ? $json[$jsonProp] : $this->{$propName};
        }
        return $this;
    }

    /**
     * Deserialize
     *
     * @param array $json
     * @return static
     */
    public static function createFromArray(array $json) : self {
        $inst = new static();
        return $inst->hydrateWithArray($json);
    }

}