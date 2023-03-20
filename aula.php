<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/suapattendance/classes/form/moodleFormAula.php');

$PAGE->set_url(new moodle_url('/blocks/presence/aula.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Aula');

if (isset($_GET['id'])) {
  $COURSE = get_course($_GET['id']);
} elseif (isset($SESSION->aula_courseid)) {
  $COURSE = get_course($SESSION->aula_courseid);
} else {
  die('Informe o ID do curso');
}

global $DB, $SESSION;

$coursecontext = context_course::instance($COURSE->id);
$course_info = get_fast_modinfo($COURSE->id);

if (!user_has_role_assignment($USER->id, 3, $coursecontext->id) && !has_capability('block/suapattendance:addinstance', $coursecontext, 1)) {
  echo $OUTPUT->header();
  echo "Fazes o quê aqui?";
  echo $OUTPUT->footer();
  die();
}

if (isset($_GET['delete']) && isset($_GET['aulaid'])) {
  $DB->delete_records('suapattendance_aula', ['id'=>filter_input(INPUT_GET, 'aulaid', FILTER_VALIDATE_INT)]);
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id", "Aula apagada com sucesso!");
} else {
  $SESSION->aula_courseid = $COURSE->id;
  $mform = new moodleFormAula();
  if ($mform->is_cancelled()) {
    redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$SESSION->aula_courseid", 'Você cancelou a ação!');
  } else {
    $fromform = $mform->get_data();
    if ($fromform) {
      $fromform->courseid = $SESSION->aula_courseid;
      $fromform->conteudo = $fromform->conteudo['text'];
      
      $id_aula = $DB->insert_record('suapattendance_aula', $fromform, $returnid=true, $bulk=false);
      // redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$SESSION->aula_courseid", 'Aula inserida com sucesso!');
      redirect("{$CFG->wwwroot}/blocks/suapattendance/componente.php?id=$SESSION->aula_courseid&sectionid=$fromform->sectionid", 'Aula inserida com sucesso!');
    }
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
  }
}
// echo "<pre>";var_dump($fromform);die();