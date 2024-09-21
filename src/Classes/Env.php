<?php

class Env
{
  /** @var (array{
   *  prd:string,
   *  stg:?string,
   *  dev:string
   * }) */
  private array $envs = [
    'prd' => '',
    'stg' => null,
    'dev' => 'localhost'
  ];
  private string $host;

  public bool $prd = false;
  public bool $stg = false;
  public bool $dev = false;

  public string $is = 'DEV';

  public function __construct(
    string $production_host,
    ?string $staging_host = null,
    ?string $dev_host = 'localhost'
  ) {
    $this->host =  $_SERVER['SERVER_NAME'];
    $this->envs['prd'] = $production_host;
    $this->envs['stg'] = $staging_host;
    $this->envs['dev'] = $dev_host;

    $this->is = strtoupper($this->get());

    $this->prd = $this->check('prd');
    $this->stg = $this->check('stg');
    $this->dev = $this->check('dev');
  }

  private function check(string $env)
  {
    return $this->is === $env;
  }

  private function get()
  {
    $res = array_filter(
      $this->envs,
      fn($e) => strpos($this->host, $e) !== false
    );
    $key = array_key_first($res);
    if (!is_string($key)) return 'dev';
    return $key;
  }
}
