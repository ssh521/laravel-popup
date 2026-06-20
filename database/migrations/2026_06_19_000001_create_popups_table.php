<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type', 40)->default('popup')->index();
            $table->string('status', 40)->default('draft')->index();
            $table->string('display_title')->nullable();
            $table->longText('body')->nullable();
            $table->string('image_disk')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_target', 20)->default('_self');
            $table->string('link_rel')->nullable();
            $table->string('position', 40)->default('center')->index();
            $table->string('device', 40)->default('all')->index();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();
            $table->json('include_paths')->nullable();
            $table->json('exclude_paths')->nullable();
            $table->string('close_policy', 40)->default('close');
            $table->unsignedInteger('close_duration')->nullable();
            $table->integer('priority')->default(0)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popups');
    }
};
