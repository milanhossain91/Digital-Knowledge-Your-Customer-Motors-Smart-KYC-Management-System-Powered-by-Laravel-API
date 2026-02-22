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
        Schema::create('attachments', function (Blueprint $table) {
                 $table->id('AttachmentID');

                $table->unsignedBigInteger('CustomerInfoID');
                $table->unsignedBigInteger('AttachmentTypeID');

                $table->string('AttachmentRaw');
                $table->string('AttachmentOriginal');
                $table->string('CreatedBy')->nullable();
                $table->timestamp('CreatedAt')->useCurrent();

                // Explicit foreign key references
                $table->foreign('CustomerInfoID')
                    ->references('CustomerInfoID')
                    ->on('customer_infos')
                    ->onDelete('cascade');

                $table->foreign('AttachmentTypeID')
                    ->references('AttachmentTypeID')
                    ->on('attachment_types')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
