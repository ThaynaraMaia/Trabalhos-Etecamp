<!DOCTYPE html>
<html lang="pt-br">
<?php

session_start();

if (!$_SESSION['tipo']  && !$_SESSION['logado']) {
    header('Location:../../../../frontend/home.php');
}

$usuario = $_SESSION['nome'];
include_once '../../../../classes/class_IRepositorioUsuarios.php';
include_once '../../../../classes/class_IRepositorioQuiz.php';
$id = $_SESSION['id_usuario'];


// Busca os dados do usuário
$dados = $respositorioUsuario->buscarUsuario($id);
// Foto padrão se não tiver
$foto = !empty($dados['foto']) ? $dados['foto'] : 'frontend/public/img/fotoperfil.png';

$listar = $respositorioQuiz->listarPerguntasPorBiologo($id);


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../homeAdm.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/baseGeral.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/logo.css">
    <link rel="stylesheet" href="../../../../../frontend/public/css/footer.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Biblioteca Scroll -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <title>Jogos - Projeto Martopia</title>
</head>

<body>
    <div class="container-fluid">

        <header class="header">

            <input type="checkbox" id="check">
            <label for="check" class="icone">
                <i class="bi bi-list" id="menu-icone"></i>
                <i class="bi bi-x" id="sair-icone"></i>
            </label>

            <div class="logo-marca" style="margin-left: -3rem;">
                <a href="./homeAdm.php" class="logo"><img src="../../../../../frontend/public/img/Logo.png" alt="Logo-Projeto Martopia"></a>
                <p style="margin-left: -3rem;">Projeto <br> Martopia</p>
            </div>

            <nav>
                <a href="../../../../trocar/trocarperfil.php"><img src="../../../../../<?php echo htmlspecialchars($foto); ?>" alt="Foto de Perfil" class="perfil" style="--i:4;"></a>
            </nav>
        </header>

        <style>
            .person {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 10rem;
            }

            body {
                background: #045A94;
                background: radial-gradient(circle, rgba(4, 90, 148, 1) 0%, rgba(129, 192, 233, 1) 50%, #9fcaec);
            }

            footer {
                background: #045A94;
            }

            .tabela tbody tr:hover {
                background-color: #dcf1fa;
            }

            header {
                box-shadow: 0 40px 60px rgba(0, 0, 0, 0.2);
            }

            /* ========== MODAL VER ========== */
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1000;
            }

            .modal .modal-content {
                color: #000;
                background: #fff;
                margin: 10% auto;
                padding: 20px;
                width: 500px;
                border-radius: 10px;
            }

            .close-btn {
                /* top: 20px;  */
                background: #ff4757;
                color: white;
                border: none;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                cursor: pointer;
                font-size: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .close-btn:hover {
                transform: rotate(90deg);
                background: #ff6b81;
            }

            /* .modal .close {
                float: right;
                font-size: 22px;
                cursor: pointer;
            } */

            /* ========== MODAL EDITAR ========== */
            .modal_fade {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1000;
                overflow-y: auto;
                padding: 40px 0;
            }

            .modal_fade.active {
                display: block;
            }

            /* conteúdo do modal continua centralizado */
            .modal_fade .modal-content {
                background: #fff;
                border-radius: 20px;
                padding: 40px;
                width: 90%;
                height: 900px;
                max-width: 600px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                margin: 40px auto;
                position: relative;
                animation: modalSlideIn 0.3s ease;
            }


            @keyframes modalSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(-50px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Close Button - Editar */
            /* .modal_fade .close {
                position: absolute;
                top: 20px;
                right: 25px;
                font-size: 28px;
                font-weight: bold;
                color: #666;
                cursor: pointer;
                transition: color 0.3s;
                background: none;
                border: none;
                float: none;
            }

            .modal_fade .close:hover {
                color: #333;
            } */

            /* Modal Title - Editar */
            .modal_fade h2 {
                color: #045A94;
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 30px;
                text-align: center;
            }

            /* Form Styling - Editar */
            .modal_fade form {
                margin-top: 20px;
            }

            .modal_fade label {
                display: block;
                color: #045A94;
                font-size: 15px;
                font-weight: 600;
                margin-bottom: 8px;
            }

            .modal_fade input[type="text"],
            .modal_fade textarea,
            .modal_fade select {
                width: 100%;
                padding: 12px 15px;
                border: 2px solid #E0E0E0;
                border-radius: 10px;
                font-size: 14px;
                color: #333;
                background: #F8F9FA;
                transition: all 0.3s;
                box-sizing: border-box;
            }

            .modal_fade input[type="text"]:focus,
            .modal_fade textarea:focus,
            .modal_fade select:focus {
                outline: none;
                border-color: #045A94;
                background: #fff;
                box-shadow: 0 0 0 3px rgba(4, 90, 148, 0.1);
            }

            .modal_fade textarea {
                resize: vertical;
                min-height: 80px;
                font-family: inherit;
            }

            /* Form Groups - Editar */
            .modal_fade form>div {
                margin-bottom: 20px;
            }

            /* Two Column Layout - Editar */
            .modal_fade .row-flex {
                display: flex;
                justify-content: space-between;
                gap: 15px;
                margin-bottom: 20px;
            }

            .modal_fade .row-flex>div {
                flex: 1;
                margin-bottom: 0;
            }

            /* Button Submit - Editar */
            .modal_fade button[type="submit"] {
                width: 100%;
                padding: 14px;
                margin-top: 25px;
                background: linear-gradient(135deg, #045A94 0%, #0673B8 100%);
                color: white;
                border: none;
                border-radius: 10px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
                box-shadow: 0 4px 15px rgba(4, 90, 148, 0.3);
            }

            .modal_fade button[type="submit"]:hover {
                background: linear-gradient(135deg, #034a7d 0%, #0562a0 100%);
                box-shadow: 0 6px 20px rgba(4, 90, 148, 0.4);
                transform: translateY(-2px);
            }

            .modal_fade button[type="submit"]:active {
                transform: translateY(0);
            }

            /* Cancel Button - Editar */
            .modal_fade .btn-cancel {
                width: 100%;
                padding: 14px;
                margin-top: 10px;
                background: #f0f0f0;
                color: #666;
                border: none;
                border-radius: 10px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
            }

            .modal_fade .btn-cancel:hover {
                background: #e0e0e0;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .modal_fade .modal-content {
                    padding: 30px 20px;
                    width: 95%;
                    max-width: none;
                }

                .modal_fade h2 {
                    font-size: 24px;
                }

                .modal_fade .row-flex {
                    flex-direction: column;
                    gap: 0;
                }

                .modal_fade .row-flex>div {
                    margin-bottom: 20px;
                }
            }

            .perfil {
                width: 80px;
                height: 80px;
                margin-left: -3rem;
                border: 1.5px solid #e18451;
                /* color: #81c0e9; */
            }

            .iconeCentral {
                display: flex;
                align-items: center;
                /* centraliza verticalmente o ícone e o texto */
                justify-content: center;
                /* centraliza horizontalmente na tela */
                background: transparent;
                border-radius: 20px;
                width: 100%;
                max-width: 1000px;
                font-weight: bold;
                filter: blur(.2px);
                box-shadow: 0 0 15px 3px #81c0e9;
                height: auto;
                padding: 2rem;
                margin: 8rem auto;
                text-align: center;
                font-family: 'Texto';
                gap: 3rem;
                margin-top: 10rem;
            }

            .centraliza {
                display: flex;
                flex-direction: column;
                /* h2 e botão ficam um embaixo do outro */
                align-items: center;
                text-align: center;
            }

            .btn-voltar {
                transition: 0.3s;
                padding: 0.8rem 1.4rem;
                background: linear-gradient(135deg, #c6e1f6, #9fcaec);
                color: #045a94;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 1.1rem;
                font-family: 'Texto', serif;
                border-radius: 25px;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            }

            .btn-voltar:hover {
                background: linear-gradient(135deg, #81C0E9, #38a0dd);
                transform: translateY(-2px);
                box-shadow: 0 6px 14px rgba(0, 0, 0, 0.35);
            }
        </style>



        <div class="iconeCentral">

            <div> <img src="../../img/pergunta.png" alt="adminIcon" style="color: #000; "></i> </div>

            <div class="centraliza">

                <h2 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, .3); color: #fff;">Minhas Perguntas - Quiz</h2>

                <br><br>

                <div>
                    <button onclick="history.back()" class="btn-voltar"> Voltar </button>
                </div>

            </div>

        </div>

        <main>



            <div class="usuarios">

                <div class="col">

                    <table class="tabela" style="font-family: 'Texto';">

                        <thead class="nametable" style="width: 100%;">
                            <tr>
                                <th scope="col" style="padding-left: 14rem; font-size: 1.3rem;">Título - Pergunta</th>
                                <th scope="col" style="padding-left: 3rem; font-size: 1.3rem;">Nível - Pergunta</th>
                                <th scope="col" style="font-size: 1.3rem;">Opção - Resposta</th>
                                <th scope="col" style="font-size: 1.3rem;">Ver</th>
                                <th scope="col" style="font-size: 1.3rem;">Editar</th>
                                <th scope="col" style="font-size: 1.3rem;">Excluir</th>
                            </tr>
                        </thead>

                        <tbody style="font-size: 1.2rem;">

                            <?php
                            if (!empty($listar)) {


                                foreach ($listar as $c) {
                                    $nivel = $c['dificuldade']; // valor vindo do banco (1, 2, 3...)

                                    switch ($nivel) {
                                        case 1:
                                            $nivelTexto = "Fácil";
                                            break;
                                        case 2:
                                            $nivelTexto = "Médio";
                                            break;
                                        case 3:
                                            $nivelTexto = "Difícil";
                                            break;
                                        default:
                                            $nivelTexto = "Enem";
                                    }
                            ?>
                                    <tr style="font-size: 1.2rem;">
                                        <th class="info" scope="row" data-label="nome-conteudo" style="font-size: 1.2rem;"><?php echo $c['pergunta']; ?></th>
                                        <td class="info" style="text-align:center; font-size: 1.2rem;"><?php echo $nivelTexto; ?></td>
                                        <td class="info" data-label="titulo-conteudo" style="font-size: 1.2rem;"><?php echo $c['resposta']; ?></td>
                                        <td>
                                            <a class="btn-ver info" style="font-size: 1.2rem;"
                                                data-id="<?php echo $c['id']; ?>"
                                                data-pergunta="<?php echo htmlspecialchars($c['pergunta']); ?>"
                                                data-a="<?php echo htmlspecialchars($c['opcao_a']); ?>"
                                                data-b="<?php echo htmlspecialchars($c['opcao_b']); ?>"
                                                data-c="<?php echo htmlspecialchars($c['opcao_c']); ?>"
                                                data-d="<?php echo htmlspecialchars($c['opcao_d']); ?>"
                                                data-resposta="<?php echo htmlspecialchars($c['resposta']); ?>"
                                                data-dificuldade="<?php echo $c['dificuldade']; ?>">
                                                Ver
                                            </a>
                                        </td>

                                        <td style="font-size: 1.2rem;">
                                            <a class="btn btn-ver1 btn-sm"
                                                onclick="editarPergunta(this)"
                                                data-id="<?= $c['id']; ?>"
                                                data-pergunta="<?= htmlspecialchars($c['pergunta']); ?>"
                                                data-opcao-a="<?= htmlspecialchars($c['opcao_a']); ?>"
                                                data-opcao-b="<?= htmlspecialchars($c['opcao_b']); ?>"
                                                data-opcao-c="<?= htmlspecialchars($c['opcao_c']); ?>"
                                                data-opcao-d="<?= htmlspecialchars($c['opcao_d']); ?>"
                                                data-resposta="<?= htmlspecialchars($c['resposta']); ?>"
                                                data-dificuldade="<?= $c['dificuldade']; ?>"
                                                data-id-biologo="<?= $c['id_biologo']; ?>">
                                                ✏️
                                            </a>
                                        </td>
                                        <td>
                                            <a href=" excluir.php?id=<?php echo $c['id']; ?>" style="color:red; font-size: 1.2rem;" onclick="return confirm('Tem certeza que deseja deletar está pergunta?')">✖</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' style='text-align:center;'>Nenhum conteúdo encontrado</td></tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>


        </main>


        <div id="modalVer" class="modal ">

            <div class="modal-content ">

                <span class="close close-btn" style="font-size: 2rem; margin-left: 410px;">&times;</span>

                <h2 style="font-size: 2rem; font-family: 'Titulo'; text-align:center; color:#045A94; padding: 16px 12px;">Detalhes da Questão</h2> <br>

                <p style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94">Pergunta:</strong> <span id="m-pergunta"></span></p> <br>

                <p style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94">A)</strong> <span id="m-a"></span></p> <br>

                <p style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94">B)</strong> <span id="m-b"></span></p> <br>

                <p style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94">C)</strong> <span id="m-c"></span></p> <br>

                <p style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94">D)</strong> <span id="m-d"></span></p> <br>

                <p style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94">Resposta:</strong> <span id="m-resposta"></span></p> <br>

                <p style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94">Nível:</strong> <span id="m-nivel"></span></p>

            </div>

        </div>


        <!-- MODAL EDITAR -->
        <div id="modalEditarPergunta" class="modal_fade">

            <div class="modal-content" style="width: 600px;">

                <span class="close close-btn" id="closeEditModal" style="font-size: 2rem; margin-left: 480px;">&times;</span>

                <h2 style="font-size: 2rem; font-family: 'Titulo'; text-align:center; color:#045A94; padding: 16px 12px;"> Editar Questão</h2>

                <form id="formEditarPergunta" action="editarPergunta.php" method="POST" style="margin-top: 20px; ">

                    <input type="hidden" id="edit-id" name="id">

                    <div style="margin-bottom: 15px;">
                        <label for="edit-pergunta" style="font-size: 1.2rem; color:#045a94; font-family: 'Texto';"><strong style="color:#045A94;">Pergunta:</strong></label>
                        <textarea id="edit-pergunta" name="pergunta" rows="3" required style="width: 100%; padding: 8px; font-size: 1.2rem; color:#000; font-family: 'Texto';"></textarea>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label for="edit-opcao-a" style="font-size: 1.2rem; color:#045A94; font-family: 'Texto';"><strong style="color:#045A94;">Opção A:</strong></label>
                        <input type="text" id="edit-opcao-a" name="opcao_a" required style="width: 100%; padding: 8px; font-size: 1.2rem; color:#000; font-family: 'Texto';">
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label for="edit-opcao-b" style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94;">Opção B:</strong></label>
                        <input type="text" id="edit-opcao-b" name="opcao_b" required style="width: 100%; padding: 8px; font-size: 1.2rem; color:#000; font-family: 'Texto';">
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label for="edit-opcao-c" style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94;">Opção C:</strong></label>
                        <input type="text" id="edit-opcao-c" name="opcao_c" required style="width: 100%; padding: 8px; font-size: 1.2rem; color:#000; font-family: 'Texto';">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="edit-opcao-d" style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94;">Opção D:</strong></label>
                        <input type="text" id="edit-opcao-d" name="opcao_d" required style="width: 100%; padding: 8px; font-size: 1.2rem; color:#000; font-family: 'Texto';">
                    </div>

                    <div style="display: flex; justify-content: space-between;">
                        <div style="width: 48%;">
                            <label for="edit-resposta" style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94;">Resposta Correta:</strong></label>
                            <select id="edit-resposta" name="resposta" required style="width: 100%; padding: 8px; font-size: 1.2rem; color:#000; font-family: 'Texto'">
                                <option value="A" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">A</option>
                                <option value="B" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">B</option>
                                <option value="C" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">C</option>
                                <option value="D" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">D</option>
                            </select>
                        </div>
                        <div style="width: 48%;">
                            <label for="edit-dificuldade" style="font-size: 1.2rem; color:#000; font-family: 'Texto';"><strong style="color:#045A94;">Dificuldade:</strong></label>
                            <select id="edit-dificuldade" name="dificuldade" required style="width: 100%; padding: 8px; font-size: 1.2rem; color:#000; font-family: 'Texto'">
                                <option value="1" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">Fácil</option>
                                <option value="2" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">Médio</option>
                                <option value="3" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">Difícil</option>
                                <option value="4" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">Enem</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" id="edit-id-biologo" name="id_biologo" style="font-size: 1.2rem; color:#000; font-family: 'Texto';">


                    <button type="submit" style="width: 100%; padding: 12px; margin-top: 20px; background-color: #045A94; color: white; border: none; border-radius: 20px; cursor: pointer; font-size: 1.2rem; color:#fff; font-family: 'Texto';">
                        Salvar Alterações
                    </button>
                </form>
            </div>
        </div>

        <footer>
            <div class="contatos">
                <h3>Contatos</h3>
                <p>Email: contato@martopia.com.br</p>
                <p>Telefone: +55 11 99999-9999</p>
                <p>Endereço: Rua do Oceano, 123, São Paulo, SP</p>
            </div>

            <div class="redes">
                <h3>Redes Sociais</h3>
                <div>
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="mapa">
                <h3>Localização</h3>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.2168153595317!2d-46.766872!3d-23.235196!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cede166027baab%3A0x566fc4df5821546c!2sEscola%20T%C3%A9cnica%20Estadual%20de%20Campo%20Limpo%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1756695006929!5m2!1spt-BR!2sbr                    allowfullscreen="" loading=" lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Mapa do local">
                </iframe>
            </div>

            <div class="copyright">
                &copy; 2025 Projeto Martopia. Todos os direitos reservados.
            </div>
        </footer>
    </div>


    <script src="modal.js">

    </script>
</body>

</html>