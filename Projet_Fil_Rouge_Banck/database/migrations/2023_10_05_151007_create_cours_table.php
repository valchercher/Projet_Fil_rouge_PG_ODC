<?php

use App\Models\Annee;
use App\Models\Semestre;
use App\Models\ModuleUser;
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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->dateTime('nb_heure_global');
            $table->foreignId('annee_scolaire_semestre_id')->constrained('annee_scolaire_semestres')->cascadeOnDelelte();
            $table->foreignId('annee_scolaire_classe_id')->constrained('annee_scolaire_classes')->cascadeOnDelte();
            $table->foreignIdFor(ModuleUser::class)->constrained()->cascadeOnDelete();
            $table->boolean('etat')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
