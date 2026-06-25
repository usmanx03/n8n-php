<?php

namespace UsmanZahid\N8n\Entities\Workflow;

use UsmanZahid\N8n\Entities\Entity;
use UsmanZahid\N8n\Entities\Tag\Tag;

class Workflow extends Entity
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?bool $active = null;
    public ?bool $isArchived = null;
    public ?string $versionId = null;
    public ?int $triggerCount = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    /** @var Node[] */
    public array $nodes = [];

    /** @var array Connections between nodes (free-form graph structure) */
    public array $connections = [];

    /** @var array Node groups */
    public array $nodeGroups = [];

    /** @var WorkflowSettings|null */
    public ?WorkflowSettings $settings = null;

    /** @var mixed Static data attached to the workflow (string or object) */
    public mixed $staticData = null;

    /** @var array Pin data keyed by node name */
    public array $pinData = [];

    /** @var array Meta information */
    public array $meta = [];

    /** @var Shared[] Sharing/project relationships */
    public array $shared = [];

    /** @var Tag[] */
    public array $tags = [];

    /** @var WorkflowVersion|null Active published version snapshot */
    public ?WorkflowVersion $activeVersion = null;

    protected function getFields(): array
    {
        return [
            'id' => ['key' => 'id', 'type' => 'string'],
            'name' => ['key' => 'name', 'type' => 'string'],
            'description' => ['key' => 'description', 'type' => 'string'],
            'active' => ['key' => 'active', 'type' => 'bool'],
            'isArchived' => ['key' => 'isArchived', 'type' => 'bool'],
            'versionId' => ['key' => 'versionId', 'type' => 'string'],
            'triggerCount' => ['key' => 'triggerCount', 'type' => 'int'],
            'createdAt' => ['key' => 'createdAt', 'type' => 'string'],
            'updatedAt' => ['key' => 'updatedAt', 'type' => 'string'],
            'nodes' => ['key' => 'nodes', 'type' => 'array', 'class' => Node::class],
            'connections' => ['key' => 'connections', 'type' => 'array'],
            'nodeGroups' => ['key' => 'nodeGroups', 'type' => 'array'],
            'settings' => ['key' => 'settings', 'type' => 'object', 'class' => WorkflowSettings::class],
            'staticData' => ['key' => 'staticData', 'type' => 'raw'],
            'pinData' => ['key' => 'pinData', 'type' => 'array'],
            'meta' => ['key' => 'meta', 'type' => 'array'],
            'shared' => ['key' => 'shared', 'type' => 'array', 'class' => Shared::class],
            'tags' => ['key' => 'tags', 'type' => 'array', 'class' => Tag::class],
            'activeVersion' => ['key' => 'activeVersion', 'type' => 'object', 'class' => WorkflowVersion::class],
        ];
    }
}
