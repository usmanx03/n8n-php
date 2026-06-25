<?php

namespace UsmanZahid\N8n\Clients\SubClients;

use UsmanZahid\N8n\Clients\ApiClient;
use UsmanZahid\N8n\Entities\Tag\Tag;
use UsmanZahid\N8n\Entities\Workflow\Workflow;
use UsmanZahid\N8n\Entities\Workflow\WorkflowList;
use UsmanZahid\N8n\Entities\Workflow\WorkflowVersion;
use UsmanZahid\N8n\Response\N8nResponse;
use UsmanZahid\N8n\Traits\PaginationTrait;

class WorkflowsClient extends ApiClient
{
    use PaginationTrait;

    /**
     * Create a new workflow.
     *
     * @param array|Workflow $payload Workflow data or a Workflow instance
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows
     */
    public function createWorkflow(array|Workflow $payload): N8nResponse
    {
        $response = $this->post('/workflows', $this->resolvePayload($payload));
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * List workflows with optional filters.
     *
     * Supported filters:
     * - active (bool)
     * - tags (string, comma-separated)
     * - name (string)
     * - projectId (string)
     * - excludePinnedData (bool)
     * - limit (int, max 250, default 100)
     * - cursor (string)
     *
     * @param array{
     *     active?: bool,
     *     tags?: string,
     *     name?: string,
     *     projectId?: string,
     *     excludePinnedData?: bool,
     *     limit?: int,
     *     cursor?: string
     * } $filters
     * @return N8nResponse<WorkflowList>
     *
     * API endpoint: GET /workflows
     */
    public function listWorkflows(array $filters = []): N8nResponse
    {
        $response = $this->get('/workflows', $filters);
        return $this->wrapEntity($response, WorkflowList::class);
    }

    /**
     * Fetch all workflows across all pages.
     *
     * @param array $filters Filters to apply (cursor is managed automatically)
     * @param int $limit Number of items per page (default 100, max 250)
     * @return N8nResponse<WorkflowList>
     */
    public function listWorkflowsAll(array $filters = [], int $limit = 100): N8nResponse
    {
        return $this->listAll(
            fn($limit, $cursor) => $this->listWorkflows(array_merge($filters, [
                'limit' => $limit,
                'cursor' => $cursor,
            ])),
            $limit
        );
    }

    /**
     * Append the next page of workflows to an existing WorkflowList.
     *
     * @param WorkflowList $list The WorkflowList to append to
     * @param array $filters Filters to apply
     * @param int $limit Number of items per page (default 100, max 250)
     * @return N8nResponse<WorkflowList>
     */
    public function appendNextWorkflowPage(WorkflowList $list, array $filters = [], int $limit = 100): N8nResponse
    {
        return $this->appendNextPage(
            $list,
            fn($l, $c) => $this->listWorkflows(array_merge($filters, [
                'limit' => $l,
                'cursor' => $c,
            ])),
            $limit
        );
    }

    /**
     * Get a workflow by ID.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @param bool $excludePinnedData Whether to exclude pinned data
     * @return N8nResponse<Workflow>
     *
     * API endpoint: GET /workflows/{id}
     */
    public function getWorkflow(string|Workflow $workflow, bool $excludePinnedData = false): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $response = $this->get("/workflows/{$id}", ['excludePinnedData' => $excludePinnedData]);
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Get a specific version of a workflow.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @param string $versionId Version ID
     * @return N8nResponse<WorkflowVersion>
     *
     * API endpoint: GET /workflows/{id}/{versionId}
     */
    public function getWorkflowVersion(string|Workflow $workflow, string $versionId): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $response = $this->get("/workflows/{$id}/{$versionId}");
        return $this->wrapEntity($response, WorkflowVersion::class);
    }

    /**
     * Update an existing workflow.
     *
     * Accepts either a raw array payload or a Workflow instance. When passing
     * a Workflow instance as the first argument (with no separate $id), the
     * instance's own ID is used.
     *
     * @param string|Workflow $workflow Workflow ID, or a Workflow instance (used as both ID source and payload)
     * @param array|Workflow $payload Fields to update — omit when $workflow is already the full object
     * @return N8nResponse<Workflow>
     *
     * API endpoint: PUT /workflows/{id}
     */
    public function updateWorkflow(string|Workflow $workflow, array|Workflow $payload = []): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $data = $this->resolvePayload($payload ?:$workflow);
        $response = $this->put("/workflows/{$id}", $data);
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Delete a workflow by ID.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @return N8nResponse<Workflow>
     *
     * API endpoint: DELETE /workflows/{id}
     */
    public function deleteWorkflow(string|Workflow $workflow): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $response = $this->delete("/workflows/{$id}");
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Activate (publish) a workflow.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @param array{versionId?: string, name?: string, description?: string} $payload Optional publish overrides
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows/{id}/activate
     */
    public function activateWorkflow(string|Workflow $workflow, array $payload = []): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $response = $this->post("/workflows/{$id}/activate", $payload);
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Deactivate a workflow.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows/{id}/deactivate
     */
    public function deactivateWorkflow(string|Workflow $workflow): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $response = $this->post("/workflows/{$id}/deactivate");
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Archive a workflow.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows/{id}/archive
     */
    public function archiveWorkflow(string|Workflow $workflow): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $response = $this->post("/workflows/{$id}/archive");
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Unarchive a workflow.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows/{id}/unarchive
     */
    public function unarchiveWorkflow(string|Workflow $workflow): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $response = $this->post("/workflows/{$id}/unarchive");
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Transfer a workflow to another project.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @param string $destinationProjectId Target project ID
     * @return N8nResponse<Workflow>
     *
     * API endpoint: PUT /workflows/{id}/transfer
     */
    public function transferWorkflow(string|Workflow $workflow, string $destinationProjectId): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $response = $this->put("/workflows/{$id}/transfer", [
            'destinationProjectId' => $destinationProjectId,
        ]);
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Get tags assigned to a workflow.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @return N8nResponse<Tag[]>
     *
     * API endpoint: GET /workflows/{id}/tags
     */
    public function getWorkflowTags(string|Workflow $workflow): N8nResponse
    {
        $id = $this->resolveId($workflow);
        return $this->wrapArray($this->get("/workflows/{$id}/tags"), Tag::class);
    }

    /**
     * Update tags assigned to a workflow.
     *
     * @param string|Workflow $workflow Workflow ID or instance
     * @param string[]|Tag[] $tags Array of tag IDs (strings) or Tag instances
     * @return N8nResponse<Tag[]>
     *
     * API endpoint: PUT /workflows/{id}/tags
     */
    public function updateWorkflowTags(string|Workflow $workflow, array $tags): N8nResponse
    {
        $id = $this->resolveId($workflow);
        $payload = array_map(fn($tag) => ['id' => $tag instanceof Tag ? $tag->id:$tag], $tags);
        return $this->wrapArray($this->put("/workflows/{$id}/tags", $payload), Tag::class);
    }
}
