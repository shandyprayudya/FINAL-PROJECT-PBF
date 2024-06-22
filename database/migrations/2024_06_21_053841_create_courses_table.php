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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('course_category_id')->index();
            $table->unsignedBigInteger('teacher_id')->index();
            $table->integer('lesson')->nullable();
            $table->string('course_code')->nullable();
            $table->string('image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('thumbnail_video')->nullable();
            $table->integer('status')->default(0)->comment('0 pending, 1 inactive, 2 active');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('course_category_id')->references('id')->on('course_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
