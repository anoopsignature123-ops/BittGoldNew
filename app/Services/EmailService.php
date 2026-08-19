<?php

namespace App\Services;

use App\Models\EmailTemplate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send an active database template by its unique key.
     *
     * Available options: cc, bcc, reply_to, from, from_name, queue.
     */
    public function send(string $templateKey, $to, array $data = [], array $options = []): bool
    {
        $template = EmailTemplate::active()->where('key', $templateKey)->first();

        if (! $template) {
            Log::warning('EmailService: active email template was not found', ['template' => $templateKey]);
            return false;
        }

        $data = array_merge([
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'support_email' => config('mail.from.address'),
        ], $data);

        $subject = $this->render($template->subject, $data, $templateKey);
        $body = $this->render($template->body, $data, $templateKey);

        $apiKey = config('services.zeptomail.api_key') ?: env('ZEPTO_MAIL_API_KEY');
        $apiBase = rtrim(config('services.zeptomail.api_base', 'https://api.zeptomail.in/v1.1/email'), '/');
        $verify = config('services.zeptomail.verify', true);

        if (empty($apiKey)) {
            Log::error('EmailService: ZeptoMail API key not configured');
            return false;
        }

        $recipients = [];
        if (is_string($to)) {
            $recipients[] = ['email' => $to];
        } elseif (is_array($to)) {
            foreach ($to as $k => $v) {
                if (is_int($k)) {
                    $recipients[] = ['email' => $v];
                } else {
                    $recipients[] = ['email' => $k, 'name' => $v];
                }
            }
        }

        $recipients = array_values(array_filter($recipients, fn (array $recipient) => filter_var($recipient['email'], FILTER_VALIDATE_EMAIL)));

        if (empty($recipients)) {
            Log::warning('EmailService: no valid recipient provided', ['template' => $templateKey]);
            return false;
        }
        $payload = [
            "from" => [
                "address" => $options['from'] ?? config('mail.from.address'),
                "name" => $options['from_name'] ?? config('mail.from.name'),
            ],
            "to" => array_map(function ($recipient) {
                return [
                    "email_address" => [
                        "address" => $recipient['email'],
                        "name" => $recipient['name'] ?? ''
                    ]
                ];
            }, $recipients),
            "subject" => $subject,
            "htmlbody" => $body,
        ];

        try {
            Log::info('EmailService: attempting to send email', [
                'template' => $templateKey,
                'to' => array_column($recipients, 'email'),
                'subject' => $subject,
            ]);

            $res = Http::withHeaders([
                'Authorization' => 'Zoho-enczapikey ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions(['verify' => $verify])->post($apiBase, $payload);

            if ($res->successful()) {
                Log::info('EmailService: email sent successfully via ZeptoMail', [
                    'template' => $templateKey,
                    'to' => array_column($recipients, 'email'),
                    'subject' => $subject,
                    'status' => $res->status(),
                ]);
                return true;
            }

            Log::error('EmailService: failed sending email via ZeptoMail', [
                'template' => $templateKey,
                'to' => array_column($recipients, 'email'),
                'status' => $res->status(),
                'response' => $res->json(),
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('EmailService: exception when sending email via ZeptoMail', [
                'template' => $templateKey,
                'to' => array_column($recipients, 'email'),
                'exception' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /** Render {{ variable }} placeholders with escaped values. */
    public function render(string $content, array $data, string $templateKey = 'email'): string
    {
        return (string) preg_replace_callback('/\{\{\s*([a-zA-Z0-9_.-]+)\s*\}\}/', function (array $match) use ($data, $templateKey) {
            $key = $match[1];

            if (!Arr::has($data, $key)) {
                throw new \RuntimeException("Missing email variable [{$key}] for template [{$templateKey}].");
            }

            $value = Arr::get($data, $key);
            if (!is_scalar($value) && $value !== null) {
                throw new \RuntimeException("Email variable [{$key}] must be a scalar value.");
            }

            return e((string) $value);
        }, $content);
    }
}