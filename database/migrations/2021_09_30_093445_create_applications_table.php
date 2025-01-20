<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->timestamps(); // created_at and updated_at
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('fathers_name', 255);
            $table->string('gender', 25);
            $table->string('passport_serial_number', 25);
            $table->date('birth_date');
            $table->string('phone_number', 128);
            $table->string('emergency_contact', 128);
            $table->string('email', 254);
            $table->string('type', 25);
            $table->string('entrance_via', 25);
            $table->string('transcript', 100)->nullable();
            $table->string('application', 100)->nullable();
            $table->double('dtm_score')->nullable();
            $table->string('dtm_certificate', 100)->nullable();
            $table->double('ielts_score')->nullable();
            $table->string('ielts_certificate', 100)->nullable();
            $table->string('area_of_study', 25)->nullable();
            $table->string('passport_file_1', 100);
            $table->string('passport_file_2', 100);
            $table->string('highest_qualification', 25)->nullable();
            $table->string('highest_qualification_region', 255)->nullable();
            $table->string('highest_qualification_district', 255)->nullable();
            $table->timestamp('highest_qualification_start_date')->nullable();
            $table->date('highest_qualification_end_date')->nullable();
            $table->string('highest_qualification_diploma', 100)->nullable();
            $table->string('highest_qualification_name', 255)->nullable();
            $table->bigInteger('direction_id')->unsigned();
            $table->bigInteger('entrance_exam_id')->unsigned()->nullable();
            $table->bigInteger('programmes_id')->unsigned();
            $table->bigInteger('citizenship_id')->unsigned()->nullable();
            $table->bigInteger('nationality_id')->unsigned()->nullable();
            $table->bigInteger('region_id')->unsigned()->nullable();
            $table->string('status', 20);
            // Indexes
            $table->index('citizenship_id', 'application_application_citizenship_id_892b8a53');
            $table->index('direction_id', 'application_application_direction_id_3646ac1f');
            $table->index('entrance_exam_id', 'application_application_entrance_exam_id_298f9ca8');
            $table->index('nationality_id', 'application_application_nationality_id_cb17ff1d');
            $table->index('programmes_id', 'application_application_programmes_id_80f5381e');
            $table->index('region_id', 'application_application_region_id_2aba96e1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
