<?php
include_once '../../classes/class_IRepositorioOng.php';

if (!isset($_GET['id'])) {
    echo "ID da ONG não informado.";
    exit;
}

$repositorio = new RepositorioOngMYSQL();
$ong = $repositorio->buscarPorId($_GET['id']); // precisa existir no repositório

if (!$ong) {
    echo "ONG não encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar ONG</title>
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <style>
        input::placeholder {
            color: #7a4100;
            font-size: 18px;
            padding: 10px;
        }

        h1 {
            text-align: center;
            margin: 20px;
            padding-bottom: 20px;
            color: #4E6422;
            font-size: 55px;
        }

        .cadastro {
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
            margin-top: 40px;
            background-color: #eee9c1ff;
            padding-right: 90px;
            width: 1250px;
            margin-left: 20px;
            padding-left: 80px;
        }

        .input {
            width: 1000px;
            padding: 15px;
            margin: 10px;
            margin-left: 65px;
            border-radius: 20px;
            border: none;
            background: white;
        }

        /* .formulario {
            margin-top: 10px;
        } */

        label {
            color: #7a4100;
            font-size: 18px;
        }

        textarea {
            border-radius: 25px;
            margin-left: 13px;
            margin-top: 8px;
            margin-bottom: 8px;
            width: 700px;
            height: 170px;
            background: white;
            border: none;
            margin-left: 1px;
        }

        textarea::placeholder {
            font-size: 18px;
            padding: 10px;
            color: #7a4100;
        }

        .botao {
            border: 1px solid #fff;
            background: linear-gradient(90deg, #b3dd5f 0%, #f1ba7b 100%);
            color: #7a4100;
            padding: 15px;
            width: 800px;
            text-align: center;
            margin-left: 10px;
            margin-top: 30px;
            border-radius: 20px;
            margin-left: 65px;
            font-weight: bold;
            font-size: 23px;
            position: relative;
            overflow: hidden;
        }

        .botao::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .botao:hover::before {
            left: 100%;
        }

        .botao:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .botao2 {
            border: 1px solid #fff;
            background: #b3dd5f;
            padding: 15px;
            width: 180px;
            text-align: center;
            margin-left: 40px;
            margin-top: 30px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 23px;
            position: relative;
            overflow: hidden;
        }

        .botao2::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .botao2:hover::before {
            left: 100%;
        }

        .botao2:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .linkbtn {
            text-decoration: none;
            color: #4E6422;
            font-weight: bold;
        }

        .cadastrar {
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .status {
            width: 1000px;
            padding: 15px;
            margin: 10px;
            margin-left: 65px;
            border-radius: 20px;
            border: none;
            background: white;
        }

        .telefone {
            width: 1000px;
            background-color: #fff;
            padding: 15px;
            border: none;
            margin-left: 65px;
            margin-bottom: 8px;
            border-radius: 20px;
        }

        .input2 {
            border: none;
            width: 850px;
            padding: 3px;
        }

        .fotoONG{
            max-width:170px; 
            display:block; 
            margin-bottom:10px;
            border-radius: 20px
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="formulario" style="display: flex;align-items: center; justify-content: center;">

            <form action="processa_edicao_parceiros.php" method="post" enctype="multipart/form-data">
                <div class="cadastro">
                    <h1>Editar ONG</h1>

                    <div class="areas">
                        <input type="hidden" name="idOng" value="<?= htmlspecialchars($ong->getIdOng()) ?>">

                        <!-- Foto -->
                        <div class="mb-3">
                            <label class="form-label">Foto Atual</label><br>
                            <?php if ($ong->getFotoOng()): ?>

                                <img src="../../../<?= htmlspecialchars($ong->getFotoOng()) ?>" class="fotoONG" alt="Foto ONG" >
                            <?php else: ?>
                                <p>Sem foto cadastrada</p>
                            <?php endif; ?>
                            <label for="foto" class="form-label">Alterar Foto</label>
                            <input type="file" id="foto" name="foto" class="form-control">
                        </div>

                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da ONG</label>
                            <input type="text" id="nome" name="nomeOng"
                                class="form-control"
                                value="<?= htmlspecialchars($ong->getNomeOng()) ?>" required>
                        </div>

                        <!-- Fundação -->
                        <div class="mb-3">
                            <label for="fundacao" class="form-label">Fundação</label>
                            <input type="date" id="fundacao" name="fundacaoOng"
                                class="form-control"
                                value="<?= htmlspecialchars($ong->getFundacaoOng()) ?>" required>
                        </div>

                        <!-- História -->
                        <div class="mb-3">
                            <label for="historia" class="form-label">História</label>
                            <textarea id="historia" name="historiaOng" class="form-control" rows="5"><?= htmlspecialchars($ong->getHistoriaOng()) ?></textarea>
                        </div>

                        <!-- Telefones -->
                        <div class="mb-3">
                            <label class="form-label">Telefones</label>
                            <div id="telefones">
                                <?php foreach ($ong->getTelefones() as $i => $tel): ?>
                                    <div class="input-group mb-2">
                                        <input type="text" name="telefones[]" class="form-control"
                                            value="<?= is_array($tel) ? htmlspecialchars($tel['telefone']) : htmlspecialchars($tel) ?>">
                                        <input type="text" name="tipos[]" class="form-control"
                                            placeholder="Tipo (ex: Celular, Fixo)"
                                            value="<?= is_array($tel) ? htmlspecialchars($tel['tipo']) : '' ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Endereços -->
                        <div class="mb-3">
                            <label class="form-label">Endereços</label>
                            <div id="enderecos">
                                <?php foreach ($ong->getEnderecos() as $end): ?>
                                    <div class="border p-2 mb-2">
                                        <input style="margin-bottom: 30px;" type="text" name="enderecos[rua][]" class="form-control mb-1" placeholder="Rua" value="<?= htmlspecialchars($end['rua'] ?? '') ?>">
                                        <input type="text" name="enderecos[numero][]" class="form-control mb-1" placeholder="Número" value="<?= htmlspecialchars($end['numero'] ?? '') ?>">
                                        <input type="text" name="enderecos[complemento][]" class="form-control mb-1" placeholder="Complemento" value="<?= htmlspecialchars($end['complemento'] ?? '') ?>">
                                        <input type="text" name="enderecos[bairro][]" class="form-control mb-1" placeholder="Bairro" value="<?= htmlspecialchars($end['bairro'] ?? '') ?>">
                                        <input type="text" name="enderecos[cidade][]" class="form-control mb-1" placeholder="Cidade" value="<?= htmlspecialchars($end['cidade'] ?? '') ?>">
                                        <input type="text" name="enderecos[estado][]" class="form-control mb-1" placeholder="Estado" value="<?= htmlspecialchars($end['estado'] ?? '') ?>">
                                        <input type="text" name="enderecos[cep][]" class="form-control mb-1" placeholder="CEP" value="<?= htmlspecialchars($end['cep'] ?? '') ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>

                    <div class="cadastrar">
                        <button class="btn botao" type="submit">Salvar alterações</button>
                        <button class="botao2"><a href="../adm/gong.php" class="linkbtn">Cancelar</a></button>
                    </div>


                </div>

            </form>
        </div>

    </div>


    <script>
        function addTelefone() {
            let div = document.createElement("div");
            div.classList.add("input-group", "mb-2");
            div.innerHTML = `
        <input type="text" name="telefones[]" class="form-control" placeholder="Telefone">
        <input type="text" name="tipos[]" class="form-control" placeholder="Tipo (ex: Celular, Fixo)">
    `;
            document.getElementById("telefones").appendChild(div);
        }

        function addEndereco() {
            let div = document.createElement("div");
            div.classList.add("border", "p-2", "mb-2");
            div.innerHTML = `
        <input type="text" name="enderecos[rua][]" class="form-control mb-1" placeholder="Rua">
        <input type="text" name="enderecos[numero][]" class="form-control mb-1" placeholder="Número">
        <input type="text" name="enderecos[complemento][]" class="form-control mb-1" placeholder="Complemento">
        <input type="text" name="enderecos[bairro][]" class="form-control mb-1" placeholder="Bairro">
        <input type="text" name="enderecos[cidade][]" class="form-control mb-1" placeholder="Cidade">
        <input type="text" name="enderecos[estado][]" class="form-control mb-1" placeholder="Estado">
        <input type="text" name="enderecos[cep][]" class="form-control mb-1" placeholder="CEP">
    `;
            document.getElementById("enderecos").appendChild(div);
        }
    </script>

</body>

</html>