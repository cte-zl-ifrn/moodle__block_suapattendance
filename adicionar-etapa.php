<?php

require_once(__DIR__ . '/../../config.php');

if (!isset($_GET['id'])) {
  die('Informe o ID do curso');
}

$PAGE->set_url(new moodle_url('/blocks/presence/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Adicionar Etapa');

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
  $etapa = new stdClass();
  $etapa->courseid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  $etapa->nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
  $etapa->ordem = filter_input(INPUT_POST, 'ordem_etapa', FILTER_VALIDATE_INT);
  if (isset($_GET['etapaid'])) {
    $etapa->id= filter_input(INPUT_GET, 'etapaid', FILTER_VALIDATE_INT);
    $DB->update_record('suapattendance_etapa', $etapa);
  } else {
    $id_etapa = $DB->insert_record('suapattendance_etapa', $etapa, $returnid=true, $bulk=false);
  }
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
} elseif (isset($_GET['delete']) && isset($_GET['etapaid'])) {
  $DB->delete_records('suapattendance_etapa', ['id'=>filter_input(INPUT_GET, 'etapaid', FILTER_VALIDATE_INT)]);
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
} else {
  echo $OUTPUT->header();
  if (isset($_GET['etapaid'])) {
    // Estou editando
    $etapa = $DB->get_record('suapattendance_etapa', ['id'=>$_GET['etapaid']]);
    $templatecontext = ['course_id' => $COURSE->id, 'etapa' => $etapa];
    // echo "<pre>";var_dump($aula);die();
  } else {
    // Estou incluindo
    $id_course = $_GET['courseid'];
    $templatecontext = ['course_id' => $COURSE->id,];
  }  
  echo $OUTPUT->render_from_template('block_suapattendance/adicionar-etapa', $templatecontext);
  echo $OUTPUT->footer();
}

// echo $OUTPUT->header();

// $templatecontext = [
//   'course' => $COURSE,
//   'user' => $USER,
//   'course_id' => $COURSE->id,
// ];

// //echo "<pre>";var_dump($templatecontext['etapas']);//die();
// echo $OUTPUT->render_from_template('block_suapattendance/adicionar-etapa', $templatecontext);

// class etapa {
//   public $courseid;
//   public $nome;
//   public $ordem;

//   function __construct($courseid, $nome, $ordem) {
//     $this->courseid = $courseid;
//     $this->nome = $nome;
//     $this->ordem = $ordem;
//   }
// }

// if(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) != null) {

//   $etapaNome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
//   $etapaOrdem = filter_input(INPUT_POST, 'ordem_etapa', FILTER_DEFAULT);

//   $etapa = new etapa($COURSE->id, $etapaNome, $etapaOrdem);
//   $id_etapa = $DB->insert_record('suapattendance_etapa', $etapa, $returnid=true, $bulk=false);

//   redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
// }



// echo $OUTPUT->footer();