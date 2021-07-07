<?php

use yii\db\Migration;

class m220320_170233_initial extends Migration
{
    public function up()
    {
        $this->createTable('proposal_proposal', array(
            'id' => 'pk',
            'organization_id' => 'int(11) NOT NULL',
            'status' => 'varchar(40) DEFAULT "Pendiente"',
            'timestamp' => 'timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)',
            'consent' => 'varchar(255) NOT NULL',
            'rrss_consent' => 'varchar(255) NOT NULL',
            'organization_area' => 'varchar(255) NOT NULL',
            'title' => 'varchar(40) NOT NULL',
            'objectives' => 'varchar(255) NOT NULL',
            'short_description' => 'varchar(255) NOT NULL',
            'expected_progress' => 'varchar(255) NOT NULL',
            'expected_progress_indicators' => 'varchar(255) NOT NULL',
            'impact' => 'varchar(255) NOT NULL',
            'related_links' => 'varchar(255) NOT NULL',
            'sdg' => 'varchar(255) NOT NULL',
            'student_academic_profile' => 'varchar(255) NOT NULL',
            'student_profile' => 'varchar(255) NOT NULL',
            'requirements' => 'varchar(255) NOT NULL',
            'organization' => 'varchar(255) NOT NULL',
            'space_id' => 'int(11) DEFAULT NULL',
        ), '');

        $this->createTable('proposal_organization', array(
            'id' => 'pk',
            'name' => 'varchar(40) NOT NULL',
            'legal_form' => 'tinyint(1) NOT NULL',
            'web_page' => 'varchar(90) NOT NULL',
            'big' => 'tinyint(1) NOT NULL',
        ), '');
    }

    public function down()
    {
        echo "m220320_170233_initial does not support migration down.\n";
        return false;
    }
}
