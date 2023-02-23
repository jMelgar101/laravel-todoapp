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
        Schema::create('list_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_complete')->default(0);
            $table->timestamp('to_complete_by_date')->nullable();
            $table->timestamp('to_complete_by_time')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('todo_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->unsigned()->nullable()->cascadeOnDelete();
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
        Schema::dropIfExists('list_items');
    }
};
