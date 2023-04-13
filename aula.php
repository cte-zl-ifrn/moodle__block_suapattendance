<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/suapattendance/classes/form/moodleFormAula.php');

$PAGE->set_url(new moodle_url('/blocks/presence/aula.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Aula');

$mform = new moodleFormAula();

if (isset($_GET['courseid'])) {
  $COURSE = get_course($_GET['courseid']);
} elseif (isset($_POST['courseid'])) {
  $COURSE = get_course($_POST['courseid']);
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

// 'id' é o identificador da aula
if (isset($_GET['delete']) && isset($_GET['id'])) {
  // Estou APAGANDO
  $DB->delete_records('suapattendance_aula', ['id'=>filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)]);
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?courseid=$COURSE->id", "Aula apagada com sucesso!");
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Estou SALVANDO (novo ou existente)
  if ($mform->is_cancelled()) {
    redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?courseid=$COURSE->id", 'Você cancelou a ação!');
  } else {
    $fromform = $mform->get_data();
    $fromform->courseid = $COURSE->id;
    $fromform->conteudo = $fromform->conteudo['text'];
    if (isset($_POST['id']) && !empty($_POST['id'])) {
      $DB->update_record('suapattendance_aula', $fromform);
      redirect("{$CFG->wwwroot}/blocks/suapattendance/componente.php?courseid=$COURSE->id&sectionid=$fromform->sectionid&aulaid=$fromform->id", "Aula alterada com sucesso!");
    } else {
      $aulaid = $DB->insert_record('suapattendance_aula', $fromform, $returnid=true, $bulk=false);
      redirect("{$CFG->wwwroot}/blocks/suapattendance/componente.php?courseid=$COURSE->id&sectionid=$fromform->sectionid&aulaid=$aulaid", "Aula inserida com sucesso!");
    }
  }
} else {
  // Estou EDITANDO (novo ou existente)
  
  if (isset($_GET['id'])) {
    // É alteração
    $aula = $DB->get_record('suapattendance_aula', ['id'=>$_GET['id']]);
    $aula->conteudo = ["text"=>$aula->conteudo];
  } else {
    // É novo
    $aula = (object)[];
  }
  
  $aula->courseid = $COURSE->id;
  $mform->set_data($aula);
  echo $OUTPUT->header();
  $mform->display();
  echo $OUTPUT->footer();
}