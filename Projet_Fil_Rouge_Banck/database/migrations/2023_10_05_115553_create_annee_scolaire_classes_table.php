<?php

use App\Models\Annee;
use App\Models\Classe;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('annee_scolaire_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Annee::class)->constrained()->cascadeOnDelte();
            $table->foreignIdFor(Classe::class)->constrained()->cascadeOnDelte();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annee_scolaire_classes');
    }
};
