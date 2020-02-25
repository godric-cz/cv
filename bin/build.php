<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$data = Yaml::parseFile(__DIR__ . '/../cv.yaml');

// add sensitive data
$dataSensitive = Yaml::parseFile(__DIR__ . '/../cv-sensitive.yaml');
$data['Osobní informace'] = array_merge(
    $data['Osobní informace'],
    $dataSensitive['Osobní informace']
);

// normalize personal info data structure
$data['Osobní informace'] = [$data['Osobní informace']];

// load template
$latte = new Latte\Engine;
$template = __DIR__ . '/../template/cv.latte';
$parameters = ['data' => $data];

if (PHP_SAPI == 'cli') {
    // commandline buid
    $parameters['base'] = __DIR__ . '/../';
    $htmlFile = __DIR__ . '/../cv.html';
    $pdfFile = __DIR__ . '/../cv.pdf';
    $html = $latte->renderToString($template, $parameters);
    file_put_contents($htmlFile, $html);

    $args = [
        __DIR__ . '/../vendor/bin/wkhtmltopdf-amd64',
        '-T', '0mm', '-R', '0mm', '-B', '0mm', '-L', '0mm',
        '--print-media-type',
        '--zoom', '1.25',
        $htmlFile,
        $pdfFile,
    ];

    system(implode(' ', array_map('escapeshellarg', $args)));

    unlink($htmlFile);
} else {
    // browser preview
    $parameters['base'] = '/';
    $latte->render($template, $parameters);
}
