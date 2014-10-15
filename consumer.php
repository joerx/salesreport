<?php

// TODO:
// - Render chart (Highcharts)
// - Emit 'real' sales events

require_once(__DIR__ . "/vendor/autoload.php");

use Sales\ReportGenerator;
use Sales\Consumer;

$salesReport = new Sales\ReportGenerator([
  "xsl_file" => __DIR__ . "/src/template.xsl",
  "data_file" => __DIR__ . "/data/sales.xml",
  "output_file" => __DIR__ . "/public/index.html"
]);

$consumer = new Sales\Consumer([
  "rabbit_host" => "localhost",
  "rabbit_port" => 5672,
  "rabbit_user" => "guest",
  "rabbit_pass" => "guest"
]);

$consumer->listen([$salesReport, "update"]);
$consumer->consume();
