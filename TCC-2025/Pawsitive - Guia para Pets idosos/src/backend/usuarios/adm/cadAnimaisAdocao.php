<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="gusuarios.css">
    <title>Cadastro Animal</title>
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
            padding-right: 100px;
        }

        input {
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
            border-radius: 15px;
            margin-left: 13px;
            margin-top: 8px;
            margin-bottom: 8px;
            width: 700px;
            height: 170px;
            background: white;
            border: none;
            margin-left: 65px;
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
            margin-right: 30px;
            margin-top: 30px;
            border-radius: 20px;
            margin-left: 40px;
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
            margin-left: 10px;
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

        .linkbtn{
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
    </style>
</head>

<body>
    <div class="container-fluid">

        <div class="formulario" style="display: flex;align-items: center; justify-content: center;">
            <form action="cadAnimaisAdocao2.php" method="post" enctype="multipart/form-data" style="margin-top: 10px;">
                <div class="cadastro">
                    <h1>Cadastrar animal</h1>

                    <div class="areas">
                        <!-- nome -->
                        <input type="text" name="nome_animal" placeholder="Nome" required><br>

                        <!-- caracteristicas -->
                        <input type="text" name="caracteristicas_animal" placeholder="Características" required><br>

                        <!-- cidade -->
                        <input type="text" name="cidade_animal" placeholder="Cidade" required><br>

                        <!-- descrição -->
                        <textarea name="descricao_animal" placeholder="Descrição" required></textarea><br>

                        <!-- genero -->
                        <input type="text" name="genero_animal" placeholder="Gênero" required><br>

                        <!-- especie -->
                        <input type="text" name="especie_animal" placeholder="Espécie" required><br>

                        <!-- idade -->
                        <input type="number" name="idade_animal" placeholder="Idade" required><br>

                        <!-- Condição de Saúde -->
                        <input type="text" name="condicao_saude" placeholder="Condição de Saúde" required><br>

                        <!-- Foto -->
                        <input type="file" name="foto_animal" accept="image/*" required><br>

                        <div class="status">
                            <label>Status:</label>
                            <select name="status_animal" required>
                                <option value="para/adocao">Para Adoção</option>
                                <option value="adotado">Adotado</option>
                                <option value="tratamento">Tratamento</option>
                            </select><br>
                        </div>

                    </div>

                    <div class="cadastrar">
                        <button type="submit" class="btn botao">Cadastrar</button>
                        <button class="botao2"><a href="../adm/ganimais.php" class="linkbtn">Voltar</a></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>