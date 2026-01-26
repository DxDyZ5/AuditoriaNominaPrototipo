<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class V110 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'default_privacy')) {
                $table->smallInteger('default_privacy')->after('billing_information')->default(1)->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'brand')) {
                $table->text('brand')->after('billing_information')->nullable();
            }
        });

        Schema::table('reports', function ($table) {
            \Illuminate\Support\Facades\DB::statement('DROP INDEX IF EXISTS reports_domain_index');
            $table->renameColumn('domain', 'project');
            $table->index('project', 'reports_project_index');
            $table->index('created_at', 'reports_created_at_index');
        });

        DB::table('settings')->insert(
            [
                ['name' => 'ad_dashboard_top', 'value' => ''],
                ['name' => 'ad_dashboard_bottom', 'value' => ''],
                ['name' => 'ad_report_top', 'value' => ''],
                ['name' => 'ad_report_bottom', 'value' => ''],
                ['name' => 'ad_reports_top', 'value' => ''],
                ['name' => 'ad_reports_bottom', 'value' => ''],
                ['name' => 'ad_projects_top', 'value' => ''],
                ['name' => 'ad_projects_bottom', 'value' => ''],
                ['name' => 'ad_tool_top', 'value' => ''],
                ['name' => 'ad_tool_bottom', 'value' => ''],
                ['name' => 'ad_tools_top', 'value' => ''],
                ['name' => 'ad_tools_bottom', 'value' => '']
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
