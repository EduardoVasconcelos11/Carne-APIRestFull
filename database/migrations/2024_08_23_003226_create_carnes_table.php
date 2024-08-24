<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarnesTable extends Migration
{
    public function up()
    {
        Schema::create('carnes', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_total', 10, 2);
            $table->decimal('valor_entrada', 10, 2)->nullable();
            $table->integer('qtd_parcelas');
            $table->date('data_primeiro_vencimento');
            $table->string('periodicidade');
            $table->timestamps();
        });

        Schema::create('parcelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carne_id')->constrained('carnes')->onDelete('cascade');
            $table->date('data_vencimento');
            $table->float('valor');
            $table->integer('numero');
            $table->boolean('entrada')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parcelas');
        Schema::dropIfExists('carnes');
    }
}
