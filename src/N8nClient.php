<?php

namespace UsmanZahid\N8n;

use RuntimeException;
use UsmanZahid\N8n\Clients\SubClients\AuditClient;
use UsmanZahid\N8n\Clients\SubClients\CredentialsClient;
use UsmanZahid\N8n\Clients\SubClients\ExecutionsClient;
use UsmanZahid\N8n\Clients\SubClients\ProjectsClient;
use UsmanZahid\N8n\Clients\SubClients\SourceControlClient;
use UsmanZahid\N8n\Clients\SubClients\TagsClient;
use UsmanZahid\N8n\Clients\SubClients\UsersClient;
use UsmanZahid\N8n\Clients\SubClients\VariablesClient;
use UsmanZahid\N8n\Clients\SubClients\WorkflowsClient;
use UsmanZahid\N8n\Clients\WebhookClient;
use UsmanZahid\N8n\Enums\RequestMethod;
use UsmanZahid\N8n\Enums\WebhookMode;

class N8nClient
{
    private static ?string $baseUrl = null;
    private static ?string $apiKey = null;
    private static ?string $webhookUsername = null;
    private static ?string $webhookPassword = null;

    private static ?AuditClient $audit = null;
    private static ?CredentialsClient $credentials = null;
    private static ?ExecutionsClient $executions = null;
    private static ?WorkflowsClient $workflows = null;
    private static ?TagsClient $tags = null;
    private static ?UsersClient $users = null;
    private static ?VariablesClient $variables = null;
    private static ?ProjectsClient $projects = null;
    private static ?SourceControlClient $sourceControl = null;

    public static function connect(
        string $baseUrl,
        string $apiKey,
        ?string $webhookUsername = null,
        ?string $webhookPassword = null
    ): void {
        self::$baseUrl = $baseUrl;
        self::$apiKey = $apiKey;
        self::$webhookUsername = $webhookUsername;
        self::$webhookPassword = $webhookPassword;

        // Reset cached clients so a reconnect always picks up fresh credentials.
        self::$audit = null;
        self::$credentials = null;
        self::$executions = null;
        self::$workflows = null;
        self::$tags = null;
        self::$users = null;
        self::$variables = null;
        self::$projects = null;
        self::$sourceControl = null;
    }

    public static function webhook(
        WebhookMode $mode = WebhookMode::Production,
        RequestMethod $method = RequestMethod::Post
    ): WebhookClient {
        self::ensureWebhookConnected();
        return new WebhookClient(
            self::$baseUrl,
            $mode,
            $method,
            self::$webhookUsername,
            self::$webhookPassword
        );
    }

    public static function audit(): AuditClient
    {
        self::ensureConnected();
        return self::$audit ??= new AuditClient(self::$baseUrl, self::$apiKey);
    }

    public static function credentials(): CredentialsClient
    {
        self::ensureConnected();
        return self::$credentials ??= new CredentialsClient(self::$baseUrl, self::$apiKey);
    }

    public static function executions(): ExecutionsClient
    {
        self::ensureConnected();
        return self::$executions ??= new ExecutionsClient(self::$baseUrl, self::$apiKey);
    }

    public static function workflows(): WorkflowsClient
    {
        self::ensureConnected();
        return self::$workflows ??= new WorkflowsClient(self::$baseUrl, self::$apiKey);
    }

    public static function tags(): TagsClient
    {
        self::ensureConnected();
        return self::$tags ??= new TagsClient(self::$baseUrl, self::$apiKey);
    }

    public static function users(): UsersClient
    {
        self::ensureConnected();
        return self::$users ??= new UsersClient(self::$baseUrl, self::$apiKey);
    }

    public static function variables(): VariablesClient
    {
        self::ensureConnected();
        return self::$variables ??= new VariablesClient(self::$baseUrl, self::$apiKey);
    }

    public static function projects(): ProjectsClient
    {
        self::ensureConnected();
        return self::$projects ??= new ProjectsClient(self::$baseUrl, self::$apiKey);
    }

    public static function sourceControl(): SourceControlClient
    {
        self::ensureConnected();
        return self::$sourceControl ??= new SourceControlClient(self::$baseUrl, self::$apiKey);
    }

    private static function ensureConnected(): void
    {
        if (!self::$baseUrl || !self::$apiKey) {
            throw new RuntimeException("N8nClient not connected. Call connect() first with API credentials.");
        }
    }

    private static function ensureWebhookConnected(): void
    {
        if (!self::$baseUrl) {
            throw new RuntimeException("Webhook base URL not configured. Call connect() with webhook URL first.");
        }
    }
}
