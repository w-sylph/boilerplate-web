<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_records', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('parent_id')->nullable();
            $table->string('parent_type')->nullable();

            $table->string('name')->nullable();
            $table->string('size')->nullable();
            $table->string('mime')->nullable();
            $table->string('extension')->nullable();
            $table->string('md5')->nullable();

            $table->text('file_path');

            $table->bigInteger('order_column')->unsigned()->default(0);

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
        Schema::dropIfExists('file_records');
    }
}
