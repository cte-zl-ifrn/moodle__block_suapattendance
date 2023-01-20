<?php

require_once(__DIR__ . '/../../config.php');

if (!isset($_GET['id'])) {
  die('Informe o ID');
}

$PAGE->set_url(new moodle_url('/blocks/presence/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Configurar Frequência');

global $DB;

echo $OUTPUT->header();

$COURSE = get_course($_GET['id']);
$coursecontext = context_course::instance($COURSE->id);
$course_info = get_fast_modinfo($COURSE->id);


if (!user_has_role_assignment($USER->id, 3, $coursecontext->id) && !has_capability('block/suapattendance:addinstance', $coursecontext, 1)) {
  echo "Fazes o quê aqui?";
  echo $OUTPUT->footer();
  die();
}

$etapas = array_values($DB->get_records('suapattendance_etapa', ['courseid'=>$COURSE->id], 'ordem'));
$array_test = [];

foreach ($etapas as $etapa) {
  $etapa->periodos_aula = array_values($DB->get_records('suapattendance_periodo_aula', ['etapaid'=>$etapa->id], 'ordem'));
  foreach ($etapa->periodos_aula as $periodo_aula) {
    $periodo_aula->data_inicio_formatada = userdate($periodo_aula->data_inicio);
    $periodo_aula->data_fim_formatada = userdate($periodo_aula->data_fim);
    $periodo_aula->aulas = array_values($DB->get_records('suapattendance_aula', ['periodoaula_id'=>$periodo_aula->id], 'ordem'));
    foreach ($periodo_aula->aulas as $aula) {
      $aula->componentes = array_values($DB->get_records('suapattendance_componente', ['aulaid'=>$aula->id], 'ordem'));
      foreach ($aula->componentes as $componente) {
        foreach ($course_info->cms as $cm) {
          if(intval($componente->moduleid) == intval($cm->id)) {
            $componente->module = $cm;
          }
        }
      }
    }
  }
}

$templatecontext = [
  'course' => $COURSE,
  'user' => $USER,
  'course_id' => $COURSE->id,
  'etapas' => $etapas,
];

echo $OUTPUT->render_from_template('block_suapattendance/configurar-frequencia', $templatecontext);

echo $OUTPUT->footer();

// $test = get_user_roles_in_course($USER->id, $_GET['id']); // Retorna a rule do usuário em usuário
// $test2 = get_user_roles_in_course($student->id, $_GET['id']);
// $test3 = user_has_role_assignment($USER->id, 3, $coursecontext->id); // Retorna true caso o usuário tenha a role especificada
// $test4 = user_has_role_assignment($student->id, 5, $coursecontext->id); /*CONTEXT_COURSE*/

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

// class periodo_aula {
//   public $etapaid;
//   public $data_inicio;
//   public $data_fim;
//   public $conteudo;
//   public $ordem;

//   function __construct($etapaid, $data_inicio, $data_fim, $conteudo, $ordem) {
//     $this->etapaid = $etapaid;
//     $this->data_inicio = $data_inicio;
//     $this->data_fim = $data_fim;
//     $this->conteudo = $conteudo;
//     $this->ordem = $ordem;
//   }
// }

// class aula {
//   public $periodoaula_id;
//   public $quantidade;
//   public $ordem;

//   function __construct($periodoaula_id, $quantidade, $ordem) {
//     $this->periodoaula_id = $periodoaula_id;
//     $this->quantidade = $quantidade;
//     $this->ordem = $ordem;
//   }
// }

// class componente {
//   public $aulaid;
//   public $moduleid;
//   public $ordem;

//   function __construct($aulaid, $moduleid, $ordem) {
//     $this->aulaid = $aulaid;
//     $this->moduleid = $moduleid;
//     $this->ordem = $ordem;
//   }
// }

// class falta {
//   public $aulaid;
//   public $userid;
//   public $quantidade_faltas;

//   function __construct($aulaid, $userid, $quantidade_faltas) {
//     $this->aulaid = $aulaid;
//     $this->userid = $userid;
//     $this->quantidade_faltas = $quantidade_faltas;
//   }
// }

// $test_consulta = $DB->get_record_sql('SELECT * FROM {user} WHERE firstname = ? AND lastname = ?', ['Martin', 'Dougiamas']);
// $test_id = $test_consulta->id;