<?php

require_once(__DIR__ . '/../../config.php');

if (!isset($_GET['courseid'])) {
  die('Informe o ID do curso');
}

$PAGE->set_url(new moodle_url('/blocks/presence/componente.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Componente');

global $DB;

echo $OUTPUT->header();

$COURSE = get_course($_GET['courseid']);
$coursecontext = context_course::instance($COURSE->id);

if (!user_has_role_assignment($USER->id, 3, $coursecontext->id) && !has_capability('block/suapattendance:addinstance', $coursecontext, 1)) {
  echo "Fazes o quê aqui?";
  echo $OUTPUT->footer();
  die();
}

$course_info = get_fast_modinfo($COURSE->id);
$section_infos = [];
$i = 0;
foreach ($course_info->get_section_info_all() as $sectionid => $section) {
    $section_name = $section->name ? $section->name : "Tópico $i";
    $section->cms = [];
    $section->name = $section_name;
    $section_infos[$section->id] = $section;
    $i++;
}

$rows = array_values($DB->get_records_sql('
    SELECT   moduleid
    FROM     mdl_suapattendance_componente c 
               INNER JOIN mdl_suapattendance_aula a ON (c.aulaid=a.id)
;
', ['courseid'=>$COURSE->id]));

$modulos_adicionados = [];
foreach ($rows as $key => $value) {
  $modulos_adicionados[$value->moduleid] = $value->moduleid;
}

foreach ($course_info->cms as $cmid => $cm) {
  // echo "<pre>"; echo json_encode((array)$cm);die();
  //  echo "<pre>";var_dump($cm);die();
  $module = new stdClass();
  $module->cmid = $cm->id;
  $module->name = $cm->name;
  $module->ja_adicionado = in_array($cm->id, $modulos_adicionados);
  $section_infos[$cm->section]->cms[] = $module;
}

$section_infos = array_values($section_infos);

// echo "<pre>";var_dump($section_infos[1]->cms);die();

// echo "<pre>";var_dump($section_infos[1]->cms);die();

// $modulos = array_values($DB->get_records('course_modules', ['course'=>$COURSE->id]));
// visibleoncoursepage
// completion
// visible

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $cms = $section_infos[$_GET['sectionid']]->cms;
  $componentes = array_values($DB->get_records('suapattendance_componente', ['aulaid'=>$_GET['aulaid']]));
  
  foreach ($cms as $cm => $value) {
    foreach ($componentes as $componente) {
      if ($value->ja_adicionado == true && $value->cmid == $componente->moduleid) {
        $value->presenca = $componente->quantidade_aulas;
      }
    }
  }
  // echo "<pre>";var_dump($cms);die();
  
  $templatecontext = [ 'course_id' => $_GET['courseid'], 'cms' => $cms, ];
  echo $OUTPUT->render_from_template('block_suapattendance/componente', $templatecontext);
} else {
  
  $componentes = array_values($DB->get_records('suapattendance_componente', ['aulaid'=>$_GET['aulaid']]));
  
  // echo "<pre>";var_dump($componentes);die();

  // Formata os dados recebidos por post e get
  $i = 0;
  foreach ($_POST as $post => $value) {
    if(substr($post, 0, 10) == "componente") {
      $cmid = substr($post, 11);
      $cms[$cmid] = new stdClass();
      $cms[$cmid]->aulaid = filter_input(INPUT_GET, 'aulaid', FILTER_VALIDATE_INT);
      $cms[$cmid]->moduleid = substr($post, 11);
    } elseif (substr($post, 0, 8) == "presenca" && substr($post, 9) == $cms[$i-1]->moduleid) {
      $cms[$i-1]->quantidade_aulas = $value;
    }
    $i++;
  }
  
  // Verifica se o componente já está adicionado ao banco, se não estiver, adciona ao banco e se tiver verifica se o percentual de presença permanece o mesmo.
  // caso seja diferente, altera e atualiza isso no banco
  foreach ($cms as $cm) {
    $k = -1;
    foreach ($componentes as $comp) {
      if ($cm->moduleid == $comp->moduleid) {
        $k = $comp->id;
        if($cm->quantidade_aulas != $comp->quantidade_aulas) {
          $cm->id = $comp->id;
          $DB->update_record('suapattendance_componente', $cm);
        }
        break;
      }
    }
    
    if($k == -1 ){
      $DB->insert_record('suapattendance_componente', $cm, $returnid=false, $bulk=false);
    }
  }

  // verifica se o componente que está no banco também está no post, caso não esteja, apaga no banco os que não estão (não funciona)
  $i = 0;
  foreach ($componentes as $comp) {
    $k = -1;
    foreach ($cms as $cm) {
      if($comp->moduleid == $cm->moduleid) {
        $k = $comp->moduleid;
        break;
      }
    }
    
    if ($k == -1) {
      $dels[$i] = $comp;
      $i++;
    }
  }

  if($i != 0) {
    foreach ($dels as $del) {
      $DB->delete_records('suapattendance_aula', ['id'=>$del->id]);
    }
  }

  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?courseid=$COURSE->id", "Presença configurada com sucesso!");
}

// implentar o incremento para conseguir pegar os N componentes que vão vim do post - Lembrar de calcular antes o tamanho do array que vai templateContext e passar paro template para ser colocado como campo hidden no forms e ser pegado aqui de volta por post.
// Puxar do banco a % de presença do componente

echo $OUTPUT->footer();

// if (isset($_GET['post'])) {
//   // Estou salvando
//   $componente = new stdClass();
//   $componente->aulaid = filter_input(INPUT_GET, 'aulaid', FILTER_VALIDATE_INT);
//   $componente->moduleid = filter_input(INPUT_GET, 'moduleid', FILTER_VALIDATE_INT);
//   $componente->quantidade_aulas = 10 /*filter_input(INPUT_GET, 'quantidade_aulas', FILTER_VALIDATE_INT)*/;

//   $id_componente = $DB->insert_record('suapattendance_componente', $componente, $returnid=true, $bulk=false);
//   redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");

// } elseif (isset($_GET['delete']) && isset($_GET['componenteid'])) {
//   $DB->delete_records('suapattendance_componente', ['id'=>filter_input(INPUT_GET, 'componenteid', FILTER_VALIDATE_INT)]);
//   redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
// } else {
//   echo $OUTPUT->header();
//   // Estou incluindo
//   $id_aula = $_GET['aulaid'];
//   $templatecontext = [ 'course_id' => $_GET['id'], 'sections' => $section_infos, 'aulaid' => $_GET['aulaid'], ];

//   echo $OUTPUT->render_from_template('block_suapattendance/componente', $templatecontext);
//   echo $OUTPUT->footer();
// }