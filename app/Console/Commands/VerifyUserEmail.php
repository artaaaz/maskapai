<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class VerifyUserEmail extends Command
{
    protected $signature = 'user:verify {email}';
    protected $description = 'Verify user email manually';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email {$email} tidak ditemukan!");
            return 1;
        }

        $user->email_verified_at = now();
        $user->save();

        $this->info("✅ Email {$email} berhasil diverifikasi!");
        return 0;
    }
}