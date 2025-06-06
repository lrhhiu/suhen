<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->change();
            }
            $table->string('title', 10)->nullable()->after('id');
            $table->string('first_name', 50)->after('title');
            $table->string('other_name', 50)->nullable()->after('first_name');
            $table->string('username', 50)->unique()->after('other_name');
            $table->string('nic', 12)->nullable()->unique()->after('username');
            $table->date('date_of_birth')->nullable()->after('nic');
            $table->string('gender', 10)->nullable()->after('date_of_birth');
            $table->string('designation', 50)->nullable()->after('gender');
            $table->unsignedInteger('default_role_id')->nullable()->after('designation');
            $table->string('telephone', 20)->nullable()->after('password');
            $table->timestamp('last_seen_at')->nullable()->after('remember_token');
            $table->string('online_status', 20)->nullable()->after('last_seen_at');
            $table->boolean('is_locked')->default(false)->after('online_status');
            $table->string('signature_path', 150)->nullable()->after('is_locked');
            $table->boolean('is_active')->default(true)->after('signature_path');

            // Ensure updated_at exists before trying to place columns after it.
            // If base migration doesn't add timestamps, these 'after' calls might fail or misposition.
            // Standard Laravel 'users' table has timestamps.
            if (Schema::hasColumn('users', 'updated_at')) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            } else { // Fallback if no timestamps
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign keys first
            // Check if columns exist before trying to drop foreign keys on them
            if (Schema::hasColumn('users', 'created_by')) {
                $table->dropForeign(['created_by']);
            }
            if (Schema::hasColumn('users', 'updated_by')) {
                $table->dropForeign(['updated_by']);
            }

            $columnsToDrop = [
                'title', 'first_name', 'other_name', 'username', 'nic', 'date_of_birth',
                'gender', 'designation', 'default_role_id', 'telephone', 'last_seen_at',
                'online_status', 'is_locked', 'signature_path', 'is_active',
                'created_by', 'updated_by'
            ];

            // Drop only columns that exist
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Restore 'name' column if it was dropped and is desired
            // if (!Schema::hasColumn('users', 'name')) {
            //     $table->string('name')->after('id'); // Or appropriate position
            // }

            // Revert 'email' to not nullable if it was originally so.
            // Default Laravel users table has 'email' as not nullable.
            // if (Schema::hasColumn('users', 'email')) {
            //    $table->string('email')->nullable(false)->change();
            // }
        });
    }
};
