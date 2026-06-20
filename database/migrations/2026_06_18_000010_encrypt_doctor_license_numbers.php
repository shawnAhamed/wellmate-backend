<?php

use App\Models\Doctor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * license_number becomes encrypted-at-rest. Since encryption is
     * non-deterministic (random IV per value), SQL equality checks against
     * the ciphertext can't enforce uniqueness — so we keep a deterministic
     * HMAC alongside it purely for uniqueness lookups (a "blind index").
     */
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('license_number_hash')->nullable()->after('license_number');
        });

        DB::table('doctors')->select('id', 'license_number')->get()->each(function ($doctor) {
            DB::table('doctors')->where('id', $doctor->id)->update([
                'license_number' => Crypt::encryptString($doctor->license_number),
                'license_number_hash' => Doctor::hashLicenseNumber($doctor->license_number),
            ]);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->unique('license_number_hash');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropUnique(['license_number_hash']);
        });

        DB::table('doctors')->select('id', 'license_number')->get()->each(function ($doctor) {
            DB::table('doctors')->where('id', $doctor->id)->update([
                'license_number' => Crypt::decryptString($doctor->license_number),
            ]);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('license_number_hash');
        });
    }
};
