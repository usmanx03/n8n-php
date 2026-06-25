<?php

namespace UsmanZahid\N8n\Entities;

abstract class Entity {
    public function __construct(array $data = []) {
        foreach ($this->getFields() as $property => $definition) {
            $key = $definition['key'] ?? $property;
            $type = $definition['type'] ?? 'string';
            $class = $definition['class'] ?? null;

            if (!array_key_exists($key, $data)) {
                continue; // keep default property value
            }

            $value = $data[$key];

            $this->$property = match ($type) {
                'string', 'int', 'float', 'bool' => settype($value, $type) ? $value : null,
                'object' => $class ? new $class($value) : $value,
                'array'  => ($class && is_array($value)) ? array_map(fn($item) => new $class($item), $value) : ($value ?? []),
                'raw'    => $value,
                default  => $value,
            };
        }
    }

    /**
     * Return an array mapping field names to their types or sub-entities.
     * Example:
     * return [
     *   'id' => ['key' => 'id', 'type' => 'string'],
     *   'project' => ['key' => 'project', 'type' => 'object', 'class' => ProjectEntity::class],
     *   'nodes' => ['key' => 'nodes', 'type' => 'array', 'class' => NodeEntity::class],
     * ];
     */
    abstract protected function getFields(): array;
}
