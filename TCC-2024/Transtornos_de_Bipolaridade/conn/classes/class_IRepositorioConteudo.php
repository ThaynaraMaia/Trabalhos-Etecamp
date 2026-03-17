<?php

include_once 'conn.php';
include_once 'class_conteudo.php';

interface IRepositoriOConteudos {
    
    public function alterarStatus($id_conteudos, $status);
    public function atualizarCaminhada($id_conteudos, $nome_caminhada, $caminhada);
    public function atualizarSono($id_conteudos, $nome_sono, $sono);
    public function atualizarAlongamento($id_conteudos, $nome_alongamento, $alongamento);
    public function atualizarRespiracao($id_conteudos, $nome_respiracao, $respiracao);
    public function atualizarDanca($id_conteudos, $nome_danca, $danca);
    public function atualizarHiit($id_conteudos, $nome_hiit, $hiit);
    public function atualizarSonobipolaridade($id_conteudos, $nome_sonobipolaridade, $sonobipolaridade);
    public function atualizarSonoregular($id_conteudos, $nome_sonoregular, $sonoregular);
    public function atualizarSonodicas($id_conteudos, $nome_sonodicas, $sonodicas);
    public function atualizarBipolaridade($id_conteudos, $nome_bipolaridade, $bipolaridade);
    public function atualizarPranayama($id_conteudos, $nome_pranayama, $pranayama);
    public function atualizarAsanas($id_conteudos, $nome_asanas, $asanas);
    public function atualizarRestaurativas($id_conteudos, $nome_restaurativa, $restaurativa);
    public function atualizarNidra($id_conteudos, $nome_nidra, $nidra);
    public function atualizarMeditacao($id_conteudos, $nome_meditacao, $meditacao);


    public function listarTodosHumor();
    public function listarTodosHumorcomum();
    public function listarTodosRespiracao();
    public function listarTodosRespiracaocomum();
    public function listarTodosDanca();
    public function listarTodosDancacomum();
    public function listarTodosCaminhada();
    public function listarTodosCaminhadacomum();
    public function listarTodosAlongamento();
    public function listarTodosAlongamentocomum();
    public function listarTodosHiit();
    public function listarTodosHiitcomum();
    public function listarTodosSonobipolaridade();
    public function listarTodosSonobipolaridadecomum();
    public function listarTodosSonoregular();
    public function listarTodosSonoregularcomum();
    public function listarTodosSonodicas();
    public function listarTodosSonodicascomum();
    public function obterUmhomeAleatorio();
    public function listarTodosPranayama();
    public function listarTodosPranayamacomum();
    public function listarTodosAsanas();
    public function listarTodosAsanascomum();
    public function listarTodosRestaurativas();
    public function listarTodosRestaurativascomum();
    public function listarTodosNidra();
    public function listarTodosNidracomum();
    public function listarTodosMeditacao();
    public function listarTodosMeditacaocomum();
    public function listarTodosBipolaridade();
    public function listarTodosBipolaridadecomum();
    
   
}

class RepositorioConteudosMYSQL implements IRepositorioConteudos  {

    private $conexao;

    public function __construct()
    {
        $this->conexao = new Conexao("localhost","root","","equilibrio");
        if($this->conexao->conectar() == false)
        {
            echo "Erro".mysqli_connect_error();
        }
    }


    public function cadastrarconteudo($conteudo)
    {
        
        $id_conteudos = $conteudo->getId_conteudos();
        $nome_conteudo = $conteudo->getNome_conteudo();
        $descricao = $conteudo->getdescricao();
        
        $sql = "INSERT INTO conteudo (id_conteudos,nome_conteudo,descricao)
         VALUES ('$id_conteudos','$nome_conteudo','$descricao')";

        $this->conexao->executarQuery($sql);

    }


    //atualiza

    public function alterarStatus($id_conteudos, $status)
    {
        $novoStatus = $status == 1 ? 0 : 1; // Alterna entre 0 e 1
        $sql = "UPDATE conteudos SET status = $novoStatus WHERE id_conteudos = $id_conteudos";
        return $this->conexao->executarQuery($sql); // Executa a query para atualizar o status
    }
    
    public function atualizarBipolaridade($id_conteudos, $nome_bipolaridade, $bipolaridade)
    {
            $sql = "UPDATE conteudos SET nome_bipolaridade = '$nome_bipolaridade', bipolaridade = '$bipolaridade' WHERE id_conteudos = $id_conteudos";
            echo $sql; // Verifique se a query está correta
            $alterar = $this->conexao->executarQuery($sql);
            return $alterar;
    }
    

    public function atualizarPranayama($id_conteudos, $nome_pranayama, $pranayama)
{
    $sql = "UPDATE conteudos SET nome_pranayama = '$nome_pranayama', pranayama = '$pranayama' WHERE id_conteudo = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarAsanas($id_conteudos, $nome_asanas, $asanas)
{
    $sql = "UPDATE conteudos SET nome_asanas = '$nome_asanas', asanas = '$asanas' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarRestaurativas($id_conteudos, $nome_restaurativas, $restaurativas)
{
    $sql = "UPDATE conteudos SET nome_restaurativas = '$nome_restaurativas', restaurativas = '$restaurativas' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarNidra($id_conteudos, $nome_nidra, $nidra)
{
    $sql = "UPDATE conteudos SET nome_nidra = '$nome_nidra', nidra = '$nidra' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarMeditacao($id_conteudos, $nome_meditacao, $meditacao)
{
    $sql = "UPDATE conteudos SET nome_meditacao = '$nome_meditacao', restaurativa = '$meditacao' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}


public function atualizarCaminhada($id_conteudos, $nome_caminhada, $caminhada)
{
    $sql = "UPDATE conteudos SET nome_caminhada = '$nome_caminhada', caminhada = '$caminhada' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarAlongamento($id_conteudos, $nome_alongamento, $alongamento)
{
    $sql = "UPDATE conteudos SET nome_alongamento = '$nome_alongamento', alongamento = '$alongamento' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarRespiracao($id_conteudos, $nome_respiracao, $respiracao)
{
    $sql = "UPDATE conteudos SET nome_respiracao = '$nome_respiracao', respiracao = '$respiracao' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarDanca($id_conteudos, $nome_danca, $danca)
{
    $sql = "UPDATE conteudos SET nome_danca = '$nome_danca', danca = '$danca' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarHiit($id_conteudos, $nome_hiit, $hiit)
{
    $sql = "UPDATE conteudos SET nome_hiit = '$nome_hiit', hiit = '$hiit' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarSono($id_conteudos, $nome_sono, $sono)
{
    $sql = "UPDATE conteudos SET nome_sono = '$nome_sono', sono = '$sono' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarSonobipolaridade($id_conteudos, $nome_sonobipolaridade, $sonobipolaridade)
{
    $sql = "UPDATE conteudos SET nome_sonobipolaridade = '$nome_sonobipolaridade', sonobipolaridade = '$sonobipolaridade' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarSonodicas($id_conteudos, $nome_sonodicas, $sonodicas)
{
    $sql = "UPDATE conteudos SET nome_sonodicas = '$nome_sonodicas', sonodicas = '$sonodicas' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

public function atualizarSonoregular($id_conteudos, $nome_sonoregular, $sonoregular)
{
    $sql = "UPDATE conteudos SET nome_sonoreguar = '$nome_sonoregular', sonoregular = '$sonoregular' WHERE id_conteudos = $id_conteudos";
    echo $sql; // Verifique se a query está correta
    $alterar = $this->conexao->executarQuery($sql);
    return $alterar;
}

    


//     public function listarTodosconteudo()
// {
//         $sql = "SELECT * FROM conteudo";

//         $registro = $this->conexao->executarQuery($sql);

//         return $registro;
// }

//listagem



public function listarTodosBipolaridade()
{
    $sql = "SELECT * FROM conteudos WHERE bipolaridade IS NOT NULL AND bipolaridade != '' AND nome_bipolaridade IS NOT NULL AND nome_bipolaridade != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}


public function listarTodosBipolaridadecomum() 
{
    $sql = "SELECT * FROM conteudos WHERE bipolaridade IS NOT NULL AND bipolaridade != '' AND nome_bipolaridade IS NOT NULL AND nome_bipolaridade != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosPranayama()
{
    $sql = "SELECT * FROM conteudos WHERE pranayama IS NOT NULL AND pranayama != '' AND nome_pranayama IS NOT NULL AND nome_pranayama != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}


public function listarTodosPranayamacomum() 
{
    $sql = "SELECT * FROM conteudos WHERE pranayama IS NOT NULL AND pranayama != '' AND nome_pranayama IS NOT NULL AND nome_pranayama != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosAsanas()
{
    $sql = "SELECT * FROM conteudos WHERE asanas IS NOT NULL AND asanas != '' AND nome_asanas IS NOT NULL AND nome_asanas != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}


public function listarTodosAsanascomum() 
{
    $sql = "SELECT * FROM conteudos WHERE asanas IS NOT NULL AND asanas != '' AND nome_asanas IS NOT NULL AND nome_asanas != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

    public function listarTodosRestaurativas()
{
    $sql = "SELECT * FROM conteudos WHERE restaurativas IS NOT NULL AND restaurativas != '' AND nome_restaurativas IS NOT NULL AND nome_restaurativas != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosRestaurativascomum() 
{
    $sql = "SELECT * FROM conteudos WHERE restaurativas IS NOT NULL AND restaurativas != '' AND nome_restaurativas IS NOT NULL AND nome_restaurativas != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosNidra()
{
    $sql = "SELECT * FROM conteudos WHERE nidra IS NOT NULL AND nidra != '' AND nome_nidra IS NOT NULL AND nome_nidra != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosNidracomum() 
{
    $sql = "SELECT * FROM conteudos WHERE nidra IS NOT NULL AND nidra != '' AND nome_nidra IS NOT NULL AND nome_nidra != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosMeditacao()
{
    $sql = "SELECT * FROM conteudos WHERE meditacao IS NOT NULL AND meditacao != '' AND nome_meditacao IS NOT NULL AND nome_meditacao != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosMeditacaocomum() 
{
    $sql = "SELECT * FROM conteudos WHERE meditacao IS NOT NULL AND meditacao != '' AND nome_meditacao IS NOT NULL AND nome_meditacao != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}


public function obterUmHumorAleatorio(){
    $sql = "SELECT humor FROM conteudos ORDER BY RAND() LIMIT 1";
    
    $resultado = $this->conexao->executarQuery($sql);
    
    return $resultado;
}

public function listarTodosHumor()
{
    $sql = "SELECT * FROM conteudos WHERE humor IS NOT NULL AND humor != '' AND nome_humor IS NOT NULL AND nome_humor != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosHumorcomum() 
{
$sql = "SELECT * FROM conteudos WHERE humor IS NOT NULL AND humor != '' AND nome_humor IS NOT NULL AND nome_humor != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}


public function listarTodosRespiracao()
{
    $sql = "SELECT * FROM conteudos WHERE respiracao IS NOT NULL AND respiracao != '' AND nome_respiracao IS NOT NULL AND nome_respiracao != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}


public function listarTodosRespiracaocomum() 
{
    $sql = "SELECT * FROM conteudos WHERE respiracao IS NOT NULL AND respiracao != '' AND nome_respiracao IS NOT NULL AND nome_respiracao != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosDanca()
{
    $sql = "SELECT * FROM conteudos WHERE danca IS NOT NULL AND danca != '' AND nome_danca IS NOT NULL AND nome_danca != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}


public function listarTodosDancacomum() 
{
    $sql = "SELECT * FROM conteudos WHERE danca IS NOT NULL AND danca != '' AND nome_danca IS NOT NULL AND nome_danca != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

    public function listarTodosCaminhada() ///////////
{
    $sql = "SELECT * FROM conteudos WHERE caminhada IS NOT NULL AND caminhada != '' AND nome_caminhada IS NOT NULL AND nome_caminhada != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosCaminhadacomum() 
{
    $sql = "SELECT * FROM conteudos WHERE caminhada IS NOT NULL AND caminhada != '' AND nome_caminhada IS NOT NULL AND nome_caminhada != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}
  
public function listarTodosAlongamento() 
{
    $sql = "SELECT * FROM conteudos WHERE alongamento IS NOT NULL AND alongamento != '' AND nome_alongamento IS NOT NULL AND nome_alongamento != ''";


    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}  

public function listarTodosAlongamentocomum() 
{
    $sql = "SELECT * FROM conteudos WHERE alongamento IS NOT NULL AND alongamento != '' AND nome_alongamento IS NOT NULL AND nome_alongamento != '' AND status = 1";
    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosHiit() 
{
    $sql = "SELECT * FROM conteudos WHERE hiit IS NOT NULL AND hiit != '' AND nome_hiit IS NOT NULL AND nome_hiit != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}  

public function listarTodosHiitcomum() 
{
    $sql = "SELECT * FROM conteudos WHERE hiit IS NOT NULL AND hiit != '' AND nome_hiit IS NOT NULL AND nome_hiit != '' AND status = 1";
    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosSonobipolaridade()
{
    $sql = "SELECT * FROM conteudos WHERE sonobipolaridade IS NOT NULL AND sonobipolaridade != '' AND nome_sonobipolaridade IS NOT NULL AND nome_sonobipolaridade != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
} 

public function listarTodosSonobipolaridadecomum() 
{
    $sql = "SELECT * FROM conteudos WHERE sonobipolaridade IS NOT NULL AND sonobipolaridade != '' AND nome_sonobipolaridade IS NOT NULL AND nome_sonobipolaridade != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosSonodicas()
{
    $sql = "SELECT * FROM conteudos WHERE sonodicas IS NOT NULL AND sonodicas != '' AND nome_sonodicas IS NOT NULL AND nome_sonodicas != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosSonodicascomum() 
{
    $sql = "SELECT * FROM conteudos WHERE sonodicas IS NOT NULL AND sonodicas != '' AND nome_sonodicas IS NOT NULL AND nome_sonodicas != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

    public function listarTodosSonoregular() ///////////
{
    $sql = "SELECT * FROM conteudos WHERE sonoregular IS NOT NULL AND sonoregular != '' AND nome_sonoregular IS NOT NULL AND nome_sonoregular != ''";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}

public function listarTodosSonoregularcomum() 
{
    $sql = "SELECT * FROM conteudos WHERE sonoregular IS NOT NULL AND sonoregular != '' AND nome_sonoregular IS NOT NULL AND nome_sonoregular != '' AND status = 1";

    $registro = $this->conexao->executarQuery($sql);

    return $registro;
}


public function obterUmhomeAleatorio(){
    $sql = "SELECT home FROM conteudos ORDER BY RAND() LIMIT 1";
    
    $resultado = $this->conexao->executarQuery($sql);
    
    return $resultado;
}

}

$respositorioConteudos = new RepositorioConteudosMYSQL(); // criar na classe pois assim não é preciso criar em todas as scripts.

?>