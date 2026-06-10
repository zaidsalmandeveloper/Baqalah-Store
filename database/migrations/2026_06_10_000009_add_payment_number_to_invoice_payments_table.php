<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->string('payment_number')->nullable()->unique()->after('id');
        });

        $prefix = 'RCP-'.date('Y').'-';
        $counter = 1;

        foreach (DB::table('invoice_payments')->orderBy('id')->get() as $payment) {
            DB::table('invoice_payments')->where('id', $payment->id)->update([
                'payment_number' => $prefix.str_pad((string) $counter, 4, '0', STR_PAD_LEFT),
            ]);
            $counter++;
        }
    }

    public function down(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn('payment_number');
        });
    }
};
