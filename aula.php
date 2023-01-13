<?php

require_once(__DIR__ . '/../../config.php');

if (!isset($_GET['id'])) {
  die('Informe o ID do curso');
}

$PAGE->set_url(new moodle_url('/blocks/presence/aula.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Aula');

global $DB;

$COURSE = get_course($_GET['id']);
$coursecontext = context_course::instance($COURSE->id);

if (!user_has_role_assignment($USER->id, 3, $coursecontext->id) && !has_capability('block/suapattendance:addinstance', $coursecontext, 1)) {
  echo "Fazes o quê aqui?";
  echo $OUTPUT->footer();
  die();
}

if ($_POST) {
  // Estou salvando
  $aula = new stdClass();
  $aula->periodoaula_id = filter_input(INPUT_GET, 'periodoaula_id', FILTER_VALIDATE_INT);
  $aula->quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
  $aula->ordem = filter_input(INPUT_POST, 'ordem', FILTER_VALIDATE_INT);
  if (isset($_GET['aulaid'])) {
    $aula->id= filter_input(INPUT_GET, 'aulaid', FILTER_VALIDATE_INT);
    $DB->update_record('suapattendance_aula', $aula);
  } else {
    $id_aula = $DB->insert_record('suapattendance_aula', $aula, $returnid=true, $bulk=false);
  }
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
} elseif (isset($_GET['delete']) && isset($_GET['aulaid'])) {
  $DB->delete_records('suapattendance_aula', ['id'=>filter_input(INPUT_GET, 'aulaid', FILTER_VALIDATE_INT)]);
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
} else {
  echo $OUTPUT->header();
  if (isset($_GET['aulaid'])) {
    // Estou editando
    $aula = $DB->get_record('suapattendance_aula', ['id'=>$_GET['aulaid']]);
    $templatecontext = ['course_id' => $COURSE->id, 'aula' => $aula];
    // echo "<pre>";var_dump($aula);die();
  } else {
    // Estou incluindo
    $id_periodo = $_GET['periodoaula_id'];
    $templatecontext = ['course_id' => $_GET['id'], 'periodoaula_id' => $_GET['periodoaula_id'], ];
  }  
  echo $OUTPUT->render_from_template('block_suapattendance/aula', $templatecontext);
  echo $OUTPUT->footer();
}