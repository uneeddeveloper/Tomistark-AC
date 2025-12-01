<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'collected_by')) {
                $table->unsignedBigInteger('collected_by')->nullable()->index()->after('payment_method');
            }
            if (!Schema::hasColumn('payments', 'collected_at')) {
                $table->timestamp('collected_at')->nullable()->after('collected_by');
            }
            if (!Schema::hasColumn('payments', 'cod_received_amount')) {
                $table->unsignedBigInteger('cod_received_amount')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('payments', 'cod_proof')) {
                $table->string('cod_proof')->nullable()->after('payment_proof');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'cod_proof')) $table->dropColumn('cod_proof');
            if (Schema::hasColumn('payments', 'cod_received_amount')) $table->dropColumn('cod_received_amount');
            if (Schema::hasColumn('payments', 'collected_at')) $table->dropColumn('collected_at');
            if (Schema::hasColumn('payments', 'collected_by')) $table->dropColumn('collected_by');
        });
    }
};
