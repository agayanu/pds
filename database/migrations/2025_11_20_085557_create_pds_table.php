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
            $table->char('Status',1)->default('1');
            $table->char('Leader',10);
            $table->string('LeaderName',50);
            $table->text('Remarks')->nullable();
            $table->string('UserName',15);
            $table->datetime('LastUpdate');
        });

        Schema::create('pds_init_violation', function (Blueprint $table) {
            $table->integer('RecID')->primary()->autoIncrement();
            $table->integer('ReffNo');
            $table->integer('ItemID');
            $table->string('ItemDesc',50);
            $table->text('Punishment');
            $table->char('Temp',1)->default('N');
            $table->string('UserName',15);
            $table->datetime('LastUpdate');
        });

        Schema::create('pds_type', function (Blueprint $table) {
            $table->integer('TransNo')->primary()->autoIncrement();
            $table->smallInteger('TransGroup');
            $table->string('ItemDesc',50);
            $table->text('Punishment')->nullable();
            $table->char('Status',1);
            $table->string('UserName',15);
            $table->datetime('LastUpdate');
            $table->string('Group',15);
            $table->smallInteger('Article')->nullable();
        });

        Schema::create('master_student', function (Blueprint $table) {
            $table->integer('Reg_No')->primary()->autoIncrement();
            $table->char('ID_No',10);
            $table->string('F_Name',50);
            $table->string('N_Name',50)->nullable();
            $table->char('NIRM',15)->nullable();
            $table->char('NIS',15)->nullable();
            $table->smallInteger('Period')->nullable();
            $table->smallInteger('Semester')->nullable();
            $table->tinyInteger('Semester_Type')->nullable();
            $table->smallInteger('Levels')->nullable();
            $table->smallInteger('Major')->nullable();
            $table->smallInteger('Grade')->nullable();
            $table->char('Class',10)->nullable();
            $table->smallInteger('Shift')->nullable();
            $table->char('Leader',10)->nullable();
            $table->string('LeaderName',50)->nullable();
            $table->smallInteger('Status')->nullable();
            $table->smallInteger('Location')->nullable();
            $table->tinyInteger('Scholarship')->nullable();
            $table->char('Gender',1)->nullable();
            $table->string('Remarks',50)->nullable();
            $table->datetime('Trans_Date')->nullable();
            $table->string('User_Name',15)->nullable();
            $table->datetime('LastUpdate')->nullable();
            $table->char('ReRegist',1)->nullable();
            $table->double('ExamNoTemp',10,0)->nullable();
            $table->string('TempProcess',30)->nullable();
        });

        Schema::create('pds_input', function (Blueprint $table) {
            $table->id();
            $table->integer('reffno_pds_init');
            $table->integer('student');
            $table->string('article');
            $table->string('remarks')->nullable();
            $table->string('evidence',480);
            $table->string('username');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds_init');
        Schema::dropIfExists('pds_init_violation');
        Schema::dropIfExists('pds_type');
        Schema::dropIfExists('Master_Student');
        Schema::dropIfExists('pds_input');
    }
};
