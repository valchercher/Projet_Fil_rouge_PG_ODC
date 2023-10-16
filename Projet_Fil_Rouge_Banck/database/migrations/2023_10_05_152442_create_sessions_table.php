<?php

use App\Models\Salle;
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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->dateTime('heure_debut');
            $table->dateTime('heure_fin');
            $table->foreignId('cours_id')->constrained('cours')->cascadeOnDelete();
            $table->foreignIdFor(Salle::class)->constrained()->cascadeOnDelete()->nullable();
            $table->boolean('valider_session')->default(0);
            $table->boolean('demande_annuler')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
