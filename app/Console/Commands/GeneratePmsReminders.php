<?php

namespace App\Console\Commands;

use App\Services\UnifiedPmsService;
use Illuminate\Console\Command;

class GeneratePmsReminders extends Command
{
    protected $signature = 'pms:generate-reminders {--days=7 : Number of days ahead to check}';

    protected $description = 'Generate payment, lease, and maintenance reminders';

    public function handle(UnifiedPmsService $service): int
    {
        $days = (int) $this->option('days');
        $result = $service->generateReminders($days);

        $this->info('Generated reminders: ' . $result['notifications_created']);

        return self::SUCCESS;
    }
}
