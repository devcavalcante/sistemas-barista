<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstabelecimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estabelecimentos', function (Blueprint $table) {
            $table->id();
            $table->string('razao_social');
            $table->boolean('possui_bilhar');
            $table->boolean('possui_happyHour');
            $table->boolean('possui_delivery');
            $table->boolean('possui_musica_ao_vivo');
            $table->boolean('paga_cover');
            $table->time('hora_abertura');
            $table->time('hora_fechamento');
            $table->string('CEP');
            $table->string('rua');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->string('celular');
            $table->integer('numero');
            $table->longText('outras_informacoes')->nullable();
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('estabelecimentos');
    }
}
