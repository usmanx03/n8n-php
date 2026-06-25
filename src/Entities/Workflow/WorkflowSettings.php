<?php

namespace UsmanZahid\N8n\Entities\Workflow;

use UsmanZahid\N8n\Entities\Entity;

class WorkflowSettings extends Entity {
    public ?string $errorWorkflow = null;
    public ?string $executionOrder = null;
    public ?int $executionTimeout = null;
    public ?string $saveDataErrorExecution = null;
    public ?string $saveDataSuccessExecution = null;
    public ?bool $saveExecutionProgress = null;
    public ?bool $saveManualExecutions = null;
    public ?string $timezone = null;
    public ?string $callerPolicy = null;
    public ?string $callerIds = null;
    public ?float $timeSavedPerExecution = null;
    public ?string $redactionPolicy = null;
    public ?bool $availableInMCP = null;
    public array $customTelemetryTags = [];

    protected function getFields(): array {
        return [
            'errorWorkflow'           => ['key' => 'errorWorkflow', 'type' => 'string'],
            'executionOrder'          => ['key' => 'executionOrder', 'type' => 'string'],
            'executionTimeout'        => ['key' => 'executionTimeout', 'type' => 'int'],
            'saveDataErrorExecution'  => ['key' => 'saveDataErrorExecution', 'type' => 'string'],
            'saveDataSuccessExecution'=> ['key' => 'saveDataSuccessExecution', 'type' => 'string'],
            'saveExecutionProgress'   => ['key' => 'saveExecutionProgress', 'type' => 'bool'],
            'saveManualExecutions'    => ['key' => 'saveManualExecutions', 'type' => 'bool'],
            'timezone'                => ['key' => 'timezone', 'type' => 'string'],
            'callerPolicy'            => ['key' => 'callerPolicy', 'type' => 'string'],
            'callerIds'               => ['key' => 'callerIds', 'type' => 'string'],
            'timeSavedPerExecution'   => ['key' => 'timeSavedPerExecution', 'type' => 'float'],
            'redactionPolicy'         => ['key' => 'redactionPolicy', 'type' => 'string'],
            'availableInMCP'          => ['key' => 'availableInMCP', 'type' => 'bool'],
            'customTelemetryTags'     => ['key' => 'customTelemetryTags', 'type' => 'array'],
        ];
    }
}
