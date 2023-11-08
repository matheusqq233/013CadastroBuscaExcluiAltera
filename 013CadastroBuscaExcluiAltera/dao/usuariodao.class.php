<?php
include '../persistencia/conexaobanco.class.php';
class UsuarioDAO{
    private $conexao=null;

    public function __construct(){
        $this->conexao = ConexaoBanco::getInstancia();
    }//fecha o construtor

    public function cadastrarUsuario($u){
        try{
            $stat = $this->conexao->prepare("insert into usuario(idUsuario,login,senha,tipo)values(null,?,?,?)" );

            $stat->bindValue(1,$u->login);
            $stat->bindValue(2,$u->senha);
            $stat->bindValue(3,$u->tipo);

            $stat->execute();

            //Encerrando a conexao
            $this->conexao=null;
            
        }catch(PDOException $e){
            echo 'Erro ao cadastrar usuário';
        }//fecha o catch
    }//fecha o método

    public function buscarUsuario(){
        try{
            $stat = $this->conexao->query("select * from usuario" );

            $array = array();
            $array =$stat->fetchAll(PDO::FETCH_CLASS, 'usuario');

            //Encerrando a conexao
            $this->conexao=null;
            return $array;
            
        }catch(PDOException $e){
            echo 'Erro ao buscar usuário!';
        }//fecha o catch
    }//fecha o método

    public function deletarUsuario($idUsuario){
        try{
            $stat = $this->conexao->prepare("delete from usuario where idUsuario=?");
            $stat->bindValue(1,$idUsuario);
            $stat->execute();

            $this->conexao=null;
        }catch(PDOException $e){
            echo 'Erro ao deletar usuário!';
        }
    }//fecha o método deletarUsuario

    public function verificarUsuario($u){
        try{
            $stat = $this->conexao->query("select * from usuario where login='$u->login' and senha='$u->senha'");

            $usuario = $stat->fetchObject('usuario');//
            return $usuario;

        }catch(PDOException $e){
            echo 'Erro ao verificar usuário!';
        }
    }



    public function buscar($query){
        try {
            $stat = $this->conexao->query("select * from usuario " . $query);
            $array = $stat->fetchAll(PDO::FETCH_CLASS, 'Usuario');
            $this->conexao = null;
            return $array;
        } catch (PDOException $e) {
            echo 'Erro ao buscar com filtro!';
        }
    }
    
  

    public function alterarUsuario($usu){
        try{
            // Construa a consulta SQL base
            $sql = 'update usuario set ';
            $params = array();
            $alteracoes = 0; // Contador de alterações
    
            // Verifica e adiciona login à consulta se não estiver vazio
            if (!empty($usu->login)) {
                $sql .= 'login = ?, ';
                $params[] = $usu->login;
                $alteracoes++;
            }
    
            // Verifica e adiciona senha à consulta se não estiver vazio
            if (!empty($usu->senha)) {
                $sql .= 'senha = ?, ';
                $params[] = $usu->senha;
                $alteracoes++;
            }
    
            // Verifica e adiciona tipo à consulta se não estiver vazio
            if (!empty($usu->tipo)) {
                $sql .= 'tipo = ?, ';
                $params[] = $usu->tipo;
                $alteracoes++;
            }
    
            // Se houver pelo menos uma alteração, execute a consulta
            if ($alteracoes > 0) {
                // Remove a vírgula extra do final da consulta
                $sql = rtrim($sql, ', ');
    
                // Adiciona a cláusula WHERE para identificar o usuário a ser atualizado
                $sql .= ' where idusuario = ?';
                $params[] = $usu->idusuario;
    
                // Prepara a consulta
                $stat = $this->conexao->prepare($sql);
    
                // Atribui os valores aos parâmetros da consulta
                foreach ($params as $key => $value) {
                    $stat->bindValue($key + 1, $value);
                }
    
                // Executa a consulta
                $stat->execute();
            }
    
            // Encerra a conexão
            $this->conexao = null;
        } catch(PDOException $e) {
            echo 'Erro ao alterar usuário!';
        }
    }
    

    


}//fecha a classe UsuarioDAO
?>