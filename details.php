<?php

require_once(__DIR__ . '/../../config.php');
$PAGE->set_url(new moodle_url('/blocks/presence/details.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Detalhes Presença');

echo $OUTPUT->header();

$templatecontext = [
    'aluno' => 'Thiago Dutra da Silva Gomes',
    'presenca' => [
        ["peso" => 1   , "presenca" => True, "descricao" => "Vídeo"        ],
        ["peso" => 0.5 , "presenca" => True, "descricao" => "PDF besta"    ],
        ["peso" => 0.5 , "presenca" => True, "descricao" => "Avaliaçao 1"  ],
        ["peso" => 0.5 , "presenca" => True, "descricao" => "Vídeo"        ],
        ["peso" => 0.5 , "presenca" => True, "descricao" => "PDF besta"    ],
        ["peso" => 1   , "presenca" => True, "descricao" => "Avaliaçao 2"  ],
    ],
];

echo $OUTPUT->render_from_template('block_suapattendance/details', $templatecontext);

echo $OUTPUT->footer();