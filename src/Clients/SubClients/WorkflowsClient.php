<?php

namespace UsmanZahid\N8n\Clients\SubClients;

use UsmanZahid\N8n\Clients\ApiClient;
use UsmanZahid\N8n\Entities\Tag\Tag;
use UsmanZahid\N8n\Entities\Workflow\Workflow;
use UsmanZahid\N8n\Entities\Workflow\WorkflowList;
use UsmanZahid\N8n\Entities\Workflow\WorkflowVersion;
use UsmanZahid\N8n\Response\N8nResponse;
use UsmanZahid\N8n\Traits\PaginationTrait;

class WorkflowsClient extends ApiClient {
    use PaginationTrait;

    /**
     * Create a new workflow.
     *
     * @param array $payload Workflow data (name, nodes, connections, settings, etc.)
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows
     */
    public function createWorkflow(array $payload): N8nResponse {
        $response = $this->post('/workflows', $payload);
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
    public function listWorkflows(array $filters = []): N8nResponse {
        $response = $this->get('/workflows', $filters);
        return $this->wrapEntity($response, WorkflowList::class);
    }

    /**
     * Fetch all workflows across all pages.
     *
     * @param array $filters Filters to apply (same as listWorkflows, cursor is managed automatically)
     * @param int $limit Number of items per page (default 100, max 250)
     * @return N8nResponse<WorkflowList>
     */
    public function listWorkflowsAll(array $filters = [], int $limit = 100): N8nResponse {
        return $this->listAll(
            fn($limit, $cursor) => $this->listWorkflows(array_merge($filters, [
                'limit'  => $limit,
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
    public function appendNextWorkflowPage(WorkflowList $list, array $filters = [], int $limit = 100): N8nResponse {
        return $this->appendNextPage(
            $list,
            fn($l, $c) => $this->listWorkflows(array_merge($filters, [
                'limit'  => $l,
                'cursor' => $c,
            ])),
            $limit
        );
    }

    /**
     * Get a workflow by ID.
     *
     * @param string $id Workflow ID
     * @param bool $excludePinnedData Whether to exclude pinned data
     * @return N8nResponse<Workflow>
     *
     * API endpoint: GET /workflows/{id}
     */
    public function getWorkflow(string $id, bool $excludePinnedData = false): N8nResponse {
        $response = $this->get("/workflows/{$id}", ['excludePinnedData' => $excludePinnedData]);
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Get a specific version of a workflow.
     *
     * @param string $id Workflow ID
     * @param string $versionId Version ID
     * @return N8nResponse<WorkflowVersion>
     *
     * API endpoint: GET /workflows/{id}/{versionId}
     */
    public function getWorkflowVersion(string $id, string $versionId): N8nResponse {
        $response = $this->get("/workflows/{$id}/{$versionId}");
        return $this->wrapEntity($response, WorkflowVersion::class);
    }

    /**
     * Update an existing workflow.
     *
     * @param string $id Workflow ID
     * @param array $payload Fields to update (name, nodes, connections, settings, etc.)
     * @return N8nResponse<Workflow>
     *
     * API endpoint: PUT /workflows/{id}
     */
    public function updateWorkflow(string $id, array $payload): N8nResponse {
        $response = $this->put("/workflows/{$id}", $payload);
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Delete a workflow by ID.
     *
     * @param string $id Workflow ID
     * @return N8nResponse<Workflow>
     *
     * API endpoint: DELETE /workflows/{id}
     */
    public function deleteWorkflow(string $id): N8nResponse {
        $response = $this->delete("/workflows/{$id}");
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Activate (publish) a workflow.
     *
     * @param string $id Workflow ID
     * @param array{versionId?: string, name?: string, description?: string} $payload Optional publish overrides
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows/{id}/activate
     */
    public function activateWorkflow(string $id, array $payload = []): N8nResponse {
        $response = $this->post("/workflows/{$id}/activate", $payload);
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Deactivate a workflow.
     *
     * @param string $id Workflow ID
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows/{id}/deactivate
     */
    public function deactivateWorkflow(string $id): N8nResponse {
        $response = $this->post("/workflows/{$id}/deactivate");
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Archive a workflow.
     *
     * @param string $id Workflow ID
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows/{id}/archive
     */
    public function archiveWorkflow(string $id): N8nResponse {
        $response = $this->post("/workflows/{$id}/archive");
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Unarchive a workflow.
     *
     * @param string $id Workflow ID
     * @return N8nResponse<Workflow>
     *
     * API endpoint: POST /workflows/{id}/unarchive
     */
    public function unarchiveWorkflow(string $id): N8nResponse {
        $response = $this->post("/workflows/{$id}/unarchive");
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Transfer a workflow to another project.
     *
     * @param string $id Workflow ID
     * @param string $destinationProjectId Target project ID
     * @return N8nResponse<Workflow>
     *
     * API endpoint: PUT /workflows/{id}/transfer
     */
    public function transferWorkflow(string $id, string $destinationProjectId): N8nResponse {
        $response = $this->put("/workflows/{$id}/transfer", [
            'destinationProjectId' => $destinationProjectId,
        ]);
        return $this->wrapEntity($response, Workflow::class);
    }

    /**
     * Get tags assigned to a workflow.
     *
     * @param string $id Workflow ID
     * @return N8nResponse<Tag[]>
     *
     * API endpoint: GET /workflows/{id}/tags
     */
    public function getWorkflowTags(string $id): N8nResponse {
        $response = $this->get("/workflows/{$id}/tags");
        return $this->wrapTagArray($response);
    }

    /**
     * Update tags assigned to a workflow.
     *
     * @param string $id Workflow ID
     * @param string[] $tagIds Array of tag IDs to assign
     * @return N8nResponse<Tag[]>
     *
     * API endpoint: PUT /workflows/{id}/tags
     */
    public function updateWorkflowTags(string $id, array $tagIds): N8nResponse {
        $payload = array_map(fn($tagId) => ['id' => $tagId], $tagIds);
        $response = $this->put("/workflows/{$id}/tags", $payload);
        return $this->wrapTagArray($response);
    }

    /**
     * Hydrate a raw tag array response into an N8nResponse containing Tag[].
     */
    private function wrapTagArray(array $raw): N8nResponse {
        $success = $raw['success'] ?? false;
        $code    = $raw['code'] ?? ($success ? 200 : 500);
        $message = $raw['message'] ?? ($success ? 'Success' : 'Error');

        if (!$success) {
            return new N8nResponse(false, null, $message, $code);
        }

        $data = $raw['data'] ?? [];
        $tags = is_array($data) ? array_map(fn($t) => new Tag($t), $data) : [];

        return new N8nResponse(true, $tags, $message, $code);
    }
}
