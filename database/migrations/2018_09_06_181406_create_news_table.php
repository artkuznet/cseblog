<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_news', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('slug')->unique()->index()->nullable(false);
            $table->string('preview')->nullable(false);
            $table->timestamp('created_at')->index()->useCurrent();
            $table->string('header')->index()->nullable(false);
            $table->text('content')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_news');
    }
}
