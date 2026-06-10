<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('outstanding_amount', 12, 2)->default(0)->after('total_amount');
            $table->decimal('account_receivable', 12, 2)->default(0)->after('outstanding_amount');
            $table->enum('payment_status', ['pending', 'clear'])->default('pending')->after('account_receivable');
        });

        foreach (DB::table('invoices')->orderBy('id')->get() as $invoice) {
            DB::table('invoices')->where('id', $invoice->id)->update([
                'outstanding_amount' => $invoice->total_amount,
                'account_receivable' => $invoice->total_amount,
                'payment_status' => 'pending',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['outstanding_amount', 'account_receivable', 'payment_status']);
        });
    }
};
