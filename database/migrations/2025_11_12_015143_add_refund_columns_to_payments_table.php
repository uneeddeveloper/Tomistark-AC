<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Status untuk alur refund: pending, approved, rejected
            $table->string('refund_status')->nullable()->after('verification_status');
            $table->text('refund_reason')->nullable()->after('refund_status');
            $table->decimal('refund_amount', 15, 2)->nullable()->after('refund_reason');
            $table->text('refund_admin_notes')->nullable()->after('refund_amount');
            $table->timestamp('refund_processed_at')->nullable()->after('refund_admin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'refund_status',
                'refund_reason',
                'refund_amount',
                'refund_admin_notes',
                'refund_processed_at'
            ]);
        });
    }
};
