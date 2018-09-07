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

            $table->string('slug')->unique()->nullable(false);
            $table->index('slug');

            $table->string('preview')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->index('created_at');

            $table->string('header')->nullable(false);
            $table->index('header');

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
