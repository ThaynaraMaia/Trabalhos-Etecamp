<?php

    include_once "classConexao.php";
    include_once "classCategoria.php";


    interface IRepositorioCategoria{
        public function incluirCategoria($categoria);
        public function listarCategorias();
        public function buscarCategoria($id);
        public function verificaCategoria($nome);
        public function editarCategorias($id, $nome);
        public function excluirCategorias($id);
    }

    class RepositorioCategoriaMySQL implements IRepositorioCategoria{

        private $conexao;

        public function __construct(){

            $this->conexao = new Conexao("localhost", "root", "", "mv");

            if($this->conexao->conectar() == false){
                echo "Erro.".mysqli_connect_error();
            }
        }


        public function incluirCategoria($categoria)
        {
            $id = $categoria->getId();
            $nome = $categoria->getNome();

            $sql = "INSERT INTO categorias (id, nome) VALUES ('$id', '$nome')";

            $this->conexao->executarQuery($sql);
        }

        public function listarCategorias(){

            $sql = "SELECT * FROM categorias ORDER BY nome ASC";
            
            $registro = $this->conexao->executarQuery($sql);

            return $registro;

        }

        public function buscarCategoria($id){

            $sql = "SELECT * FROM categorias where id ='$id'";

            $registro = $this->conexao->executarQuery($sql);

            return $registro;

        }

        public function verificaCategoria($nome){
            $sql = "SELECT * FROM categorias WHERE nome = '$nome'";

            $encontrou = $this->conexao->executarQuery($sql);

            return $encontrou;
        }


        public function editarCategorias($id, $nome){

            $sql = "UPDATE categorias SET nome = '$nome' WHERE id ='$id'";
            
            $editar = $this->conexao->executarQuery($sql);

            return $editar;
        }

        public function excluirCategorias($id){
            
            $sql = "DELETE FROM categorias where id ='$id'";

            $registro = $this->conexao->executarQuery($sql);

            return $registro;

        }

    }

    $repositorioCategoria = new RepositorioCategoriaMySQL();
   
?>