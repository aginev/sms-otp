<?php

namespace SmsOtp\Models;

abstract class Model
{
    /**
     * Primary key name
     *
     * @var string
     */
    protected string $keyName = 'id';
    
    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if ($data) {
            $this->fill($data);
        }
    }
    
    /**
     * Fill public properties with data
     *
     * @param $data
     */
    private function fill($data): void
    {
        foreach ($this->getPublicProperties() as $property) {
            if (array_key_exists($property->getName(), $data)) {
                $this->{$property->getName()} = $data[$property->getName()];
            }
        }
    }
    
    /**
     * Get class public properties
     *
     * @return \ReflectionProperty[]
     */
    private function getPublicProperties(): array
    {
        $result = [];
        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            
            $result[] = $property;
        }
        
        return $result;
    }
    
    /**
     * Check if the class has public property
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasProperty(string $name): bool
    {
        foreach ($this->getPublicProperties() as $property) {
            if ($property->getName() === $name) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if the model record exists at the database.
     * In general if the primary key has been set this will mean that the model has been loaded from the database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return (bool)$this->${$this->keyName};
    }
    
    /**
     * Array representation of the object
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        
        foreach ($this->getPublicProperties() as $property) {
            $result[$property->getName()] = $property->getValue($this);
        }
        
        return $result;
    }
}
