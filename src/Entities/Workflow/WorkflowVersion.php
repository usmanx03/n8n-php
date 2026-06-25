<?php

namespace UsmanZahid\N8n\Entities\Workflow;

use UsmanZahid\N8n\Entities\Entity;

class WorkflowVersion extends Entity {
    public ?string $versionId = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    /** @var Node[] */
    public array $nodes = [];

    /** @var array Connections between nodes */
    public array $connections = [];

    /** @var WorkflowSettings|null */
    public ?WorkflowSettings $settings = null;

    protected function getFields(): array {
        return [
            'versionId'   => ['key' => 'versionId', 'type' => 'string'],
            'name'        => ['key' => 'name', 'type' => 'string'],
            'description' => ['key' => 'description', 'type' => 'string'],
            'createdAt'   => ['key' => 'createdAt', 'type' => 'string'],
            'updatedAt'   => ['key' => 'updatedAt', 'type' => 'string'],
            'nodes'       => ['key' => 'nodes', 'type' => 'array', 'class' => Node::class],
            'connections' => ['key' => 'connections', 'type' => 'array'],
            'settings'    => ['key' => 'settings', 'type' => 'object', 'class' => WorkflowSettings::class],
        ];
    }
}
