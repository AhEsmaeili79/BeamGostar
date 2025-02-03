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
        DB::statement("
    CREATE VIEW laboratory AS
    SELECT 
        c.id,
        GROUP_CONCAT(c.name_fa, ' ', c.family_fa) AS full_name_fa,
        GROUP_CONCAT(c.name_en, ' ', c.family_en) AS full_name_en,
        CASE 
            WHEN c.nationality = 0 THEN 'ایرانی' 
            ELSE 'خارجی' 
        END AS national,
        c.mobile,
        c.customer_type,
        c.clearing_type,
        ca.acceptance_date,
        COUNT(s.id) AS samples_number,
        a.id AS analyze_id,
        ca.tracking_code,
        fc.scan_form,
        ca.description,
        ca.priority,
        ca.status,
        fc.state,
        fc.date_success
    FROM customers c
    JOIN customer_analysis ca ON c.id = ca.customers_id
    LEFT JOIN samples s ON ca.id = s.customer_analysis_id
    LEFT JOIN `analyze` a ON s.analyze_id = a.id
    LEFT JOIN financial_check fc ON ca.id = fc.customer_analysis_id
    GROUP BY c.id, ca.id, a.id, fc.id, c.nationality, c.mobile, c.customer_type, c.clearing_type, ca.acceptance_date, ca.tracking_code, fc.scan_form, ca.description, ca.priority, ca.status, fc.state, fc.date_success
");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS laboratory");
    }
};
