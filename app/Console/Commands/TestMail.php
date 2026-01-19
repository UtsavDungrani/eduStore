<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mail {email? : The email to send the test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the mail configuration by sending a simple email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? config('mail.from.address');

        if (!$email) {
            $this->error('No email provided and MAIL_FROM_ADDRESS is not set.');
            return 1;
        }

        $this->info("Attempting to send test email to: {$email}...");
        $this->info("Using mailer: " . config('mail.default'));
        $this->info("Host: " . config('mail.mailers.smtp.host'));
        $this->info("Port: " . config('mail.mailers.smtp.port'));
        $this->info("Username: " . config('mail.mailers.smtp.username'));

        try {
            Mail::raw('This is a test email from your Laravel application to verify SMTP settings.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - SMTP Configuration Verified');
            });

            $this->info("✅ Email sent successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email.");
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
