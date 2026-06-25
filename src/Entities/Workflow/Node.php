<?php

namespace UsmanZahid\N8n\Entities\Workflow;

use UsmanZahid\N8n\Entities\Entity;

class Node extends Entity
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $webhookId = null;
    public ?string $type = null;
    public ?float $typeVersion = null;
    public ?bool $disabled = null;
    public ?bool $executeOnce = null;
    public ?bool $alwaysOutputData = null;
    public ?bool $retryOnFail = null;
    public ?int $maxTries = null;
    public ?int $waitBetweenTries = null;
    public ?bool $notesInFlow = null;
    public ?string $notes = null;
    public ?string $onError = null;
    public array $position = [];
    public array $parameters = [];
    /** @var array<string, array{id: string, name: string}> Credential references keyed by credential type */
    public array $credentials = [];
    public array $customTelemetryTags = [];
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    protected function getFields(): array
    {
        return [
            'id' => ['key' => 'id', 'type' => 'string'],
            'name' => ['key' => 'name', 'type' => 'string'],
            'webhookId' => ['key' => 'webhookId', 'type' => 'string'],
            'type' => ['key' => 'type', 'type' => 'string'],
            'typeVersion' => ['key' => 'typeVersion', 'type' => 'float'],
            'disabled' => ['key' => 'disabled', 'type' => 'bool'],
            'executeOnce' => ['key' => 'executeOnce', 'type' => 'bool'],
            'alwaysOutputData' => ['key' => 'alwaysOutputData', 'type' => 'bool'],
            'retryOnFail' => ['key' => 'retryOnFail', 'type' => 'bool'],
            'maxTries' => ['key' => 'maxTries', 'type' => 'int'],
            'waitBetweenTries' => ['key' => 'waitBetweenTries', 'type' => 'int'],
            'notesInFlow' => ['key' => 'notesInFlow', 'type' => 'bool'],
            'notes' => ['key' => 'notes', 'type' => 'string'],
            'onError' => ['key' => 'onError', 'type' => 'string'],
            'position' => ['key' => 'position', 'type' => 'array'],
            'parameters' => ['key' => 'parameters', 'type' => 'array'],
            'credentials' => ['key' => 'credentials', 'type' => 'array'],
            'customTelemetryTags' => ['key' => 'customTelemetryTags', 'type' => 'array'],
            'createdAt' => ['key' => 'createdAt', 'type' => 'string'],
            'updatedAt' => ['key' => 'updatedAt', 'type' => 'string'],
        ];
    }
}
