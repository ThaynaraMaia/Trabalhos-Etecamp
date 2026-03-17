<?php

include_once 'class_conexao.php';
include_once 'class_Ong.php';

interface IRepositorioOng
{
    public function listarTodasOngs();
    public function removerOng($idOng);
    public function inserirOng(Ong $ong);
    public function atualizarOng(Ong $ong);
    public function buscarPorId($idOng);
}

class RepositorioOngMYSQL implements IRepositorioOng
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = new mysqli("localhost", "root", "", "pawsitive");
        if ($this->conexao->connect_errno) {
            die("Erro ao conectar: " . $this->conexao->connect_error);
        }
    }

    public function listarTodasOngs()
    {
        $sql = "SELECT * FROM tblong";
        $resultado = $this->conexao->query($sql);
        $ongs = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $ong = new Ong(
                    $row['id_ong'],
                    $row['nome_ong'],
                    $row['fundacao_ong'],
                    $row['historia_ong'],
                    $row['foto_ong'],
                    $this->buscarTelefones($row['id_ong']),
                    $this->buscarEnderecos($row['id_ong'])
                );
                $ongs[] = $ong;
            }
        }
        return $ongs;
    }

    public function removerOng($idOng)
    {
        $stmt = $this->conexao->prepare("DELETE FROM tblong WHERE id_ong = ?");
        if (!$stmt) {
            die("Erro na preparação: " . $this->conexao->error);
        }
        $stmt->bind_param("i", $idOng);
        $executou = $stmt->execute();
        if (!$executou) {
            die("Erro na execução: " . $stmt->error);
        }
        $stmt->close();
        return $executou;
    }

    public function inserirOng(Ong $ong)
    {
        $stmt = $this->conexao->prepare("
        INSERT INTO tblong (nome_ong, fundacao_ong, historia_ong, foto_ong)
        VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            echo "Erro na preparação: " . $this->conexao->error;
            return false;
        }

        $nome = $ong->getNomeOng();
        $fundacao = $ong->getFundacaoOng();
        $historia = $ong->getHistoriaOng();
        $foto = $ong->getFotoOng();

        $stmt->bind_param("siss", $nome, $fundacao, $historia, $foto);

        $executou = $stmt->execute();
        if (!$executou) {
            echo "Erro na execução: " . $stmt->error;
            $stmt->close();
            return false;
        }

        $idOng = $stmt->insert_id;
        $ong->setIdOng($idOng);

        $stmt->close();

        // Salvar telefones
        $telefones = $ong->getTelefones();
        if (!empty($telefones)) {
            $stmtTel = $this->conexao->prepare("INSERT INTO tblong_telefones (id_ong, telefone, tipo_telefone) VALUES (?, ?, ?)");
            if (!$stmtTel) {
                echo "Erro na preparação telefones: " . $this->conexao->error;
                return false;
            }
            foreach ($telefones as $t) {
                $telefones = $_POST['telefone'] ?? [];

                foreach ($telefones as $telefone) {
                    if (!empty($telefone)) {
                        // salve no banco aqui
                    }
                }
                $tipos = $_POST['tipo_telefone'] ?? [];

                foreach ($telefones as $index => $telefone) {
                    $tipo = $tipos[$index] ?? 'comercial';

                    if (!empty($telefone)) {
                        // salvar no banco
                    }
                }
                $stmtTel->bind_param("iss", $idOng, $telefone, $tipo);
                if (!$stmtTel->execute()) {
                    echo "Erro ao salvar telefone: " . $stmtTel->error;
                    $stmtTel->close();
                    return false;
                }
            }
            $stmtTel->close();
        }

        // Salvar endereços
        $enderecos = $ong->getEnderecos();
        if (!empty($enderecos)) {
            $stmtEnd = $this->conexao->prepare("INSERT INTO tblong_enderecos (id_ong, rua, numero, complemento, cidade, estado, cep) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmtEnd) {
                echo "Erro na preparação endereços: " . $this->conexao->error;
                return false;
            }
            foreach ($enderecos as $endereco) {
                $ruas = $_POST['rua'] ?? [];
                $numeros = $_POST['numero'] ?? [];
                $complementos = $_POST['complemento'] ?? [];
                $cidades = $_POST['cidade'] ?? [];
                $estados = $_POST['estado'] ?? [];
                $ceps = $_POST['cep'] ?? [];

                foreach ($ruas as $i => $rua) {
                    $numero = $numeros[$i] ?? '';
                    $complemento = $complementos[$i] ?? '';
                    $cidade = $cidades[$i] ?? '';
                    $estado = $estados[$i] ?? '';
                    $cep = $ceps[$i] ?? '';

                    if (!empty($rua) && !empty($numero) && !empty($cidade) && !empty($estado) && !empty($cep)) {
                        // Salvar no banco
                    } else {
                        echo "Endereço incompleto na linha $i.";
                    }
                }
                $stmtEnd->bind_param("issssss", $idOng, $rua, $numero, $complemento, $cidade, $estado, $cep);
                if (!$stmtEnd->execute()) {
                    echo "Erro ao salvar endereço: " . $stmtEnd->error;
                    $stmtEnd->close();
                    return false;
                }
            }
            $stmtEnd->close();
        }

        return true;
    }

    public function atualizarOng(Ong $ong)
    {
        $stmt = $this->conexao->prepare("
        UPDATE tblong SET nome_ong = ?, fundacao_ong = ?, historia_ong = ?, foto_ong = ?
        WHERE id_ong = ?
    ");
        if (!$stmt) {
            die("Erro na preparação: " . $this->conexao->error);
        }

        $nome = $ong->getNomeOng();
        $fundacao = $ong->getFundacaoOng();
        $historia = $ong->getHistoriaOng();
        $foto = $ong->getFotoOng();
        $idOng = $ong->getIdOng();

        $stmt->bind_param("ssssi", $nome, $fundacao, $historia, $foto, $idOng);

        $executou = $stmt->execute();
        if (!$executou) {
            $stmt->close();
            die("Erro na execução: " . $stmt->error);
        }

        $stmt->close();

        // Remove os telefones e endereços antigos antes de salvar novos
        $this->removerTelefones($idOng);
        $this->removerEnderecos($idOng);

        // Salva os telefones e endereços atualizados
        $this->salvarTelefones($ong);
        $this->salvarEnderecos($ong);

        return true;
    }

    public function buscarPorId($idOng)
    {
        $stmt = $this->conexao->prepare("SELECT * FROM tblong WHERE id_ong = ?");
        if (!$stmt) {
            die("Erro na preparação: " . $this->conexao->error);
        }
        $stmt->bind_param("i", $idOng);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $ong = null;
        if ($row = $resultado->fetch_assoc()) {
            $ong = new Ong(
                $row['id_ong'],
                $row['nome_ong'],
                $row['fundacao_ong'],
                $row['historia_ong'],
                $row['foto_ong'],
                $this->buscarTelefones($row['id_ong']),
                $this->buscarEnderecos($row['id_ong'])
            );
        }
        $stmt->close();
        return $ong;
    }

    private function buscarTelefones($idOng)
    {
        $stmt = $this->conexao->prepare("SELECT telefone, tipo_telefone FROM tblong_telefones WHERE id_ong = ?");
        $telefones = [];
        if ($stmt) {
            $stmt->bind_param("i", $idOng);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while ($row = $resultado->fetch_assoc()) {
                $telefones[] = ['telefone' => $row['telefone'], 'tipo' => $row['tipo_telefone']];
            }
            $stmt->close();
        }
        return $telefones;
    }

    private function buscarEnderecos($idOng)
    {
        $stmt = $this->conexao->prepare("SELECT rua, numero, complemento, cidade, estado, cep FROM tblong_enderecos WHERE id_ong = ?");
        $enderecos = [];
        if ($stmt) {
            $stmt->bind_param("i", $idOng);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while ($row = $resultado->fetch_assoc()) {
                $enderecos[] = $row;
            }
            $stmt->close();
        }
        return $enderecos;
    }

    private function salvarTelefones(Ong $ong)
    {
        $telefones = $ong->getTelefones();
        if (empty($telefones)) {
            return;
        }

        $stmt = $this->conexao->prepare("INSERT INTO tblong_telefones (id_ong, telefone, tipo_telefone) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Erro na preparação dos telefones: " . $this->conexao->error);
        }

        $idOng = $ong->getIdOng();

        foreach ($telefones as $t) {
            $telefone = is_array($t) ? $t['telefone'] : $t;
            $tipo = is_array($t) && isset($t['tipo']) ? $t['tipo'] : 'comercial';

            $stmt->bind_param("iss", $idOng, $telefone, $tipo);

            $executou = $stmt->execute();
            if (!$executou) {
                $stmt->close();
                die("Erro ao salvar telefone: " . $stmt->error);
            }
        }

        $stmt->close();
    }

    private function salvarEnderecos(Ong $ong)
    {
        $enderecos = $ong->getEnderecos();
        if (empty($enderecos)) {
            return;
        }

        $stmt = $this->conexao->prepare("INSERT INTO tblong_enderecos (id_ong, rua, numero, complemento, cidade, estado, cep) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Erro na preparação dos endereços: " . $this->conexao->error);
        }

        $idOng = $ong->getIdOng();

        foreach ($enderecos as $endereco) {
            $rua = $endereco['rua'];
            $numero = $endereco['numero'];
            $complemento = $endereco['complemento'];
            $cidade = $endereco['cidade'];
            $estado = $endereco['estado'];
            $cep = $endereco['cep'];

            $stmt->bind_param("issssss", $idOng, $rua, $numero, $complemento, $cidade, $estado, $cep);

            $executou = $stmt->execute();
            if (!$executou) {
                $stmt->close();
                die("Erro ao salvar endereço: " . $stmt->error);
            }
        }

        $stmt->close();
    }

    private function removerTelefones($idOng)
    {
        $stmt = $this->conexao->prepare("DELETE FROM tblong_telefones WHERE id_ong = ?");
        if ($stmt) {
            $stmt->bind_param("i", $idOng);
            $stmt->execute();
            $stmt->close();
        }
    }

    private function removerEnderecos($idOng)
    {
        $stmt = $this->conexao->prepare("DELETE FROM tblong_enderecos WHERE id_ong = ?");
        if ($stmt) {
            $stmt->bind_param("i", $idOng);
            $stmt->execute();
            $stmt->close();
        }
    }
}

$repositorioOng = new RepositorioOngMYSQL();
