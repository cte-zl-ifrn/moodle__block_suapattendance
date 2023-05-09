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

$aulas = array_values($DB->get_records_sql("
  SELECT a.* 
  FROM mdl_suapattendance_aula a 
      INNER JOIN mdl_course_sections s ON (s.id = a.sectionid) 
        WHERE s.course = ?
        ORDER BY a.etapa
", [$COURSE->id]));

foreach ($aulas as $aula) {
  $aula->data_inicio = date('d/m/Y', $aula->data_inicio);
  $aula->data_fim = date('d/m/Y', $aula->data_fim);
  $section = $DB->get_record('course_sections', ['id' => $aula->sectionid]);
  $aula->sectionname = $section->name;
}

$templatecontext = [
  'course_url' => "{$CFG->wwwroot}/course/view.php?id={$COURSE->id}",
  'course' => $COURSE,
  'user' => $USER,
  'course_id' => $COURSE->id,
  'aulas' => $aulas,
];

echo $OUTPUT->render_from_template('block_suapattendance/configurar-frequencia', $templatecontext);

echo $OUTPUT->footer();
