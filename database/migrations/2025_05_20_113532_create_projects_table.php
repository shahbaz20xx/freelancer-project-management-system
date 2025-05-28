<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recruiter_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('project_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_type_id')->constrained()->onDelete('cascade');
            $table->text('responsibility')->nullable();
            $table->text('qualifications')->nullable();
            $table->string('experience');
            $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open');
            $table->decimal('budget', 10, 2)->nullable();
            $table->enum('billing_type', ['project', 'task']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
