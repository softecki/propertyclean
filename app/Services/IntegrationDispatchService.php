<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class IntegrationDispatchService
{
    public function dispatch(string $channel, string $recipient, ?string $subject, string $message, int $parentId, array $context = []): array
    {
        $channel = strtolower($channel);
        if ($channel === 'email') {
            return $this->dispatchEmail($recipient, $subject, $message, $parentId, $context);
        }

        // SMS/WhatsApp can be wired to provider clients later.
        $logId = DB::table('integration_logs')->insertGetId([
            'parent_id' => $parentId,
            'integration_type' => $channel,
            'provider' => $context['provider'] ?? null,
            'reference_type' => $context['reference_type'] ?? 'notification',
            'reference_id' => $context['reference_id'] ?? null,
            'status' => 'pending_provider',
            'message' => 'Queued for provider integration',
            'payload' => json_encode([
                'recipient' => $recipient,
                'subject' => $subject,
                'message' => $message,
                'context' => $context,
            ]),
            'response' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['status' => 'queued', 'integration_log_id' => $logId];
    }

    public function logBankEvent(int $parentId, string $referenceType, int $referenceId, array $payload): void
    {
        DB::table('integration_logs')->insert([
            'parent_id' => $parentId,
            'integration_type' => 'banking',
            'provider' => $payload['bank_name'] ?? null,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'status' => 'pending_provider',
            'message' => 'Local control number created; awaiting bank API integration',
            'payload' => json_encode($payload),
            'response' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function dispatchEmail(string $recipient, ?string $subject, string $message, int $parentId, array $context = []): array
    {
        try {
            Mail::raw($message, function ($mail) use ($recipient, $subject) {
                $mail->to($recipient)->subject($subject ?: 'Notification');
            });

            $logId = DB::table('integration_logs')->insertGetId([
                'parent_id' => $parentId,
                'integration_type' => 'email',
                'provider' => config('mail.default'),
                'reference_type' => $context['reference_type'] ?? 'notification',
                'reference_id' => $context['reference_id'] ?? null,
                'status' => 'sent',
                'message' => 'Email sent from system',
                'payload' => json_encode([
                    'recipient' => $recipient,
                    'subject' => $subject,
                    'message' => $message,
                    'context' => $context,
                ]),
                'response' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return ['status' => 'sent', 'integration_log_id' => $logId];
        } catch (\Throwable $e) {
            $logId = DB::table('integration_logs')->insertGetId([
                'parent_id' => $parentId,
                'integration_type' => 'email',
                'provider' => config('mail.default'),
                'reference_type' => $context['reference_type'] ?? 'notification',
                'reference_id' => $context['reference_id'] ?? null,
                'status' => 'failed',
                'message' => $e->getMessage(),
                'payload' => json_encode([
                    'recipient' => $recipient,
                    'subject' => $subject,
                    'message' => $message,
                    'context' => $context,
                ]),
                'response' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return ['status' => 'failed', 'integration_log_id' => $logId];
        }
    }
}
