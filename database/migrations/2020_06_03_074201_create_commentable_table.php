<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commentable', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->integer('commentable_id');// dice el id del usuario/producto sobre el que se ha hecho el comentairo 
            $table->integer('commentable_type');//dice si se ha hecho sobre un producto o un usuario
            $table->integer('user_id');//el autor del comentario
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
        Schema::dropIfExists('commentable');
    }
}
