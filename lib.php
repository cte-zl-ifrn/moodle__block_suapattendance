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

// require_once('config.php');

// // Definir o evento de exclusão do curso
// $event = \core\event\course_deleted::create(array(
//     'context' => context_system::instance(),
//     'objectid' => $courseid, // ID do curso que foi apagado
// ));

// // Disparar o evento
// $event->trigger();

// // Função de manipulador de evento
// function course_deleted_handler(\core\event\course_deleted $event) {
//     // Código para executar quando um curso for apagado
//     // Você pode adicionar ações personalizadas aqui
//     $courseid = $event->objectid;
//     echo "O curso com o ID $courseid foi apagado.";
// }

// // Registrar o observador de evento
// $observers = array(
//     array(
//         'eventname' => '\core\event\course_deleted',
//         'callback' => 'course_deleted_handler',
//     ),
// );



// class mod_meu_plugin_event_listener {


//     protected function init() {
//         $this->data['objecttable'] = 'course';
//         $this->data['crud'] = 'r';
//         // $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
//     }

//     public static function course_deleted(\core\event\course_deleted $event) {
//         $data = $event->get_data();
//         $courseid = $data['objectid'];

//         die();
//     }
// }

// $observers = array(
//     array(
//         'eventname' => '\core\event\course_deleted',
//         'callback' => 'mod_meu_plugin_event_listener::course_deleted',
//     ),
// );



// $observers = array(
//     array(
//         'eventname' => '\core\event\course_deleted',
//         'callback' => function (\core\event\course_deleted $event) {
//             // $data = $event->get_data();
//             // $courseid = $data['objectid'];

//             die();
//         }
//     ),
// );



// class mod_meu_plugin_event_listener extends base {

    // protected function init() {
    //     $this->data['objecttable'] = 'course';
    //     $this->data['crud'] = 'd';
    //     $this->data['edulevel'] = self::LEVEL_TEACHING;
    // }

    // public static function get_name() {
    //     return 'mod_meu_plugin_event';
    // }

    // public function get_description() {
    //     return 'Ouvinte de evento para exclusão de curso no Meu Plugin';
    // }

    // public function get_url() {
    //     return new \moodle_url('/mod/meu_plugin/view.php', array('id' => $this->courseid));
    // }

//     public static function create_from_event(\core\event\base $event) {
//         $data = $event->get_data();
//         $courseid = $data['objectid'];

//         $DB->delete_records('suapattendance_aula', ['id'=>16]);
//     }
// }

// $observers = array(
//     array(
//         'eventname' => '\core\event\course_deleted',
//         'callback' => 'mod_meu_plugin_event_listener::create_from_event',
//     ),
// );



// function course_deleted_observer(\core\event\course_deleted $event) {
//     global $DB;
    
//     $DB->delete_records('suapattendance_aula', ['id'=>16]);

    // $context = $event->get_context();
    // $courseid = $context->instanceid;
    

    // $aulas = array_values($DB->get_records_sql('
    // SELECT a.id
    // FROM mdl_suapattendance_aula a 
    //     INNER JOIN mdl_course_sections s ON (a.sectionid = s.id) WHERE course = ?;
    // ', [$courseid]));

    // if ($aulas) {
    //     foreach($aulas as $aula) {
    //         $DB->delete_records('suapattendance_aula', ['id'=>$aula->id]);
    //     }
    // }
// }

// $observers = [
//     [
//         'eventname' => '\core\event\course_deleted',
//         'callback' => 'course_deleted_observer',
//     ],
// ];

// foreach ($handlers as $eventname => $callback) {
//     $observers = event_observer::instances();
//     $observers->register($eventname, $callback);
// }

// $eventobservers = \core\event\observer::create_instances($observers);
// foreach ($eventobservers as $eventobserver) {
//     $eventobserver->init();
// }


// foreach ($observers as $observer) {
//     $observers = event_observer::instances();
//     $observers->register($observer['eventname'], $observer['callback']);
// }
