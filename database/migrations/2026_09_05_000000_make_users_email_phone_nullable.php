<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Driver creation is now a two-step flow: the create form saves a
     * driver from DL data alone (no email/phone yet), and the follow-up
     * "Driver KYC" page collects email/phone before the account is usable.
     * Raw SQL is used instead of Blueprint::change() since doctrine/dbal
     * isn't installed in this project.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
        DB::statement('ALTER TABLE users MODIFY phone VARCHAR(255) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY phone VARCHAR(255) NOT NULL');
    }
};
