<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('source_id')->constrained('sources')->index();
            $table->string('category_id')->constrained('categories')->index();
            $table->string('author')->nullable();
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('url');
            $table->text('url_to_image')->nullable();
            $table->timestamp('published_at');
            $table->longText('content')->nullable();
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
        Schema::dropIfExists('articles');
    }
}
