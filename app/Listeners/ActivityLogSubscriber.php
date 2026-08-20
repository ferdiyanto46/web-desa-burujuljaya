<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\ContactInformation;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tourism;
use App\Models\Umkm;
use App\Models\User;
use App\Models\VillagePage;
use App\Models\VillageService;
use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class ActivityLogSubscriber
{
    /**
     * Models that should be logged.
     */
    private const LOGGABLE_MODELS = [
        Article::class,
        Announcement::class,
        Gallery::class,
        Tourism::class,
        Umkm::class,
        Product::class,
        VillagePage::class,
        VillageService::class,
        ContactInformation::class,
        Faq::class,
        HomepageSection::class,
        User::class,
        Role::class,
        Permission::class,
        Setting::class,
    ];

    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    /**
     * Handle eloquent events.
     */
    public function handleEloquentEvent(string $eventName, array $data): void
    {
        if (empty($data) || ! ($data[0] instanceof Model)) {
            return;
        }

        $model = $data[0];

        if (! in_array(get_class($model), self::LOGGABLE_MODELS, true)) {
            return;
        }

        $event = Str::afterLast($eventName, '.');
        $eventParts = explode(':', $event);
        $event = $eventParts[0];

        $validEvents = ['created', 'updated', 'deleted', 'restored', 'forceDeleted'];
        if (! in_array($event, $validEvents, true)) {
            return;
        }

        // For updates, check if there are actual changes
        if ($event === 'updated' && ! $model->wasChanged()) {
            return;
        }

        $className = class_basename($model);
        $description = "{$className} was {$event}";

        $properties = [];
        if ($event === 'updated') {
            $properties = [
                'old' => $model->getOriginal(),
                'attributes' => $model->getChanges(),
            ];
        } elseif ($event === 'created') {
            $properties = [
                'attributes' => $model->getAttributes(),
            ];
        }

        $this->activityLogService->log(
            event: $event,
            description: $description,
            subject: $model,
            properties: $properties
        );
    }

    public function handleLogin(Login $event): void
    {
        $this->activityLogService->log(
            event: 'login',
            description: 'User logged in',
            causer: $event->user instanceof Model ? $event->user : null
        );
    }

    public function handleLogout(Logout $event): void
    {
        $this->activityLogService->log(
            event: 'logout',
            description: 'User logged out',
            causer: $event->user instanceof Model ? $event->user : null
        );
    }

    public function handleFailedLogin(Failed $event): void
    {
        $this->activityLogService->log(
            event: 'failed_login',
            description: "Failed login attempt for user: {$event->credentials['email']}",
            properties: ['credentials' => $event->credentials]
        );
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        // Eloquent events
        $events->listen('eloquent.*', [self::class, 'handleEloquentEvent']);

        // Auth events
        $events->listen(Login::class, [self::class, 'handleLogin']);
        $events->listen(Logout::class, [self::class, 'handleLogout']);
        $events->listen(Failed::class, [self::class, 'handleFailedLogin']);
    }
}
