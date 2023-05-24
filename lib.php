<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contains functions called by core.
 *
 * @package    block_online_users
 * @copyright  2018 Mihail Geshoski <mihail@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function course_deleted_observer(\core\event\course_deleted $event) {
    global $DB;
    
    $context = $event->get_context();
    $courseid = $context->instanceid;
    
    $aulas = array_values($DB->get_records_sql('
    SELECT a.id
    FROM mdl_suapattendance_aula a 
        INNER JOIN mdl_course_sections s ON (a.sectionid = s.id) WHERE course = ?;
    ', [$courseid]));

    if ($aulas) {
        foreach($aulas as $aula) {
            $DB->delete_records('suapattendance_aula', ['id'=>$aula->id]);
        }
    }
}

// Registrar o manipulador de evento para o evento 'course_deleted'
$observers = [
    [
        'eventname' => '\core\event\course_deleted',
        'callback' => 'course_deleted_observer',
    ],
];

// foreach ($handlers as $eventname => $callback) {
//     $observers = event_observer::instances();
//     $observers->register($eventname, $callback);
// }

// $eventobservers = \core\event\observer::create_instances($observers);
// foreach ($eventobservers as $eventobserver) {
//     $eventobserver->init();
// }


// Registre os manipuladores de eventos
foreach ($observers as $observer) {
    $observers = event_observer::instances();
    $observers->register($observer['eventname'], $observer['callback']);
}
