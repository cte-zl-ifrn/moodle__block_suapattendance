<?php
define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');

global $DB;

$COURSE = get_course(2);
$coursecontext = context_course::instance($COURSE->id);
// $course_info = get_fast_modinfo($COURSE->id);

// foreach ($course_info->cms as $cmid => $cm) {
//     echo "$cm->id" . "\n";
// }

$course_info = get_fast_modinfo($COURSE->id);
$componentes = array_values($DB->get_records('suapattendance_componente', ['aulaid'=>$aula->id], 'ordem'));
$aulas = $DB->get_records('suapattendance_aula', ['periodoaula_id'=>$etapa->id], 'ordem');

foreach ($aulas as $aula) {
    
  foreach ($componentes as $componente) {
    foreach ($course_info->cms as $cmid => $cm) {
        $componente->cms = [];
        if($aula->id == $componente->aulaid && $componte->moduleid == $cm->id) {
          $componentes->cms[$componente->aulaid] = $componente;
        }
    }
  }
}




// $section_infos = [];
// $i = 0;
// foreach ($course_info->get_section_info_all() as $sectionid => $section) {
//     $section_name = $section->name ? $section->name : "Tópico $i";
//     $section->cms = [];
//     $section->name = $section_name;
//     $section_infos[] = $section;
//     $i++;
// }
// foreach ($course_info->cms as $cmid => $cm) {
//     $section_infos[$cm->section]->cms[] = $cm;
// }


// foreach ($section_infos as $section) {
//     echo $section->name . "\n";
//     // foreach ($section->cms as $cm) {
//     //     echo "  $cm->name\n";
//     // }
// }

// var_dump($section_infos);


// $id_ja_usados = $DB->get_records(...);

// $i = 0;
// foreach ($course_info->get_section_info_all() as $sectionid => $section) {
//     $section_name = $section->name ? $section->name : "Tópico $i";
//     echo "$section->id - $section_name\n";
//     $i++;
//     var_dump($section->get_formatted_name());
//     // foreach ($section->get_modinfo() as $cmid => $cm) {
//     //     echo "{$cm->name} - {$cm->name} - {$section->name}\n";
//     // }
// }
