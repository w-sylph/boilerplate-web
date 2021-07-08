<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSampleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->unsigned()->nullable();

            $table->string('name');
            $table->longText('description')->nullable();

            $table->text('file_path')->nullable();

            $table->text('data')->nullable();

            $table->text('reason')->nullable();
            $table->string('status')->default('PENDING');

            $table->string('color')->nullable();

            $table->datetime('date')->nullable();
            $table->text('dates')->nullable();

            $table->string('telephone_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('zip_code')->nullable();

            $table->bigInteger('order_column')->unsigned()->default(0);

            $table->softDeletes();
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
        Schema::dropIfExists('sample_items');
    }
}
