<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMar - Gerenciamento de Denúncias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #0d6efd;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #bb2d3b;
        }
        .badge {
            font-size: 0.85em;
        }
        .denuncia-item {
            transition: all 0.3s ease;
            border-left: 4px solid #0d6efd;
        }
        .denuncia-item:hover {
            background-color: #f1f8ff;
            transform: translateY(-2px);
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .page-title {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-fish me-2"></i>InstaMar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-flag me-1"></i> Denúncias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-image me-1"></i> Postagens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-users me-1"></i> Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-sign-out-alt me-1"></i> Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="page-title"><i class="fas fa-flag me-2"></i>Gerenciamento de Denúncias</h1>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
            </div>
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-4">
                        <label for="postFilter" class="form-label">ID da Postagem</label>
                        <input type="number" class="form-control" id="postFilter" placeholder="Digite o ID da postagem">
                    </div>
                    <div class="col-md-4">
                        <label for="userFilter" class="form-label">ID do Usuário</label>
                        <input type="number" class="form-control" id="userFilter" placeholder="Digite o ID do usuário">
                    </div>
                    <div class="col-md-4">
                        <label for="dateFilter" class="form-label">Data</label>
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Aplicar Filtros</button>
                        <button type="reset" class="btn btn-outline-secondary"><i class="fas fa-undo me-1"></i> Limpar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h5><i class="fas fa-flag"></i> Total de Denúncias</h5>
                        <h3>42</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h5><i class="fas fa-check-circle"></i> Resolvidas</h5>
                        <h3>28</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white text-center">
                    <div class="card-body">
                        <h5><i class="fas fa-clock"></i> Pendentes</h5>
                        <h3>14</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Denúncias -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Denúncias Registradas</h5>
                <span class="badge bg-primary">Últimas 50</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Usuário</th>
                                <th scope="col">Postagem</th>
                                <th scope="col">Data</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Incluindo o repositório e listando as denúncias
                            include_once '../classes/class_Conexao.php';
                            include_once '../classes/class_InstaMar.php';
                            include_once '../classes/class_IRepositorioInstamar.php';
                            
                            $result = $respositorioInstamar->listarDenuncias();
                            
                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr class='denuncia-item'>";
                                    echo "<th scope='row'>" . $row['id'] . "</th>";
                                    echo "<td>" . $row['usuario'] . " <br><small class='text-muted'>ID: " . $row['id_usuario'] . "</small></td>";
                                    echo "<td>" . (strlen($row['post']) > 50 ? substr($row['post'], 0, 50) . "..." : $row['post']) . " <br><small class='text-muted'>ID: " . $row['id_post'] . "</small></td>";
                                    echo "<td>" . date('d/m/Y H:i', strtotime($row['data_denuncia'])) . "</td>";
                                    echo "<td>
                                            <button class='btn btn-sm btn-info me-1' data-bs-toggle='tooltip' title='Ver detalhes'>
                                                <i class='fas fa-eye'></i>
                                            </button>
                                            <button class='btn btn-sm btn-success me-1' data-bs-toggle='tooltip' title='Marcar como resolvida'>
                                                <i class='fas fa-check'></i>
                                            </button>
                                            <button class='btn btn-sm btn-danger' data-bs-toggle='tooltip' title='Excluir denúncia'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-4'>Nenhuma denúncia encontrada.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <nav aria-label="Navegação de página">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Próxima</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal para detalhes da denúncia -->
    <div class="modal fade" id="detalhesDenunciaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes da Denúncia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Informações do Usuário</h6>
                            <p><strong>ID:</strong> 123</p>
                            <p><strong>Nome:</strong> João Silva</p>
                            <p><strong>Email:</strong> joao@exemplo.com</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Informações da Postagem</h6>
                            <p><strong>ID:</strong> 456</p>
                            <p><strong>Legenda:</strong> Esta é uma postagem com conteúdo inadequado...</p>
                            <p><strong>Data da Postagem:</strong> 15/08/2023 14:30</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6>Conteúdo da Postagem</h6>
                        <div class="border p-3 rounded bg-light">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eu ultricies lacinia, nunc nisl aliquam nisl, eu aliquam nisl nunc eu nisl.</p>
                            <img src="https://via.placeholder.com/300" class="img-fluid rounded" alt="Postagem">
                        </div>
                    </div>
                    <div>
                        <h6>Histórico de Ações</h6>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>Denúncia registrada</span>
                                    <small class="text-muted">10/09/2023 09:15</small>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>Visualizada por moderador</span>
                                    <small class="text-muted">10/09/2023 10:22</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">InstaMar &copy; 2023 - Sistema de Gerenciamento de Denúncias</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Exemplo de funcionalidade de filtro com JavaScript
        document.getElementById('postFilter').addEventListener('input', function() {
            // Aqui você implementaria a filtragem real
            console.log('Filtrando por postagem:', this.value);
        });
    </script>
</body>
</html>