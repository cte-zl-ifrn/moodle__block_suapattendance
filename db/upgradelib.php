<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin upgrade helper functions are defined here.
 *
 * @package     block_suapattendance
 * @category    upgrade
 * @copyright   2022 Kelson Medeiros <kelsoncm@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see         https://docs.moodle.org/dev/Data_definition_API
 * @see         https://docs.moodle.org/dev/XMLDB_creating_new_DDL_functions
 * @see         https://docs.moodle.org/dev/Upgrade_API
 */

defined('MOODLE_INTERNAL') || die();

function block_suapattendance_migrate($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion == 0) {
        # suapattendance_etapa
        $table = new xmldb_table("suapattendance_etapa");
        $table->add_field("id",         XMLDB_TYPE_INTEGER, '10',   XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE,  null, null, null);
        $table->add_field("courseid",   XMLDB_TYPE_INTEGER, '10',   XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("nome",       XMLDB_TYPE_CHAR,    '255',  null,           XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("ordem",      XMLDB_TYPE_INTEGER, '10',   XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("idnumber",   XMLDB_TYPE_CHAR,    '10',   XMLDB_UNSIGNED, null,          null,            null, null, null);

        $table->add_key("primary",      XMLDB_KEY_PRIMARY,  ["id"],         null,       null);
        $table->add_key('courseid_fk',  XMLDB_KEY_FOREIGN,  ['courseid'],   'course', ['id']);
        $status = $dbman->create_table($table);

        $table = new xmldb_table("suapattendance_periodo_aula");
        $table->add_field("id",             XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE,  null, null, null);
        $table->add_field("etapaid",        XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("data_inicio",    XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("data_fim",       XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("conteudo",       XMLDB_TYPE_TEXT,    'medium',   XMLDB_UNSIGNED, null,          null,            null, null, null);
        $table->add_field("ordem",          XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("idnumber",       XMLDB_TYPE_CHAR,    '255',      XMLDB_UNSIGNED, null,          null,            null, null, null);

        $table->add_key("primary",      XMLDB_KEY_PRIMARY, ["id"],          null,                   null);
        $table->add_key("etapaid_fk",   XMLDB_KEY_FOREIGN, ['etapaid'],     'suapattendance_etapa', ['id']);
        $status = $dbman->create_table($table);

        # suapattendance_aula
        $table = new xmldb_table("suapattendance_aula");
        $table->add_field("id",                     XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE,  null, null, null);
        $table->add_field("periodoaula_id",         XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("quantidade",             XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("ordem",                  XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,            null, null, null);
        $table->add_field("idnumber",               XMLDB_TYPE_CHAR,    '255',      XMLDB_UNSIGNED, null,          null,            null, null, null);

        $table->add_key("primary",           XMLDB_KEY_PRIMARY, ["id"],              null,                           null);
        $table->add_key("periodoaula_id_fk", XMLDB_KEY_FOREIGN, ['periodoaula_id'],  'suapattendance_periodo_aula',  ['id']);
        $status = $dbman->create_table($table);

        # suapattendance_falta
        $table = new xmldb_table("suapattendance_falta");
        $table->add_field("id",                 XMLDB_TYPE_INTEGER, '10',   XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE,    null, null, null);
        $table->add_field("aulaid",             XMLDB_TYPE_INTEGER, '10',   XMLDB_UNSIGNED, XMLDB_NOTNULL, null,              null, null, null);
        $table->add_field("userid",             XMLDB_TYPE_INTEGER, '10',   XMLDB_UNSIGNED, XMLDB_NOTNULL, null,              null, null, null);
        $table->add_field("quantidade_faltas",  XMLDB_TYPE_INTEGER, '10',   XMLDB_UNSIGNED, null,          null,              null, null, null);
        $table->add_field("idnumber",           XMLDB_TYPE_CHAR,    '255',  XMLDB_UNSIGNED, null,          null,              null, null, null);

        $table->add_key("primary",   XMLDB_KEY_PRIMARY, ["id"],       null,                   null);
        $table->add_key("aulaid_fk", XMLDB_KEY_FOREIGN, ['aulaid'],   'suapattendance_aula',  ['id']);
        $table->add_key("userid_fk", XMLDB_KEY_FOREIGN, ['userid'],   'user',                 ['id']);
        $status = $dbman->create_table($table);
        
        # suapattendance_componente
        $table = new xmldb_table("suapattendance_componente");
        $table->add_field("id",         XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE,    null, null, null);
        $table->add_field("aulaid",     XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,              null, null, null);
        $table->add_field("moduleid",   XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,              null, null, null);
        $table->add_field("ordem",      XMLDB_TYPE_INTEGER, '10',       XMLDB_UNSIGNED, XMLDB_NOTNULL, null,              null, null, null);
        $table->add_field("idnumber",   XMLDB_TYPE_CHAR,    '255',      XMLDB_UNSIGNED,          null,          null,              null, null, null);

        $table->add_key("primary",      XMLDB_KEY_PRIMARY, ["id"], null, null);
        $table->add_key("aulaid_fk",    XMLDB_KEY_FOREIGN, ['aulaid'],   'suapattendance_aula', ['id']);
        $table->add_key("moduleid_fk",  XMLDB_KEY_FOREIGN, ['moduleid'], 'course_modules',      ['id']);
        $status = $dbman->create_table($table);
    }
    return true;
}
