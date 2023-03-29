<?php

require_once(__DIR__ . '/../../config.php');

if (!isset($_GET['courseid'])) die('Informe o ID');

$PAGE->set_url(new moodle_url('/blocks/presence/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Configurar Frequência');

global $DB, $SESSION;

echo $OUTPUT->header();

$COURSE = get_course($_GET['courseid']);
$coursecontext = context_course::instance($COURSE->id);
$course_info = get_fast_modinfo($COURSE->id);

if (!user_has_role_assignment($USER->id, 3, $coursecontext->id) && !has_capability('block/suapattendance:addinstance', $coursecontext, 1)) {
  echo "Fazes o quê aqui?";
  echo $OUTPUT->footer();
  die();
}

// $etapas = array_values($DB->get_records('suapattendance_etapa', ['courseid'=>$COURSE->id], 'ordem'));
// $array_test = [];

// foreach ($etapas as $etapa) {
//     $etapa->periodos_aula = array_values($DB->get_records('suapattendance_periodo_aula', ['etapaid'=>$etapa->id], 'ordem'));
//   foreach ($etapa->periodos_aula as $periodo_aula) {
//     $periodo_aula->data_inicio_formatada = userdate($periodo_aula->data_inicio);
//     $periodo_aula->data_fim_formatada = userdate($periodo_aula->data_fim);
//     $periodo_aula->aulas = array_values($DB->get_records('suapattendance_aula', ['periodoaula_id'=>$periodo_aula->id], 'ordem'));
//     foreach ($periodo_aula->aulas as $aula) {
//       $aula->componentes = array_values($DB->get_records('suapattendance_componente', ['aulaid'=>$aula->id], 'ordem'));
//       foreach ($aula->componentes as $componente) {
//         foreach ($course_info->cms as $cm) {
//             if(intval($componente->moduleid) == intval($cm->id)) {
//                 $componente->module = $cm;
//               }
//             }
//           }
//         }
//       }
//     }


$aulas = array_values($DB->get_records('suapattendance_aula', null/*['courseid'=>$COURSE->id]*/)); // Impletar retorno de aulas baseadas no curso
// $aulas->componentes = array_values($DB->get_records('suapattendance_componente', ['aulaid'=>$aula->id], 'ordem'));

  $templatecontext = [
    'course' => $COURSE,
    'user' => $USER,
    'course_id' => $COURSE->id,
    'aulas' => $aulas,
  ];

echo $OUTPUT->render_from_template('block_suapattendance/configurar-frequencia', $templatecontext);

echo $OUTPUT->footer();
