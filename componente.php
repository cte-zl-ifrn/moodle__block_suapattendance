<?php

require_once(__DIR__ . '/../../config.php');

if (!isset($_GET['courseid'])) {
  die('Informe o ID do curso');
}

$PAGE->set_url(new moodle_url('/blocks/presence/componente.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Componente');

global $DB;

$COURSE = get_course($_GET['courseid']);
$coursecontext = context_course::instance($COURSE->id);

if (!user_has_role_assignment($USER->id, 3, $coursecontext->id) && !has_capability('block/suapattendance:addinstance', $coursecontext, 1)) {
  echo "Fazes o quê aqui?";
  echo $OUTPUT->footer();
  die();
}

$course_info = get_fast_modinfo($COURSE->id);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
  $rows = array_values($DB->get_records_sql('
    SELECT   moduleid
    FROM     mdl_suapattendance_componente c 
    INNER JOIN mdl_suapattendance_aula a ON (c.aulaid=a.id);
  ', ['courseid'=>$COURSE->id]));

  $modulos_adicionados = [];
  foreach ($rows as $key => $value) {
    $modulos_adicionados[$value->moduleid] = $value->moduleid;
  }

  $cms = [];
  $i = 0;
  foreach ($course_info->cms as $cmid => $cm) {
    if ($cm->get_section_info()->id == $_GET['sectionid']) {
      $cms[$i] = new stdClass();
      $cms[$i]->cmid = $cm->id;
      $cms[$i]->name = $cm->name;
      $cms[$i]->ja_adicionado = in_array($cmid, $modulos_adicionados);
      $i++;
    }
  }

  $componentes = array_values($DB->get_records('suapattendance_componente', ['aulaid'=>$_GET['aulaid']]));
  
  foreach ($cms as $cm => $value) {
    foreach ($componentes as $componente) {
      if ($value->ja_adicionado == true && $value->cmid == $componente->moduleid) {
        $value->presenca = $componente->quantidade_aulas;
      }
    }
  }
  $templatecontext = [ 'course_id' => $_GET['courseid'], 'cms' => $cms, ];
  echo $OUTPUT->header();
  echo $OUTPUT->render_from_template('block_suapattendance/componente', $templatecontext);
  echo $OUTPUT->footer();
} else {

  $componentes = array_values($DB->get_records('suapattendance_componente', ['aulaid'=>$_GET['aulaid']]));

  // Formata os dados recebidos por post e get
  $cms = [];
  $total = 0;
  foreach ($_POST as $post => $value) {
    if(substr($post, 0, 10) == "componente") {
      $cmid = substr($post, 11);
      $cms[$cmid] = new stdClass();
      $cms[$cmid]->aulaid = filter_input(INPUT_GET, 'aulaid', FILTER_VALIDATE_INT);
      $cms[$cmid]->moduleid = substr($post, 11);
      $cms[$cmid]->quantidade_aulas = $_POST["presenca-{$cmid}"];
      $total += $_POST["presenca-{$cmid}"];
    }
  }

  if ($total == 100 || $cms == []) {
    // Compara os dados vindo do banco com o que veio do POST e se ouver alteração, altera. Além disso, se o objeto já existir no banco, retira dos arrays.
    // Sobrando do array que veio do banco, os componentes que precisam ser apagados e do array que veio do post os componentes que precisam ser adicionados
    foreach ($componentes as $comp => $value) {
      if (array_key_exists($value->moduleid, $cms)) {
        if ($cms[$value->moduleid]->quantidade_aulas != $value->quantidade_aulas) {
          $cms[$value->moduleid]->id = $value->id;
          $DB->update_record('suapattendance_componente', $cms[$value->moduleid]);
        }
        unset($componentes[$comp]);
        unset($cms[$value->moduleid]);
      }
    }
    
    // Adicionando no banco os novos componentes
    if ($cms) {
      foreach ($cms as $cm) {
        $DB->insert_record('suapattendance_componente', $cm, $returnid=false, $bulk=false);
      }
    }

    // Apagando do banco componentes não mais utilizados
    if ($componentes) {
      foreach($componentes as $comp) {
        $DB->delete_records('suapattendance_componente', ['id'=>$comp->id]);
      }
    }

    redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?courseid=$COURSE->id", "Presença configurada com sucesso!");
  } else {
    redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?courseid=$COURSE->id");
  }
}