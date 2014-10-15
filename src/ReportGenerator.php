<?php

namespace Sales;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Annotation\Type;

use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerAutoloadNamespace('JMS\Serializer\Annotation', 
    __DIR__ . "/../vendor/jms/serializer/src");

class Model {

  /** @Type("string") */
  public $seller_name = "John Doe";
  
  /** @Type("string") */
  public $buyer_name = "Jane Doe";
  
  /** @Type("float") */
  public $price = 19.95;

  /** @Type("integer") */
  public $amount = 1;
}

class ReportGenerator {

  private $data;
  private $dataFile;
  private $processor;
  private $serializer;

  public function __construct($config) {
    
    $this->dataFile = $config["data_file"];
    $this->xslFile = $config["xsl_file"];
    $this->outputFile = $config["output_file"];

    $this->initSerializer();
    $this->initProcessor();
    $this->loadData();
  }

  public function update($msg) {
    array_push($this->data, $msg);
    $this->dumpData();
    $this->render();
  }

  private function initProcessor() {
    $xsl = new \DOMDocument();
    $xsl->load($this->xslFile);
    $this->processor = new \XSLTProcessor();
    $this->processor->importStylesheet($xsl);
  }

  private function initSerializer() {
    $this->serializer = SerializerBuilder::create()->build();
  }

  public function loadData() {
    if (file_exists($this->dataFile)) {
      $xmlContent = file_get_contents($this->dataFile);
      $this->data = $this->serializer->deserialize($xmlContent, "Sales\Model", "xml");
    } else {
      $this->data = [];
    }
  }

  public function dumpData() {
    $xmlContent = $this->serializer->serialize($this->data, 'xml');
    file_put_contents($this->dataFile, $xmlContent);
    return $xmlContent;
  }

  public function render() {
    $xml = new \DOMDocument();
    $xml->load($this->dataFile);
    $output = $this->processor->transformToDoc($xml);
    $html = $output->saveHTML();
    file_put_contents($this->outputFile, $html);
    return $html;
  }
}
