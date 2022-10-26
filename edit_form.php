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
 * Form for editing suapattendance block instances.
 *
 * @package     block_suapattendance
 * @copyright   2022 Kelson Medeiros <kelsoncm@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_suapattendance_edit_form extends block_edit_form {

    /**
     * Extends the configuration form for block_suapattendance.
     *
     * @param MoodleQuickForm $mform The form being built.
     */
    protected function specific_definition($mform) {

        // Section header title.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // config_title
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_suapattendance'));
        $mform->setDefault('config_title', get_string('pluginname', 'block_suapattendance'));
        $mform->setType('config_title', PARAM_TEXT);

        // faltas_ou_presencas
        $options = [
            'p' => get_string('presenca', 'block_suapattendance'),
            'f' => get_string('falta', 'block_suapattendance'),
        ];
        $mform->addElement('select', 'config_faltas_ou_presencas', get_string('faltas_ou_presencas', 'block_suapattendance'), $options);
        $mform->setDefault('faltas_ou_presencas', 'p');
        $mform->setType('faltas_ou_presencas', PARAM_TEXT);

        // apresentacao
        $options = [
            'p' => get_string('porcentagem', 'block_suapattendance'),
            'q' => get_string('quantidade', 'block_suapattendance'),
            'a' => get_string('ambos', 'block_suapattendance'),
        ];
        $mform->addElement('select', 'config_apresentacao', get_string('apresentacao', 'block_suapattendance'), $options);
        $mform->setDefault('apresentacao', 'p');
        $mform->setType('apresentacao', PARAM_TEXT);

    }
}
