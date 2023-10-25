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
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_vacation_days()
            RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.status_id = 3 AND NEW.type = 1 THEN
                    UPDATE company_employees
                    SET vacation_days = vacation_days - (NEW."end" - NEW.start)
                    WHERE user_id = NEW.user_id;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER inquires_after_update
            AFTER UPDATE ON inquires
            FOR EACH ROW
            EXECUTE FUNCTION update_vacation_days();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS inquires_after_update ON inquires');
        DB::unprepared('DROP FUNCTION IF EXISTS update_vacation_days()');
    }
};
