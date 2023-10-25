<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $query = '
            CREATE OR REPLACE VIEW inquire_details AS
            SELECT
                i.inquire_id,
                i.user_id,
                u.name AS user_name,
                u.surname AS user_surname,
                u.email AS user_email,
                c.name AS company_name,
                c.id AS company_id,
                it.name AS type_name,
                it.id AS type_id,
                s.name AS status_name,
                s.id AS status_id,
                i.start,
                i.end
            FROM inquires i
            JOIN users u ON i.user_id = u.id
            JOIN company_employees ce ON u.id = ce.user_id
            JOIN companies c ON ce.company_id = c.id
            JOIN inquire_types it ON i.type = it.id
            JOIN statuses s ON i.status_id = s.id;
        ';

        DB::statement($query);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS inquire_details');
    }
};
