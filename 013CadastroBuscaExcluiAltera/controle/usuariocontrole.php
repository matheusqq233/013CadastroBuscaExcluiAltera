<?php
    session_start();
    //session_unset(); //Removendo as sessões anteriores

    include_once '../modelo/usuario.class.php';
    include_once '../util/validacao.class.php';
    include_once '../dao/usuariodao.class.php';
    include_once '../util/controlelogin.class.php';

    
    
    if( isset($_GET['op']) ){
                    
     switch($_GET['op']) {

        case 'cadastrar':
            //Cadastro com validação - testando se existem
            if( isset($_POST['txtlogin']) &&
                isset($_POST['txtsenha']) &&
                isset($_POST['seltipo']) ) {

                    //Recebendo os dados
                    $login = $_POST['txtlogin'];
                    $senha = $_POST['txtsenha'];
                    $tipo = $_POST['seltipo'];

                    //fazendo a validação
                    $erros = array();

                    if(!Validacao::testarLogin($login) ){
                        $erros[] = 'Login inválido!';
                    }

                    if(!Validacao::testarSenha($senha) ){
                        $erros[] = 'Senha inválida!';
                    }

                    if(!Validacao::testarTipo($tipo) ){
                        $erros[] = 'Tipo inválido!';
                    }

                    if( count($erros) == 0){
                        $u = new Usuario();
                        $u->login = $_POST['txtlogin'];
                        $u->senha = $_POST['txtsenha'];
                        $u->tipo = $_POST['seltipo'];

                        /*Enviar o objeto $u para o banco de dados */
                        $uDAO = new UsuarioDAO();
                        $uDAO->cadastrarUsuario($u);

                        $_SESSION['u']=serialize($u);
                        $_SESSION['msg'] = 'Usuário' . $u->login .'cadastrado com sucesso!';

                        header("location:../visao/guiresposta.php");
                    }else{
                        $_SESSION['erros'] = serialize($erros);
                        header("location:../visao/guierro.php");
                    }//fecha o if do count
            }else{
            echo 'DEU RUIM!';
            }//fecha o isset

        break; 

        case 'consultar':
            $uDAO = new UsuarioDAO();

            $array = array();
            $array = $uDAO->buscarUsuario();

            $_SESSION['usuario'] = serialize($array);
            header("location:../visao/guiconsulta.php");
        break; //

        case 'deletar':
            if( isset($_REQUEST['idUsuario'])){
                $uDAO = new UsuarioDAO();
                $uDAO->deletarUsuario($_REQUEST['idUsuario']);

                header('location:../controle/usuariocontrole.php?op=consultar');
            }else{
                echo'idUsuario não existe';
            }

        break;

        case 'logar':   
            if( isset($_POST['txtlogin']) &&
                isset($_POST['txtsenha'])){
                    $cont = 0;

                    if(!Validacao::testarLogin($_POST['txtlogin'])){
                        $cont++;
                    }

                    if(!Validacao::testarSenha($_POST['txtsenha'])){
                        $cont++;
                    }

                    if($cont == 0){
                        $login = Validacao::retirarEspacos($_POST['txtlogin']);
                        $login = Validacao::escaparAspas($login);

                        $senha = Validacao::retirarEspacos($_POST['txtsenha']);
                        $senha = Validacao::escaparAspas($senha);

                        $usuario = new Usuario();

                        
                        $usuario->login = $login;
                        $usuario->senha = $senha;
                        ControleLogin::logar($usuario);
                    }else{
                        $_SESSION['msg'] = 'Login/Senha inválidos!';
                        header('location:../visao/guiresposta.php');
                    }

                }else{
                    echo'Não existe txtlogin e/ou txtsenha!!';
                }
        break;

        case 'deslogar':
            ControleLogin::deslogar();
            
        break;

        case 'buscar':
            if( isset($_POST['txtfiltro']) &&
                isset($_POST['rdfiltro'])){
                    $erros = array();
                    if(!Validacao::validarFiltro($_POST['txtfiltro'])){
                        $erros[] = 'Dado Inválido!';
                    }

                    if(count($erros) == 0){
                        $uDAO = new UsuarioDAO();
                        $usuario = array();
                    

                        if ($_POST['rdfiltro'] == 'idusuario') {
                            $query = "where idusuario = " . $_POST['txtfiltro'];
                        } else if ($_POST['rdfiltro'] == 'login') {
                            $query = "where login = \"" . $_POST['txtfiltro'] . "\"";
                        } else if ($_POST['rdfiltro'] == 'parteslogin') {
                            $query = "where login like '%" . $_POST['txtfiltro'] . "%'";
                        } else {
                            $query = "where tipo = \"" . $_POST['txtfiltro'] . "\"";
                        }
                        

                    $usuario = $uDAO->buscar($query);

                    $_SESSION['usuario']=serialize($usuario);
                    header('location:../visao/guiconsulta.php');
                }else{
                    $_SESSION['erros'] = serialize($erros);
                    header('location:../visao/guierro.php');
                }
                }else{
                    echo 'Variáveis não existem!';
                }

        break;
        
        case 'alterar':
            if( isset($_GET['idUsuario'])){
                $query = 'where idusuario = '.$_GET['idUsuario'];

                $uDAO = new UsuarioDAO();
                $usuarios = array();
                $usuarios = $uDAO->buscar($query);

                $_SESSION['usuarios'] = serialize($usuarios);
                header('location:../visao/guialterar.php');

            }else{
                echo 'Não existem variáveis!';
            }
        break;
        
        case 'confirmalterar':
            if (isset($_POST['txtidusuario']) && isset($_POST['txtlogin']) && isset($_POST['txtsenha']) && isset($_POST['seltipo'])) {
                $idUsuario = $_POST['txtidusuario'];
                $login = $_POST['txtlogin'];
                $senha = $_POST['txtsenha'];
                $tipo = $_POST['seltipo'];
        
                // Criar objeto Usuario com os dados do formulário
                $usuario = new Usuario();
                $usuario->idusuario = $idUsuario;
                $usuario->login = $login;
                $usuario->senha = $senha;
                $usuario->tipo = $tipo;
        
                // Chamar a função para alterar o usuário na DAO
                $uDAO = new UsuarioDAO();
                $uDAO->alterarUsuario($usuario);
        
                // Redirecionar para a página de consulta ou outra página desejada
                header('location:../controle/usuariocontrole.php?op=consultar');
            } else {
                echo 'Variáveis não existem!';
            }
        break;

        default: echo 'Erro no switch';
        break;//fecha case cadastrar
    }//fecha o switch
}else{
    echo 'Variavel não existe';
}
    ?>