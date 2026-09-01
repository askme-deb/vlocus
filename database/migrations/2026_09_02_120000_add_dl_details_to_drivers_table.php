<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Driving-licence detail columns, populated from the BankU driving-license
     * verify API on the driver create/edit screens. Everything is nullable so a
     * driver can still be saved when the lookup only returns a partial record.
     * Dates are kept as plain strings because upstream formats vary
     * (dd-mm-yyyy, dd-MMM-yyyy, ...) and are shown back verbatim.
     */
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('dl_status')->nullable()->after('driving_license_verification_data');
            $table->string('holder_name')->nullable()->after('dl_status');
            $table->string('father_or_husband_name')->nullable()->after('holder_name');
            $table->string('dl_dob')->nullable()->after('father_or_husband_name');
            $table->string('dl_issue_date')->nullable()->after('dl_dob');
            $table->text('dl_address')->nullable()->after('dl_issue_date');
            $table->string('class_of_vehicle')->nullable()->after('dl_address');
            $table->string('dl_verification_id')->nullable()->after('class_of_vehicle');
            $table->string('dl_transaction_id')->nullable()->after('dl_verification_id');
            $table->string('issuing_state')->nullable()->after('dl_transaction_id');
            $table->string('dl_nt_valid_from')->nullable()->after('issuing_state');
            $table->string('dl_nt_valid_to')->nullable()->after('dl_nt_valid_from');
            $table->string('dl_tr_valid_from')->nullable()->after('dl_nt_valid_to');
            $table->string('dl_tr_valid_to')->nullable()->after('dl_tr_valid_from');
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'dl_status',
                'holder_name',
                'father_or_husband_name',
                'dl_dob',
                'dl_issue_date',
                'dl_address',
                'class_of_vehicle',
                'dl_verification_id',
                'dl_transaction_id',
                'issuing_state',
                'dl_nt_valid_from',
                'dl_nt_valid_to',
                'dl_tr_valid_from',
                'dl_tr_valid_to',
            ]);
        });
    }
};
