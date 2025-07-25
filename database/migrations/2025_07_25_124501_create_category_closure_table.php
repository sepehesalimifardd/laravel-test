<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('category_closure', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ancestor_id')
                ->constrained('categories')
                ->onDelete('cascade');

            $table->foreignId('descendant_id')
                ->constrained('categories')
                ->onDelete('cascade');

            $table->unsignedInteger('depth');

            $table->index(['ancestor_id', 'descendant_id']);
            $table->index('depth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_closure');
    }
};
