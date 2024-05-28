<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id(); // Unsigned BigInt auto-increment
            $table->string('poster_path', 40)->nullable();
            $table->string('backdrop_path', 40)->nullable();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade'); // Foreign key
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
}
