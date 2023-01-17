<?php

require_once(__DIR__ . '/../../config.php');

if (!isset($_GET['id'])) {
  die('Informe o ID do curso');
}

$PAGE->set_url(new moodle_url('/blocks/presence/componente.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Componente');

global $DB;

$COURSE = get_course($_GET['id']);
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
                 INNER JOIN mdl_suapattendance_periodo_aula p ON (a.periodoaula_id=p.id)
                   INNER JOIN mdl_suapattendance_etapa e ON (p.etapaid=e.id AND e.courseid=:courseid)
;
', ['courseid'=>$COURSE->id]));

$modulos_adicionados = [];
foreach ($rows as $key => $value) {
  $modulos_adicionados[$value->moduleid] = $value->moduleid;
}




foreach ($course_info->cms as $cmid => $cm) {
  $module = new stdClass();
  $module->name = $cm->name;
  $module->ja_adicionado = in_array($cm->id, $modulos_adicionados);
  $section_infos[$cm->section]->cms[] = $module;
}

$section_infos = array_values($section_infos);

// $modulos = array_values($DB->get_records('course_modules', ['course'=>$COURSE->id]));
// visibleoncoursepage
// completion
// visible

if (isset($_GET['post'])) {
  // Estou salvando
  $componente = new stdClass();
  $componente->aulaid = filter_input(INPUT_GET, 'aulaid', FILTER_VALIDATE_INT);
  $componente->moduleid = filter_input(INPUT_GET, 'moduleid', FILTER_VALIDATE_INT);
  $componente->ordem = 1; // Implementar como alterar a ordem !!!

  $id_componente = $DB->insert_record('suapattendance_componente', $componente, $returnid=true, $bulk=false);
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");

} elseif (isset($_GET['delete']) && isset($_GET['componenteid'])) {
  $DB->delete_records('suapattendance_componente', ['id'=>filter_input(INPUT_GET, 'componenteid', FILTER_VALIDATE_INT)]);
  redirect("{$CFG->wwwroot}/blocks/suapattendance/configurar-frequencia.php?id=$COURSE->id");
} else {
  echo $OUTPUT->header();
  // Estou incluindo
  $id_aula = $_GET['aulaid'];
  $templatecontext = [ 'course_id' => $_GET['id'], 'sections' => $section_infos, 'aulaid' => $_GET['aulaid'], ];

  echo $OUTPUT->render_from_template('block_suapattendance/componente', $templatecontext);
  echo $OUTPUT->footer();
}