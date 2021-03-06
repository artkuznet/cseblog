<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageHasTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('t_image_has_tag', function (Blueprint $table) {
            $table->uuid('image_guid')->foreign('image_guid')->references('guid')->on('t_images');
            $table->integer('tag_id')->foreign('tag_id')->references('id')->on('t_tags');
            $table->primary(['image_guid', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('t_image_has_tag');
    }
}
