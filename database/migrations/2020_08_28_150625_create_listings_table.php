<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();

            $table->string('name', 127)->nullable(false);

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });

        $this->updateTasksTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }

    private function updateTasksTable()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('listing_id')->nullable(true)->constrained()->onDelete('cascade');
        });
    }
}
