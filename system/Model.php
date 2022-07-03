<?php
/**
 * Esta clase es el intermediario entre los modelos y
 * la clase Database, esto con el objetivo de mantener
 * una estructura solida y poco confusa
 **/
namespace System;

use Buki\Pdox;

class Model
{
    private $bdd = null;
    private $config;

    public function db() {
        if(is_null($this->bdd)) {
            $this->config = [
                'host'		=> $_ENV['APP_DATABASE_HOST'],
                'driver'	=> "mysql",
                'database'	=> $_ENV['APP_DATABASE_NAME'],
                'username'	=> $_ENV['APP_DATABASE_USER'],
                'password'	=> $_ENV['APP_DATABASE_PASSWORD'],
                'charset'	=> "utf8",
                'collation'	=> "utf8mb4_unicode_ci",
                'prefix'	=> "",
                'port'	=> $_ENV['APP_DATABASE_PORT']
            ];
            /**
             * Pdox es un Query Builder usado para facilitar la manera en que se
             * hacen las consultas a la base de datos, es una clase bien completa
             * que contiene metodos para toda clase de consultas
             **/
            $this->bdd = new Pdox($this->config);
        }
        return $this->bdd;
    }
}