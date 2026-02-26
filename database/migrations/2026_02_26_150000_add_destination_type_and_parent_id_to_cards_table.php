<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('destination_type', 20)->default('url')->after('description');
            $table->unsignedBigInteger('parent_id')->nullable()->after('destination_type');

            $table->index('destination_type');
            $table->index('parent_id');
            $table->foreign('parent_id')->references('id')->on('cards')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['destination_type']);
            $table->dropIndex(['parent_id']);
            $table->dropColumn(['destination_type', 'parent_id']);
        });
    }
};
