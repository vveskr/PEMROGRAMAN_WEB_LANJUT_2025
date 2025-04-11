<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('m_supplier', function (Blueprint $table) {
            $table->id('supplier_id'); // id_supplier (primary key)
            $table->string('supplier_kode', 10)->unique();
            $table->string('supplier_nama', 100);
            $table->text('supplier_alamat');
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_supplier');
    }
};