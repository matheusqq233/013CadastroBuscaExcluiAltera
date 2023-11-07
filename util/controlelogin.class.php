<?php
include_once '../dao/usuariodao.class.php';

class ControleLogin{

    public static function logar($u){
        $uDAO = new UsuarioDAO();
        $usuario = $uDAO->verificarUsuario($u);

        if($usuario && !is_null($usuario)) {
            $_SESSION['privateUser']=serialize($usuario);

            header("location:../index.php");
        }else{
            $_SESSION['msg']='Login ou Senha inválidos!';
            header("location:../visao/guiresposta.php");
        }//fecha o if
    }//fecha o método logar

    public static function deslogar(){
        unset($_SESSION['privateUser']);
        $_SESSION['msg']='Você foi deslogado!';
        header("location:../visao/guiresposta.php");
    }//fecha o método deslogar

    public static function verificarAcesso(){
        if(!isset($_SESSION['privateUser']) ){
            $_SESSION['msg']='Você não está logado!';
            header("location:../visao/guiresposta.php");
        }//fim do if
    }//fecha o método verificar acesso
}
?>