<?php

namespace Sales;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer {

  private $connection = null;
  private $channel = null;
  private $config = [];
  private $isConnected = false;
  private $listeners = [];

  public function __construct($config) {
    $this->config = $config;
  }

  private function connect() {
    $this->connection = new AMQPConnection(
      $this->config["rabbit_host"],
      $this->config["rabbit_port"],
      $this->config["rabbit_user"],
      $this->config["rabbit_pass"]
    );
    $this->channel = $this->connection->channel();
  } 

  private function setup() {
    $this->connect();
    $this->channel->exchange_declare("sales", "fanout", false, false, false);
    list($this->qname, , ) = $this->channel->queue_declare("", false, false, true, false);
    $this->channel->queue_bind($this->qname, "sales");
    $this->isConnected = true;
  }

  private function tearDown() {
    $this->channel->close();
    $this->connection->close();
  }

  public function consume() {
    if (!$this->isConnected) {
      $this->setup();
    }
    $this->channel->basic_consume($this->qname, "", false, true, false, false, function($msg) {
      foreach($this->listeners as $listener) {
        call_user_func($listener, $msg);
      }
    });
    while(count($this->channel->callbacks)) {
      $this->channel->wait();
    }
    $this->tearDown();
  }

  public function listen($listener) {
    array_push($this->listeners, $listener);
  }
}
