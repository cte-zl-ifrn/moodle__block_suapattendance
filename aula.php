<?php
if (!isset($_GET['id'])) {
  die('Informe o ID do curso');
}

require_once(__DIR__ . '/../../config.php');

$PAGE->set_url(new moodle_url('/blocks/presence/periodo.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Período');

global $DB;

$COURSE = get_course($_GET['id']);
$coursecontext = context_course::instance($COURSE->id);
$course_info = get_fast_modinfo($COURSE->id);


if (!user_has_role_assignment($USER->id, 3, $coursecontext->id) && !has_capability('block/suapattendance:addinstance', $coursecontext, 1)) {
  echo $OUTPUT->header();
  echo "Fazes o quê aqui?";
  echo $OUTPUT->footer();
  die();
}


if ($_POST) {
  // Estou salvando
  $aula = new stdClass();
  $aula->etapaid = filter_input(INPUT_GET, 'etapaid', FILTER_VALIDATE_INT);
  $aula->data_inicio = strtotime(filter_input(INPUT_POST, 'data_inicio', FILTER_DEFAULT));
  $aula->data_fim = strtotime(filter_input(INPUT_POST, 'data_fim', FILTER_DEFAULT));
  $aula->conteudo = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_STRING);
  $aula->ordem = filter_input(INPUT_POST, 'ordem_periodo', FILTER_VALIDATE_INT);
  if (isset($_GET['aulaid'])) {
    $aula->id= filter_input(INPUT_GET, 'etapaid', FILTER_VALIDATE_INT);
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
  } else {
    // Estou incluindo
    $id_section = $_GET['topicoid'];
    $templatecontext = ['course_id' => $_GET['id'],];
  }  
  echo $OUTPUT->render_from_template('block_suapattendance/aula', $templatecontext);
  echo $OUTPUT->footer();
}
