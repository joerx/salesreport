<?php

require_once(__DIR__ . "/vendor/autoload.php");

use Sales\ReportGenerator;
use Sales\Consumer;

$salesReport = new Sales\ReportGenerator([
  "xsl_file" => __DIR__ . "/src/template.xsl",
  "data_file" => __DIR__ . "/data/sales.xml",
  "output_file" => __DIR__ . "/public/index.html"
]);


$data = $salesReport->dumpData();
var_dump($data);

$html = $salesReport->render();
var_dump($html);
