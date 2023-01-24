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
  $periodo_aula = new stdClass();
  $periodo_aula->etapaid = filter_input(INPUT_GET, 'etapaid', FILTER_VALIDATE_INT);
  $periodo_aula->data_inicio = strtotime(filter_input(INPUT_POST, 'data_inicio', FILTER_DEFAULT));
  $periodo_aula->data_fim = strtotime(filter_input(INPUT_POST, 'data_fim', FILTER_DEFAULT));
  $periodo_aula->conteudo = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_STRING);
  $periodo_aula->ordem = filter_input(INPUT_POST, 'ordem_periodo', FILTER_VALIDATE_INT);
  if (isset($_GET['periodoaula_id'])) {
    $periodo_aula->id= filter_input(INPUT_GET, 'etapaid', FILTER_VALIDATE_INT);
    $DB->update_record('suapattendance_periodo_aula', $periodo_aula);
  } else {
    $id_periodo = $DB->insert_record('suapattendance_periodo_aula', $periodo_aula, $returnid=true, $bulk=false);
  }
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
} elseif (isset($_GET['delete']) && isset($_GET['periodoaula_id'])) {
  $DB->delete_records('suapattendance_periodo_aula', ['id'=>filter_input(INPUT_GET, 'periodoaula_id', FILTER_VALIDATE_INT)]);
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
} else {
  echo $OUTPUT->header();
  if (isset($_GET['periodoaula_id'])) {
    // Estou editando
    $periodo_aula = $DB->get_record('suapattendance_periodo_aula', ['id'=>$_GET['periodoaula_id']]);
    $templatecontext = ['course_id' => $COURSE->id, 'periodo_aula' => $periodo_aula];
    // echo "<pre>";var_dump($aula);die();
  } else {
    // Estou incluindo
    $id_etapa = $_GET['etapaid'];
    $templatecontext = ['course_id' => $_GET['id'], 'etapaid' => $_GET['etapaid'],];
  }  
  echo $OUTPUT->render_from_template('block_suapattendance/periodo', $templatecontext);
  echo $OUTPUT->footer();
}
