<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up()
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
        $table->enum('status', ['à faire', 'en cours', 'terminé'])->default('à faire');
        $table->foreignId('project_id')->constrained()->onDelete('cascade');
        $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->date('due_date')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('tasks');
}

};
