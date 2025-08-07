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
        Schema::table('cupons', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('validade');
            $table->unsignedInteger('uso_maximo')->nullable()->after('ativo');
            $table->unsignedInteger('uso_count')->default(0)->after('uso_maximo');
            $table->dateTime('valid_from')->nullable()->after('pct_desc');
            $table->dateTime('valid_to')->nullable()->after('validade');
        });

        Schema::table('pedido_cupons', function (Blueprint $table) {
            $table->decimal('desconto_aplicado', 10, 2)
                ->nullable()
                ->after('cupom_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_cupons', function (Blueprint $table) {
            $table->dropColumn('desconto_aplicado');
        });

        Schema::table('cupons', function (Blueprint $table) {
            $table->dropColumn([
                'valid_to',
                'valid_from',
                'uso_count',
                'uso_maximo',
                'ativo',
            ]);
        });
    }
};
