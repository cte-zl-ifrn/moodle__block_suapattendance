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
 * Block suapattendance is defined here.
 *
 * @package     block_suapattendance
 * @copyright   2022 Kelson Medeiros <kelsoncm@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_suapattendance extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_suapattendance');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
        // return "Conteúdo do block";

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        // TODO: se não estiver configurado. se professor, pedir que configure. se aluno, pedir que aguarde o professor configurar
        $text = "
            <table style='width: 100%;' border=1>
            <tr>
            <td>&nbsp</td>
            <td>&nbsp</td>
            <td>&nbsp</td>
            <td>&nbsp</td>
            <td>&nbsp</td>
            </tr>
            </table>
        ";

        $total = 5;
        $falta = 1;
        $presenca = 3;
        $porcentagem_presencas = $presenca / $total * 100;
        $porcentagem_faltas = $falta / $total * 100;
        if ($this->config != null && property_exists($this->config, 'faltas_ou_presencas')) {
            if ($this->config->faltas_ou_presencas == 'p') {
                switch ($this->config->apresentacao) {
                    case 'p':
                        $text .= "$porcentagem_presencas% de presenças";
                        break;
                    case 'q':
                        $text .= "$presenca presenças de $total";
                        break;
                    default:
                        $text .= "$presenca presenças de $total<br>";
                        $text .= "$porcentagem_presencas% de presenças";
                        break;
                }
            } else {
                switch ($this->config->apresentacao) {
                    case 'p':
                        $text .= "$porcentagem_faltas% de faltas";
                        break;
                    case 'q':
                        $text .= "$falta faltas de $total";
                        break;
                    default:
                        $text .= "$falta faltas de $total<br>";
                        $text .= "$porcentagem_faltas% de faltas";
                        break;
                }
            }
            $text .= "<div><a class='btn btn-primary'>Detalhar</a></div>";
    
            $this->content->text = $text;
            $this->content->text .= $OUTPUT->render_from_template('block_suapattendance/widget');
        }

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = "Um título qq";
            // $this->title = get_string('pluginname', 'block_suapattendance');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    public function has_config() {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return [
            // 'all' => false,
            // 'site' => true,
            // 'site-index' => true,
            'course-view' => true, 
            // 'course-view-social' => false,
            // 'mod' => true, 
            // 'mod-quiz' => false
        ];
    }
}
