<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../../../bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="gusuarios.css">
    <title>Cadastrar ONG</title>
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

        .telefone{
            width: 1000px;
            background-color: #fff;
            padding: 15px;
            border: none;
            margin-left: 65px;
            margin-bottom: 8px;
            border-radius: 20px;
        }

        .input2{
            border: none;
            width: 850px;
            padding: 3px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="formulario" style="display: flex;align-items: center; justify-content: center;">
            <form action="cadOng2.php" method="post" enctype="multipart/form-data" style="margin-top: 10px;">
                <div class="cadastro">
                    <h1>Cadastrar ONG</h1>

                    <div class="ares">
                        <!-- <label>Nome da ONG:</label><br /> -->
                        <input type="text" name="nome_ong" placeholder="Nome" class="input" required /><br />

                        <!-- <label>Ano Fundação:</label><br /> -->
                        <input type="number" name="fundacao_ong" placeholder="Ano de Fundação" class="input" required min="1800" max="<?= date('Y') ?>" /><br />

                        <!-- <label>História da ONG:</label><br /> -->
                        <textarea name="historia_ong" rows="5" cols="40" placeholder="Historia da Ong" class="input" required></textarea><br />

                        <!-- <label>Foto da ONG:</label><br /> -->
                        <input type="file" name="foto_ong" class="input" accept="image/*" required /><br /><br />

                        <!-- <label>Telefone(s):</label><br /> -->
                        <div class="telefone">
                            <input type="text" name="telefone[]" placeholder="Telefone" class="input2" required />
                            <select name="tipo_telefone[]">
                                <option value="comercial">Comercial</option>
                                <option value="celular">Celular</option>
                                <option value="outro">Outro</option>
                            </select>
                            <br />
                        </div>



                        <!-- <label>Endereço(s):</label><br /> -->
                        <input type="text" name="rua[]" placeholder="Rua" class="input" required />
                        <input type="text" name="numero[]" placeholder="Número" class="input" required />
                        <input type="text" class="input" name="complemento[]" placeholder="Complemento" />
                        <input type="text" name="cidade[]" placeholder="Cidade" class="input" required />
                        <input type="text" name="estado[]" placeholder="Estado" class="input" required />
                        <input type="text" name="cep[]" placeholder="CEP" class="input" required />
                        <br />
                    </div>


                    <div class="cadastrar">
                        <button type="submit" class="btn botao">Cadastrar</button>
                        <button class="botao2"><a href="../adm/gong.php" class="linkbtn">Voltar</a></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>