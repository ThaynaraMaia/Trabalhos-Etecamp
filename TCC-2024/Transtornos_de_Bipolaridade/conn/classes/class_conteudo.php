<?php

class conteudos {
    private $id_conteudos;
    private $home;
    private $nome_bipolaridade;
    private $bipolaridade;
    private $nome_pranayama;
    private $pranayama;
    private $nome_asanas;
    private $asanas;
    private $nome_restaurativas;
    private $restaurativas;
    private $nome_nidra;
    private $nidra;
    private $nome_meditacao;
    private $meditacao;
    private $nome_humor;
    private $Humor;
    private $nome_alongamento;
    private $alongamento;
    private $nome_caminhada;
    private $caminhada;
    private $nome_respiracao;
    private $respiracao;
    private $nome_danca;
    private $danca;
    private $nome_hiit;
    private $hiit;
    private $nome_sonobipolaridade;
    private $sonobipolaridade;
    private $nome_sonodicas;
    private $sonodicas;
    private $nome_sonoregular;
    private $sonoregular;
    private $status;

    public function __construct($id_conteudos,$home,  $nome_pranayama, $pranayama, 
    $nome_asanas,$asanas, $nome_restaurativas, $restaurativas, $nome_nidra, $nidra, 
    $nome_meditacao, $meditacao, $nome_bipolaridade,$bipolaridade, $nome_humor,$Humor,
    $nome_alongamento, $alongamento,$nome_caminhada,$caminhada, $nome_respiracao, $respiracao,
    $nome_danca, $danca,$nome_hiit,$hiit,$nome_sonodicas, $sonodicas, $nome_sonoregular,$sonoregular,
    $nome_sonobipolaridade, $sonobipolaridade, $status){


        $this->id_conteudos=$id_conteudos;
        $this->home=$home;
        $this->nome_bipolaridade=$nome_bipolaridade;
        $this->bipolaridade=$bipolaridade;
        $this->nome_humor=$nome_humor;
        $this->Humor=$Humor;
        $this->nome_pranayama=$nome_pranayama;
        $this->pranayama=$pranayama;
        $this->nome_asanas=$nome_asanas;
        $this->asanas=$asanas;
        $this->nome_restaurativas=$nome_restaurativas;
        $this->restaurativas=$restaurativas;
        $this->nome_nidra=$nome_nidra;
        $this->nidra=$nidra;
        $this->nome_meditacao=$nome_meditacao;
        $this->meditacao=$meditacao;
        $this->nome_alongamento=$nome_alongamento;
        $this->alongamento=$alongamento;
        $this->nome_caminhada=$nome_caminhada;
        $this->caminhada=$caminhada;
        $this->nome_respiracao=$nome_respiracao;
        $this->respiracao=$respiracao;
        $this->nome_danca=$nome_danca;
        $this->danca=$danca;
        $this->nome_caminhada=$nome_caminhada;
        $this->caminhada=$caminhada;
        $this->nome_hiit=$nome_hiit;
        $this->hiit=$hiit;
        $this->nome_sonobipolaridade=$nome_sonobipolaridade;
        $this->sonobipolaridade=$sonobipolaridade;
        $this->nome_sonodicas=$nome_sonodicas;
        $this->sonodicas=$sonodicas;
        $this->nome_sonoregular=$nome_sonoregular;
        $this->sonoregular=$sonoregular;
        $this->status=$status;
       
    }

    public function setId_conteudos($id_conteudos){
        $this->id_conteudos=$id_conteudos;
    }

    public function getId_conteudos(){
        return $this->id_conteudos;
    }

    public function setHome($home){
        $this->home=$home;
    }

    public function getHome(){
        return $this->home;
    }
   
    public function setNome_bipolaridade($nome_bipolaridade){
        $this->nome_bipolaridade=$nome_bipolaridade;
    }

    public function getNome_bipolaridade(){
        return $this->nome_bipolaridade;
    }

    public function setBipolaridade($bipolaridade){
        $this->bipolaridade=$bipolaridade;
    }

    public function getBipolaridade(){
        return $this->bipolaridade;
    }

    public function setNome_meditacao($nome_meditacao){
        $this->nome_meditacao=$nome_meditacao;
    }

    public function getNome_meditacao(){
        return $this->nome_meditacao;
    }

    public function setMeditacao($meditacao){
        $this->meditacao=$meditacao;
    }

    public function getMeditacao(){
        return $this->meditacao;
    }

    public function setNome_humor($nome_humor){
        $this->nome_humor=$nome_humor;
    }

    public function getNome_humor(){
        return $this->nome_humor;
    }

    public function setHumor($Humor){
        $this->Humor=$Humor;
    }

    public function getHumor(){
        return $this->Humor;
    }

    public function setNome_pranayma($nome_pranayama){
        $this->nome_pranayama=$nome_pranayama;
    }

    public function getNome_pranayma(){
        return $this->nome_pranayama;
    }

    public function setPranayma($pranayama){
        $this->pranayama=$pranayama;
    }

    public function getPranayma(){
        return $this->pranayama;
    }

    public function setNome_asanas($nome_asanas){
        $this->nome_asanas=$nome_asanas;
    }

    public function getNome_asanas(){
        return $this->nome_asanas;
    }

    public function setAsanas($asanas){
        $this->asanas=$asanas;
    }

    public function getAsanas(){
        return $this->asanas;
    }

    public function setNome_restaurativas($nome_restaurativas){
        $this->nome_restaurativas=$nome_restaurativas;
    }

    public function getNome_restaurativas(){
        return $this->nome_restaurativas;
    }

    public function setRestaurativas($nome_restaurativas){
        $this->restaurativas=$nome_restaurativas;
    }

    public function getRestaurativas(){
        return $this->restaurativas;
    }

    public function setNome_nidra($nome_nidra){
        $this->nome_nidra=$nome_nidra;
    }

    public function getNome_nidra(){
        return $this->nome_nidra;
    }

    public function setNidra($nome_nidra){
        $this->nidra=$nome_nidra;
    }

    public function getNidra(){
        return $this->nidra;
    }

    
    public function setNome_alongamento($nome_alongamento){
        $this->nome_alongamento=$nome_alongamento;
    }

    public function getNome_alongamento(){
        return $this->nome_alongamento;
    }

    public function setAlongamento($alongamento){
        $this->alongamento=$alongamento;
    }

    public function getAlongamento(){
        return $this->alongamento;
    }

    public function setNome_caminhada($nome_caminhada){
        $this->nome_caminhada=$nome_caminhada;
    }

    public function getNome_caminhada(){
        return $this->nome_caminhada;
    }

    public function setCaminhada($caminhada){
        $this->caminhada=$caminhada;
    }

    public function getCaminhada(){
        return $this->caminhada;
    }

    public function setNome_respiracao($nome_respiracao){
        $this->nome_respiracao=$nome_respiracao;
    }

    public function getNome_respiracao(){
        return $this->nome_respiracao;
    }

    public function setRespiracao($nome_respiracao){
        $this->respiracao=$nome_respiracao;
    }

    public function getRespiracao(){
        return $this->respiracao;
    }

    public function setNome_danca($nome_danca){
        $this->nome_danca=$nome_danca;
    }

    public function getNome_danca(){
        return $this->nome_danca;
    }

    public function setDanca($danca){
        $this->danca=$danca;
    }

    public function getDanca(){
        return $this->danca;
    }

    public function setNome_hiit($nome_hiit){
        $this->nome_hiit=$nome_hiit;
    }

    public function getNome_hiit(){
        return $this->nome_hiit;
    }

    public function setHiit($hiit){
        $this->hiit=$hiit;
    }

    public function getHiit(){
        return $this->hiit;
    }

    
    public function setNome_sonodicas($nome_sonodicas){
        $this->nome_sonodicas=$nome_sonodicas;
    }

    public function getNome_sonodicas(){
        return $this->nome_sonodicas;
    }

    public function setSonodicas($sonodicas){
        $this->sonodicas=$sonodicas;
    }

    public function getSonodicas(){
        return $this->sonodicas;
    }

    public function setNome_sonoregular($nome_sonoregular){
        $this->nome_caminhada=$nome_sonoregular;
    }

    public function getNome_sonoregular(){
        return $this->nome_sonoregular;
    }

    public function setSonoregular($sonoregular){
        $this->sonoregular=$sonoregular;
    }

    public function getSonoregular(){
        return $this->sonoregular;
    }

    public function setNome_sonobipolaridae($nome_sonobipolaridade){
        $this->nome_sonobipolaridade=$nome_sonobipolaridade;
    }

    public function getNome_sonobipoaridade(){
        return $this->nome_sonobipolaridade;
    }

    public function setSonobipolaridade($nome_sonobipolaridade){
        $this->sonobipolaridade=$nome_sonobipolaridade;
    }

    public function getSonobipolaridade(){
        return $this->sonobipolaridade;
    }

    public function setStatus($status){
        $this->status=$status;
    }

    public function getStatus(){
        return $this->status;
    }


}
?>