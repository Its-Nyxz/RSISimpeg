    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_gapok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gol_id');
            $table->integer('masa_kerja')->nullable();
            $table->integer('nominal_gapok')->nullable();
            $table->timestamps(0);

            $table->foreign('gol_id')->references('id')->on('master_golongan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_gapok');
    }
};
