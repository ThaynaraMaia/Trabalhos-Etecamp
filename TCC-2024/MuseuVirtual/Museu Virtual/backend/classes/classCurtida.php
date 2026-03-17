<?php

    class Curtida{
        private $id;
        private $usuario_id;
        private $obra_id;

        public function __construct($id, $usuario_id, $obra_id){
            $this->id = $id;
            $this->usuario_id = $usuario_id;
            $this->obra_id = $obra_id;
        }

        public function setId($id){
            $this->id = $id;
        }
        public function getId(){
            return $this->id;
        }

        public function setUsuario_id($usuario_id){
            $this->usuario_id = $usuario_id;
        }
        public function getUsuario_id(){
            return $this->usuario_id;
        }

        public function setObra_id($obra_id){
            $this->obra_id = $obra_id;
        }
        public function getObra_id(){
            return $this->obra_id;
        }

    }
    
?>