<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviewsArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previews_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('previews_id');
            $table->index('previews_id');
            $table->foreign('previews_id')->references('id')->on('previews')->onDelete('cascade');

            $table->string('day');
            $table->string('date');
            $table->string('timestamp');
            $table->string('from');
            $table->string('to');
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
        Schema::dropIfExists('previews_articles');
    }
}
