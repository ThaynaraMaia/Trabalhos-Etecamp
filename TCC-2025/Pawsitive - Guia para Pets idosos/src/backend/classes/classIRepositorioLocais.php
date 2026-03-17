<?php

include_once 'class_conexao.php';
include_once 'class_Locais.php';
include_once 'class_EnderecoLocais.php';

interface IRepositorioLocal
{
    public function listarTodosLocais();
    public function removerLocal($idLocal);
    public function inserirLocal(Local $local);
    public function atualizarLocal(Local $local);
    public function buscarPorId($idLocal);
}

class RepositorioLocalMYSQL implements IRepositorioLocal
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = new mysqli("localhost", "root", "", "pawsitive");
        if ($this->conexao->connect_errno) {
            die("Erro ao conectar: " . $this->conexao->connect_error);
        }
    }

    public function listarTodosLocais()
    {
        $sql = "SELECT l.*, e.rua, e.numero, e.bairro, e.cidade, e.cep, e.estado, e.complemento, e.id as endereco_id 
                FROM tbllocais l
                INNER JOIN tbllocal_endereco e ON l.endereco_id = e.id";

        $resultado = $this->conexao->query($sql);
        $locais = [];

        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $endereco = new Endereco(
                    $row['endereco_id'],
                    $row['rua'],
                    $row['numero'],
                    $row['bairro'],
                    $row['cidade'],
                    $row['cep'],
                    $row['estado'],
                    $row['complemento']
                );

                $local = new Local(
                    $row['id_local'],
                    $row['nome_local'],
                    $row['descricao_local'],
                    $endereco,
                    $row['horario_abertura'],
                    $row['horario_fechamento'],
                    $row['tipo']
                );

                $locais[] = $local;
            }
        }
        return $locais;
    }

    public function removerLocal($idLocal)
    {
        // Primeiro remover o endereço (se necessário) e depois o local
        // Para simplificar, vamos remover só o local, assumindo que o endereço pode ser compartilhado.

        $stmt = $this->conexao->prepare("DELETE FROM tbllocais WHERE id_local = ?");
        if (!$stmt) {
            die("Erro na preparação: " . $this->conexao->error);
        }

        $stmt->bind_param("i", $idLocal);
        $executou = $stmt->execute();

        if (!$executou) {
            die("Erro na execução: " . $stmt->error);
        }

        $stmt->close();
        return $executou;
    }

    public function inserirLocal(Local $local)
    {
        // Inserir primeiro o endereço
        $endereco = $local->getEndereco();

        // Inserir endereço
        $stmtEnd = $this->conexao->prepare("
            INSERT INTO tbllocal_endereco (rua, numero, bairro, cidade, cep, estado, complemento)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmtEnd) {
            echo "Erro na preparação endereço: " . $this->conexao->error;
            return false;
        }

        $rua = $endereco->getRua();
        $numero = $endereco->getNumero();
        $bairro = $endereco->getBairro();
        $cidade = $endereco->getCidade();
        $cep = $endereco->getCep();
        $estado = $endereco->getEstado();
        $complemento = $endereco->getComplemento();

        $stmtEnd->bind_param(
            "sisssss",
            $rua,
            $numero,
            $bairro,
            $cidade,
            $cep,
            $estado,
            $complemento
        );

        // $stmtEnd->bind_param(
        //     "sisssss",
        //     $endereco->getRua(),
        //     $endereco->getNumero(),
        //     $endereco->getBairro(),
        //     $endereco->getCidade(),
        //     $endereco->getCep(),
        //     $endereco->getEstado(),
        //     $endereco->getComplemento()
        // );

        if (!$stmtEnd->execute()) {
            echo "Erro ao salvar endereço: " . $stmtEnd->error;
            $stmtEnd->close();
            return false;
        }

        $idEndereco = $stmtEnd->insert_id;
        $stmtEnd->close();

        // Agora inserir o local
        $stmtLocal = $this->conexao->prepare("
            INSERT INTO tbllocais (nome_local, descricao_local, endereco_id, horario_abertura, horario_fechamento, tipo)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        if (!$stmtLocal) {
            echo "Erro na preparação local: " . $this->conexao->error;
            return false;
        }

        $nomeLocal = $local->getNomeLocal();
        $descricaoLocal = $local->getDescricaoLocal();
        $horarioAbertura = $local->getHorarioAbertura();
        $horarioFechamento = $local->getHorarioFechamento();
        $tipo = $local->getTipo();

        $stmtLocal->bind_param(
            "ssisss",
            $nomeLocal,
            $descricaoLocal,
            $idEndereco,
            $horarioAbertura,
            $horarioFechamento,
            $tipo
        );

        if (!$stmtLocal->execute()) {
            echo "Erro ao salvar local: " . $stmtLocal->error;
            $stmtLocal->close();
            return false;
        }

        $idLocal = $stmtLocal->insert_id;
        $local->setIdLocal($idLocal);

        $stmtLocal->close();

        return true;
    }

    public function atualizarLocal(Local $local)
    {
        $idLocal = $local->getIdLocal();
        $endereco = $local->getEndereco();
        $idEndereco = $endereco->getId();

        // Atualiza endereço
        $stmtEnd = $this->conexao->prepare("
            UPDATE tbllocal_endereco SET rua = ?, numero = ?, bairro = ?, cidade = ?, cep = ?, estado = ?, complemento = ?
            WHERE id = ?
        ");

        if (!$stmtEnd) {
            die("Erro na preparação do endereço: " . $this->conexao->error);
        }

        $rua = $endereco->getRua();
        $numero = $endereco->getNumero();
        $bairro = $endereco->getBairro();
        $cidade = $endereco->getCidade();
        $cep = $endereco->getCep();
        $estado = $endereco->getEstado();
        $complemento = $endereco->getComplemento();
        $idEndereco = $endereco->getId();

        $stmtEnd->bind_param(
            "sisssssi",
            $rua,
            $numero,
            $bairro,
            $cidade,
            $cep,
            $estado,
            $complemento,
            $idEndereco
        );

        // $stmtEnd->bind_param(
        //     "sisssssi",
        //     $endereco->getRua(),
        //     $endereco->getNumero(),
        //     $endereco->getBairro(),
        //     $endereco->getCidade(),
        //     $endereco->getCep(),
        //     $endereco->getEstado(),
        //     $endereco->getComplemento(),
        //     $idEndereco
        // );

        if (!$stmtEnd->execute()) {
            $stmtEnd->close();
            die("Erro na execução do endereço: " . $stmtEnd->error);
        }

        $stmtEnd->close();

        // Atualiza local
        $stmtLocal = $this->conexao->prepare("
            UPDATE tbllocais SET nome_local = ?, descricao_local = ?, horario_abertura = ?, horario_fechamento = ?, tipo = ?
            WHERE id_local = ?
        ");

        if (!$stmtLocal) {
            die("Erro na preparação do local: " . $this->conexao->error);
        }

        $nomeLocal = $local->getNomeLocal();
        $descricaoLocal = $local->getDescricaoLocal();
        $horarioAbertura = $local->getHorarioAbertura();
        $horarioFechamento = $local->getHorarioFechamento();
        $tipo = $local->getTipo();

        $stmtLocal->bind_param(
            "sssssi",
            $nomeLocal,
            $descricaoLocal,
            $horarioAbertura,
            $horarioFechamento,
            $tipo,
            $idLocal
        );

        // $stmtLocal->bind_param(
        //     "sssssi",
        //     $local->getNomeLocal(),
        //     $local->getDescricaoLocal(),
        //     $local->getHorarioAbertura(),
        //     $local->getHorarioFechamento(),
        //     $local->getTipo(),
        //     $idLocal
        // );

        if (!$stmtLocal->execute()) {
            $stmtLocal->close();
            die("Erro na execução do local: " . $stmtLocal->error);
        }

        $stmtLocal->close();

        return true;
    }

    public function buscarPorId($idLocal)
    {
        $stmt = $this->conexao->prepare("
            SELECT l.*, e.rua, e.numero, e.bairro, e.cidade, e.cep, e.estado, e.complemento, e.id as endereco_id
            FROM tbllocais l
            INNER JOIN tbllocal_endereco e ON l.endereco_id = e.id
            WHERE l.id_local = ?
        ");

        if (!$stmt) {
            die("Erro na preparação: " . $this->conexao->error);
        }

        $stmt->bind_param("i", $idLocal);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $local = null;

        if ($row = $resultado->fetch_assoc()) {
            $endereco = new Endereco(
                $row['endereco_id'],
                $row['rua'],
                $row['numero'],
                $row['bairro'],
                $row['cidade'],
                $row['cep'],
                $row['estado'],
                $row['complemento']
            );

            $local = new Local(
                $row['id_local'],
                $row['nome_local'],
                $row['descricao_local'],
                $endereco,
                $row['horario_abertura'],
                $row['horario_fechamento'],
                $row['tipo']
            );
        }

        $stmt->close();
        return $local;
    }
}
