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
        Schema::create('pds', function (Blueprint $table) {
            $table->integer('TransNo')->primary()->autoIncrement();
            $table->dateTime('TransDate');
            $table->integer('Reg_No');
            $table->char('ID_No',10);
            $table->string('F_Name',50);
            $table->char('Class',10);
            $table->smallInteger('Grade');
            $table->smallInteger('Levels');
            $table->integer('Major');
            $table->char('Status',1);
            $table->char('Leader',10);
            $table->string('LeaderName',50);
            $table->text('Chronology');
            $table->text('Withness');
            $table->string('Leader_UserName',15);
            $table->datetime('Leader_LastUpdate');
            $table->text('Leader_Remarks');
            $table->string('BP_UserName',15);
            $table->dateTime('BP_LastUpdate');
            $table->text('BP_Remarks');
            $table->string('UserName',15);
            $table->datetime('LastUpdate');
        });

        Schema::create('pds_init', function (Blueprint $table) {
            $table->integer('TransNo')->primary()->autoIncrement();
            $table->integer('ReffNo_PDS')->nullable();
            $table->dateTime('TransDate');
            $table->integer('Reg_No');
            $table->char('ID_No',10);
            $table->string('F_Name',50);
            $table->char('Class',10);
            $table->smallInteger('Grade');
            $table->smallInteger('Levels');
            $table->integer('Major');
            $table->char('Status',1);
            $table->char('Leader',10);
            $table->string('LeaderName',50);
            $table->text('Remarks');
            $table->string('UserName',15);
            $table->datetime('LastUpdate');
        });

        Schema::create('pds_init_violation', function (Blueprint $table) {
            $table->integer('RecID')->primary()->autoIncrement();
            $table->integer('ReffNo');
            $table->integer('ItemID');
            $table->string('ItemDesc',50);
            $table->text('Punishment');
            $table->char('Temp',1);
            $table->string('UserName',15);
            $table->datetime('LastUpdate');
        });

        Schema::create('pds_violation', function (Blueprint $table) {
            $table->integer('RecID')->primary()->autoIncrement();
            $table->integer('ReffNo');
            $table->integer('ReffInit');
            $table->integer('ItemID');
            $table->string('ItemDesc',50);
            $table->text('Punishment');
            $table->char('Temp',1);
            $table->string('UserName',15);
            $table->datetime('LastUpdate');
        });

        Schema::create('pds_type', function (Blueprint $table) {
            $table->integer('TransNo')->primary()->autoIncrement();
            $table->smallInteger('TransGroup');
            $table->string('ItemDesc',50);
            $table->text('Punishment');
            $table->char('Status',1);
            $table->string('UserName',15);
            $table->datetime('LastUpdate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds');
        Schema::dropIfExists('pds_init');
        Schema::dropIfExists('pds_init_violation');
        Schema::dropIfExists('pds_violation');
        Schema::dropIfExists('pds_type');
    }
};
