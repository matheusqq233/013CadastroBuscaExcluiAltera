<?php
    class ConexaoBanco extends PDO{
        private static $instancia=null;

        public function ConexaoBanco($dsn,$usuario,$senha) {
            //Construtor pai do PDO
            parent::__construct($dsn,$usuario,$senha);
        }

        public static function getInstancia(){
            if( !isset(self::$instancia) ) {
                try{
                    /* Cria e retornar uma nova conexão */
                    self::$instancia = new ConexaoBanco("mysql:dbname=tdsmanha;host=localhost","root","");
                }catch(Exception $e){
                    echo 'Erro ao conectar';
                    exit();
                }//fecha o catch
            }//fecha o if
            return self::$instancia;
        }//fecha o método

    }//fecha a classe ConexaoBanco
?>