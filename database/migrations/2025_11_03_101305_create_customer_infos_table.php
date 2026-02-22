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
        Schema::create('customer_infos', function (Blueprint $table) {
            $table->id('CustomerInfoID'); // Primary key
            $table->string('CustomerCode')->unique();
            $table->string('CustomerName');
            $table->string('Business')->nullable();
            $table->string('FatherName')->nullable();
            $table->string('Address')->nullable();
            $table->string('Contact')->nullable();
            $table->string('NIDNumber')->nullable();
            $table->decimal('DownPayment', 12, 2)->nullable();
            $table->decimal('FinanceAmount', 12, 2)->nullable();
            $table->date('InvoiceDate')->nullable();
            $table->string('TTYCode')->nullable();
            $table->string('TTYName')->nullable();
            $table->string('BoxNo')->nullable();
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_infos');
    }
};
