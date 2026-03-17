// public/js/gerenciamentoScript.js
console.log(" gerenciamentoScript.js carregado");

class GerenciamentoColaboradores {
    constructor() {
        this.BACKEND_URL = "http://localhost:3001/api";
        this.BACKEND_BASE = "http://localhost:3001";
        this.token = localStorage.getItem("token") || sessionStorage.getItem("token");
        
        this.usuarioAtual = null;
        this.colaboradores = [];
        this.colaboradorSelecionado = null;
        this.modoExibicao = "cards";
        this.cargosDisponiveis = [];
        
        this.init();
    }
 getFotoUrl(foto) {
        if (!foto) return "/img/fundofoda.png";
        
        // Se já tem protocolo (http/https), retorna direto
        if (foto.startsWith('http')) return foto;
        
        // Se começa com 'uploads/', remove o prefixo
        if (foto.startsWith('uploads/')) {
            foto = foto.replace('uploads/', '');
        }
        
        // Se começa com 'doc-' ou similar, é um upload real
        if (foto.includes('doc-') || foto.match(/\.(jpg|jpeg|png|gif)$/i)) {
            return `${this.BACKEND_BASE}/uploads/${foto}`;
        }
        
        // Fallback para imagens padrão em /img
        return `/img/${foto}`;
    }

    // ===== INICIALIZAÇÃO PRINCIPAL =====
    async init() {
        try {
            console.log("🔧 Inicializando gerenciamento de colaboradores...");
            
            if (!this.validarToken()) return;
            
            await this.inicializarElementos();
            await this.carregarUsuario();
            await this.carregarColaboradores();
            this.configurarEventListeners();
            
            console.log(" Sistema inicializado com sucesso");
        } catch (error) {
            console.error(" Erro na inicialização:", error);
            this.mostrarMensagem("Erro ao inicializar sistema", "danger");
        }
    }

    // ===== INICIALIZAÇÃO DE ELEMENTOS DOM =====
    async inicializarElementos() {
         console.log("Inicializando elementos DOM...");
    
    // Elementos principais
    this.formColaborador = document.getElementById("formColaborador");
    this.btnRegistrar = document.getElementById("btnRegistrarColaborador");
    this.modalEl = document.getElementById("modalColaborador");
    this.modalColab = this.modalEl ? new bootstrap.Modal(this.modalEl) : null;
    
    // Foto e preview
    this.fotoInput = document.getElementById("foto");
    this.previewImage = document.getElementById("previewFoto");
    
    // Filtros e busca
    this.setorSelect = document.getElementById("filtroSetor");
    this.cardRow = document.getElementById("listaColaboradores");
    this.buscaInput = document.getElementById("buscaColaborador");
    this.btnTrocarExibicao = document.getElementById("btnTrocarExibicao");
    
    // Overlay de detalhes
    this.overlay = document.getElementById("overlayColaborador");
    this.colabSelecionadoFoto = document.getElementById("colabSelecionadoFoto");
    this.colabSelecionadoNome = document.getElementById("colabSelecionadoNome");
    this.colabSelecionadoCPF = document.getElementById("colabSelecionadoCPF");
    this.colabSelecionadoMatricula = document.getElementById("colabSelecionadoMatricula");
    this.colabSelecionadoCargo = document.getElementById("colabSelecionadoCargo");
    this.colabSelecionadoSetor = document.getElementById("colabSelecionadoSetor");
    this.colabSelecionadoJornada = document.getElementById("colabSelecionadoJornada");
    this.colabSelecionadoHoras = document.getElementById("colabSelecionadoHoras");
    this.colabSelecionadoSalario = document.getElementById("colabSelecionadoSalario");
    this.colabSelecionadoBeneficios = document.getElementById("colabSelecionadoBeneficios");
    
    // Botões do overlay
    this.btnExcluirColab = document.getElementById("btnExcluirColab");
    this.btnEditarColab = document.getElementById("btnEditarColab");
    this.btnFecharColab = document.getElementById("btnFecharColab");

    // Elementos do MODAL - CRÍTICO: garantir que sejam encontrados
    this.selectCargo = document.getElementById("cargo");
    this.containerBeneficios = document.getElementById("containerBeneficios");
    this.inputNumeroRegistro = document.getElementById("numero_registro");
    this.inputNome = document.getElementById("nome");
    this.inputCPF = document.getElementById("cpf");
    this.inputSetor = document.getElementById("setor");
    this.inputTipoJornada = document.getElementById("tipo_jornada");
    this.inputHorasDiarias = document.getElementById("horas_diarias");
    this.inputSalario = document.getElementById("salario");
    this.tituloModal = document.getElementById("tituloModalColaborador");
    this.inputSenha = document.getElementById("senha");
    // Debug detalhado
    console.log(" Elementos críticos inicializados:", {
        selectCargo: !!this.selectCargo,
        containerBeneficios: !!this.containerBeneficios,
        formColaborador: !!this.formColaborador,
        modalEl: !!this.modalEl,
        overlay: !!this.overlay
    });

    // Se elementos críticos não foram encontrados, tentar novamente
    if (!this.selectCargo || !this.containerBeneficios) {
        console.warn("⚠️ Elementos do modal não encontrados. Tentando novamente...");
        setTimeout(() => this.inicializarElementos(), 500);
        return;
    }

    // Configurar preview de foto
    if (this.previewImage) {
        this.previewImage.src = "/img/fundofoda.png";
    }
    
    if (this.fotoInput && this.previewImage) {
        this.fotoInput.addEventListener("change", () => {
            const file = this.fotoInput.files[0];
            this.previewImage.src = file ? URL.createObjectURL(file) : "/img/fundofoda.png";
        });
    }

    console.log(" Elementos DOM inicializados com sucesso");

    }

    // ===== VALIDAÇÃO E SEGURANÇA =====
    validarToken() {
        if (!this.token) {
            this.redirecionarLogin("Token não encontrado");
            return false;
        }
        
        try {
            const payload = JSON.parse(atob(this.token.split(".")[1]));
            const tokenValido = payload.exp * 1000 > Date.now();
            
            if (!tokenValido) {
                this.redirecionarLogin("Sessão expirada");
                return false;
            }
            
            return true;
        } catch {
            this.redirecionarLogin("Token inválido");
            return false;
        }
    }

    redirecionarLogin(mensagem) {
        alert(`${mensagem}. Faça login novamente.`);
        localStorage.removeItem("token");
        sessionStorage.removeItem("token");
        window.location.href = "/login";
    }

    // ===== UTILITÁRIOS =====
    mostrarMensagem(mensagem, tipo = "info") {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 5000);
    }

    async fetchJSON(url, options = {}) {
        try {
            options.headers = { 
                ...options.headers, 
                Authorization: `Bearer ${this.token}` 
            };
            options.credentials = "include";
            
            const response = await fetch(url, options);
            
            if (response.status === 401) {
                this.redirecionarLogin("Sessão expirada");
                return { success: false, data: null };
            }
            
            const data = await response.json().catch(() => null);
            
            return {
                success: response.ok,
                status: response.status,
                data
            };
        } catch (error) {
            console.error("Erro na requisição:", error);
            return { 
                success: false, 
                data: null, 
                error: error.message 
            };
        }
    }

    // ===== GERENCIAMENTO DE USUÁRIO =====
    async carregarUsuario() {
        if (this.usuarioAtual) return this.usuarioAtual;
        
        console.log(" Carregando dados do usuário...");
        const { success, data } = await this.fetchJSON(`${this.BACKEND_URL}/gestor/me`);
        
        if (success && data) {
            this.usuarioAtual = data.usuario || data.data || data;
            
            if (this.usuarioAtual?.id) {
                console.log(" Usuário carregado:", {
                    id: this.usuarioAtual.id,
                    nome: this.usuarioAtual.nome,
                    empresa_id: this.usuarioAtual.empresa_id
                });
                return this.usuarioAtual;
            }
        }
        
        console.error(" Falha ao carregar usuário");
        this.mostrarMensagem("Erro ao carregar dados do usuário", "danger");
        return null;
    }

    // ===== GERENCIAMENTO DE COLABORADORES =====
    async carregarColaboradores(setorFiltro = "") {
        if (!this.usuarioAtual) {
            await this.carregarUsuario();
        }
        
        if (!this.usuarioAtual) {
            console.error(" Usuário não identificado");
            this.renderizarColaboradores([]);
            return;
        }

        console.log(" Carregando colaboradores...");
        
        let url = `${this.BACKEND_URL}/colaborador/listar?empresa_id=${this.usuarioAtual.empresa_id}`;
        if (setorFiltro) {
            url += `&setor=${encodeURIComponent(setorFiltro)}`;
        }
        
        const { success, data } = await this.fetchJSON(url);
        
        if (!success) {
            console.error(" Erro ao carregar colaboradores:", data);
            this.mostrarMensagem(data?.message || "Erro ao carregar colaboradores", "danger");
            this.renderizarColaboradores([]);
            return;
        }

        // Processar dados dos colaboradores
        this.colaboradores = Array.isArray(data) ? data : (data?.data || []);
        
        this.colaboradores = this.colaboradores.map(colab => ({
            ...colab,
            salario: this.parseSalario(colab.salario),
            beneficios: Array.isArray(colab.beneficios) ? colab.beneficios : []
        }));

        console.log(` ${this.colaboradores.length} colaboradores carregados`);
        this.preencherFiltroSetores();
        this.renderizarColaboradores();
    }

    parseSalario(salario) {
        if (typeof salario === "string") {
            const parsed = parseFloat(salario);
            return isNaN(parsed) ? 0 : parsed;
        }
        return salario || 0;
    }

    preencherFiltroSetores() {
        if (!this.setorSelect) return;
        
        const setores = [...new Set(
            this.colaboradores
                .map(c => (c.setor || "").toString().trim())
                .filter(Boolean)
        )];
        
        this.setorSelect.innerHTML = '<option value="">Todos os setores</option>';
        
        setores.forEach(setor => {
            const option = document.createElement("option");
            option.value = setor;
            option.textContent = setor;
            this.setorSelect.appendChild(option);
        });
    }

    renderizarColaboradores(lista = this.colaboradores) {
        if (!this.cardRow) return;
        
        if (!lista?.length) {
            this.cardRow.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Nenhum colaborador encontrado</p>
                </div>
            `;
            return;
        }

        // Agrupar por setor
        const setoresMap = {};
        lista.forEach(colab => {
            const setor = colab.setor || "Sem setor";
            if (!setoresMap[setor]) setoresMap[setor] = [];
            setoresMap[setor].push(colab);
        });

        this.cardRow.innerHTML = "";
        
        Object.entries(setoresMap).forEach(([setor, colaboradoresSetor]) => {
            const divSetor = this.criarContainerSetor(setor);
            const containerColaboradores = this.criarContainerColaboradores(colaboradoresSetor);
            
            divSetor.appendChild(containerColaboradores);
            this.cardRow.appendChild(divSetor);
        });
    }

    criarContainerSetor(nomeSetor) {
        const div = document.createElement("div");
        div.className = "setor-container mb-4";
        
        const titulo = document.createElement("h5");
        titulo.className = "setor-titulo mb-3";
        titulo.textContent = nomeSetor;
        
        div.appendChild(titulo);
        return div;
    }

    criarContainerColaboradores(colaboradores) {
        const container = document.createElement("div");
        
        if (this.modoExibicao === "lista") {
            container.className = "lista-colaboradores-wrapper";
            container.style.cssText = `
                display: flex;
                flex-direction: column;
                gap: 15px;
                width: 100%;
            `;
            
            colaboradores.forEach(colab => {
                container.appendChild(this.criarLinhaColaborador(colab));
            });
        } else {
            container.className = "cards-row";
            container.style.cssText = `
                display: flex;
                flex-direction: column;
                gap: 12px;
                width: 100%;
            `;
            
            // Agrupar em pares
            for (let i = 0; i < colaboradores.length; i += 2) {
                const parDiv = this.criarParColaboradores(colaboradores.slice(i, i + 2));
                container.appendChild(parDiv);
            }
        }
        
        return container;
    }

    criarLinhaColaborador(colab) {
        const linha = document.createElement("div");
        linha.className = "item-colaborador linha-card";
        linha.style.cssText = `
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            cursor: pointer;
            padding: 12px 16px;
            box-sizing: border-box;
        `;

       const fotoSrc = this.getFotoUrl(colab.foto);
        const salarioTexto = colab.salario > 0 ? 
            Number(colab.salario).toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : 
            "R$ 0,00";

        linha.innerHTML = `
            <div class="coluna-foto" style="flex: 0 0 auto;">
                <img src="${fotoSrc}" alt="Foto de ${colab.nome}" 
                     class="foto-card" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.18);">
            </div>
            <div style="flex: 1 1 auto; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; min-width: 0;">
                <div class="coluna-nome" style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${colab.nome}</div>
                <div class="coluna-cargo" style="font-size: 0.88rem; color: rgba(255,255,255,0.85);">${colab.cargo || "-"}</div>
            </div>
            <div class="coluna-salario" style="flex: 0 0 140px; text-align: right; font-weight: 700; white-space: nowrap;">${salarioTexto}</div>
        `;

        linha.addEventListener("click", () => this.selecionarColaborador(colab));
        return linha;
    }

    criarParColaboradores(colaboradores) {
        const parDiv = document.createElement("div");
        parDiv.className = "cards-pair-row";
        parDiv.style.cssText = `
            display: flex;
            flex-direction: row;
            gap: 12px;
            justify-content: flex-start;
            flex-wrap: nowrap;
            width: 100%;
        `;

        colaboradores.forEach(colab => {
            const col = document.createElement("div");
            col.className = "card-col";
            col.style.cssText = `
                flex: 1 1 0;
                display: flex;
                justify-content: center;
                box-sizing: border-box;
            `;

            const card = this.criarCardColaborador(colab);
            col.appendChild(card);
            parDiv.appendChild(col);
        });

        // Centralizar se tiver apenas um card
        if (parDiv.children.length === 1) {
            parDiv.style.justifyContent = "center";
        }

        return parDiv;
    }

    criarCardColaborador(colab) {
        const card = document.createElement("div");
        card.className = "item-colaborador glass-card p-3 text-center h-100";
        card.style.cssText = `
            cursor: pointer;
            width: 100%;
            max-width: 260px;
        `;

         const fotoSrc = this.getFotoUrl(colab.foto);
        const salarioTexto = colab.salario > 0 ? 
            Number(colab.salario).toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : 
            "R$ 0,00";

        card.innerHTML = `
            <img src="${fotoSrc}" alt="Foto de ${colab.nome}" 
                 class="foto-card rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
            <h6 class="nome mb-1">${colab.nome}</h6>
            <p class="cargo text-muted mb-1">${colab.cargo || "-"}</p>
            <p class="salario small text-success mb-0"><strong>${salarioTexto}</strong></p>
        `;

        card.addEventListener("click", () => this.selecionarColaborador(colab));
        return card;
    }

    // ===== OVERLAY DE DETALHES =====
    selecionarColaborador(colab) {
        if (!this.overlay) {
            console.error(" Overlay não disponível");
            return;
        }

        this.colaboradorSelecionado = colab;

        // Atualizar dados básicos
       this.atualizarElemento(this.colabSelecionadoFoto, () => {
        const fotoUrl = this.getFotoUrl(colab.foto);
        this.colabSelecionadoFoto.src = fotoUrl;
        this.colabSelecionadoFoto.onerror = () => {
            this.colabSelecionadoFoto.src = "/img/fundofoda.png";
        };
    });

        this.atualizarElemento(this.colabSelecionadoNome, () => {
            this.colabSelecionadoNome.textContent = colab.nome || "Nome não informado";
        });

        this.atualizarElemento(this.colabSelecionadoCPF, () => {
            this.colabSelecionadoCPF.textContent = `CPF: ${colab.cpf || "-"}`;
        });

        this.atualizarElemento(this.colabSelecionadoMatricula, () => {
            this.colabSelecionadoMatricula.textContent = `Matrícula: ${colab.numero_registro || "-"}`;
        });

        this.atualizarElemento(this.colabSelecionadoCargo, () => {
            this.colabSelecionadoCargo.textContent = `Cargo: ${colab.cargo || "-"}`;
        });

        this.atualizarElemento(this.colabSelecionadoSetor, () => {
            this.colabSelecionadoSetor.textContent = `Setor: ${colab.setor || "-"}`;
        });

        this.atualizarElemento(this.colabSelecionadoJornada, () => {
            this.colabSelecionadoJornada.textContent = `Jornada: ${colab.tipo_jornada || "-"}`;
        });

        this.atualizarElemento(this.colabSelecionadoHoras, () => {
            this.colabSelecionadoHoras.textContent = `Horas diárias: ${colab.horas_diarias || "-"}`;
        });

        this.atualizarElemento(this.colabSelecionadoSalario, () => {
            this.colabSelecionadoSalario.textContent = colab.salario > 0 ? 
                `Salário: ${Number(colab.salario).toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}` :
                "Salário: Não definido";
        });

        // Atualizar benefícios
        this.atualizarElemento(this.colabSelecionadoBeneficios, () => {
            this.renderizarBeneficiosOverlay(colab.beneficios);
        });

        this.overlay.style.display = "flex";
    }

    atualizarElemento(elemento, callback) {
        if (elemento) {
            callback();
        }
    }

    renderizarBeneficiosOverlay(beneficios) {
        if (!this.colabSelecionadoBeneficios) return;

        this.colabSelecionadoBeneficios.innerHTML = "";

        if (!Array.isArray(beneficios) || beneficios.length === 0) {
            const item = document.createElement("li");
            item.className = "text-muted";
            item.textContent = "Nenhum benefício cadastrado";
            this.colabSelecionadoBeneficios.appendChild(item);
            return;
        }

        beneficios.forEach(beneficio => {
            const item = document.createElement("li");
            item.className = "beneficio-overlay-item mb-2";
            
            const valor = Number(beneficio.valor_personalizado || beneficio.valor_aplicado || 0)
                .toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
            
            item.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-start">${beneficio.nome_do_beneficio || beneficio.nome || "Benefício"}</span>
                    <span class="text-success ms-2">${valor}</span>
                </div>
            `;
            
            this.colabSelecionadoBeneficios.appendChild(item);
        });
    }

    // ===== GERENCIAMENTO DE CARGOS E BENEFÍCIOS =====
//  CORRETO - URLs sem duplicação
async carregarCargosParaSelect() {
    if (!this.selectCargo) {
        console.error(" Select de cargo não inicializado");
        return false;
    }

    try {
        console.log(" Carregando cargos para select...");
        const { success, data } = await this.fetchJSON(`${this.BACKEND_URL}/cargos/listar`);
        
        console.log(" Resposta completa da API de cargos:", { success, data });

        if (success && data) {
            let cargosArray = [];
            
            if (Array.isArray(data)) {
                cargosArray = data;
            } else if (Array.isArray(data.data)) {
                cargosArray = data.data;
            } else if (data.success && Array.isArray(data.data)) {
                cargosArray = data.data;
            } else {
                console.warn("⚠️ Estrutura de dados inesperada:", data);
                this.usarCargosFallback();
                return false;
            }
            
            this.cargosDisponiveis = cargosArray;
            
            // Limpar select
            this.selectCargo.innerHTML = '<option value="">Selecione um cargo</option>';
            
            // Adicionar opções com informação de setor
            cargosArray.forEach(cargo => {
                const option = document.createElement("option");
                option.value = cargo.id;
                
                // Formatar o texto para mostrar cargo + setor
                const setorNome = cargo.nome_setor || cargo.setor || 'Setor não definido';
                option.textContent = `${cargo.nome_cargo} - ${setorNome}`;
                
                // Armazenar dados adicionais no dataset
                option.dataset.nome = cargo.nome_cargo;
                option.dataset.setor = setorNome;
                option.dataset.setorId = cargo.setor_id;
                option.dataset.cargoId = cargo.id;
                
                this.selectCargo.appendChild(option);
            });
            
            console.log(` ${cargosArray.length} cargos carregados para seleção`);
            return true;
        } else {
            console.error(" API retornou success: false");
            this.usarCargosFallback();
            return false;
        }
    } catch (error) {
        console.error(" Erro ao carregar cargos:", error);
        this.usarCargosFallback();
        return false;
    }
}

// ===== MÉTODOS AUXILIARES PARA CARGOS =====
preencherSelectCargos(cargos) {
    if (!this.selectCargo) return;
    
    this.selectCargo.innerHTML = '<option value="">Selecione um cargo</option>';
    
    cargos.forEach(cargo => {
        const option = document.createElement("option");
        option.value = cargo.id;
        option.textContent = `${cargo.nome_cargo} - ${cargo.nome_setor || cargo.setor || 'Setor não definido'}`;
        option.dataset.nome = cargo.nome_cargo;
        option.dataset.setor = cargo.nome_setor || cargo.setor;
        this.selectCargo.appendChild(option);
    });
    
    console.log(` ${cargos.length} cargos carregados para seleção`);
}

usarCargosFallback() {
    if (!this.selectCargo) return;
    
    console.warn("⚠️ Usando cargos fallback devido a erro na API");
    
    this.selectCargo.innerHTML = `
        <option value="">Selecione um cargo</option>
        <option value="1">Analista - TI</option>
        <option value="2">Analista - Departamento Pessoal</option>
        <option value="3">Gerente - Administrativo</option>
        <option value="4">Assistente - RH</option>
    `;
    
    this.mostrarMensagem("Lista de cargos carregada com opções básicas", "warning");
}

// ===== MÉTODO AUXILIAR PARA BUSCAR CARGO POR NOME =====
async buscarCargoPorNome(nomeCargo) {
    if (!nomeCargo) return null;
    
    try {
        // Primeiro tenta carregar da API
        const { success, data } = await this.fetchJSON(`${this.BACKEND_URL}/cargos/buscar?nome=${encodeURIComponent(nomeCargo)}`);
        
        if (success && data) {
            const cargoData = data.data || data;
            if (cargoData && cargoData.id) {
                return cargoData;
            }
        }
        
        // Fallback: busca nos cargos já carregados
        const cargoEncontrado = this.cargosDisponiveis.find(cargo => 
            cargo.nome_cargo?.toLowerCase().includes(nomeCargo.toLowerCase()) ||
            cargo.nome?.toLowerCase().includes(nomeCargo.toLowerCase())
        );
        
        return cargoEncontrado || null;
    } catch (error) {
        console.error(" Erro ao buscar cargo por nome:", error);
        return null;
    }
} 


// async carregarBeneficiosPorCargo(cargoId) {
//     console.log(` Carregando benefícios para cargo: ${cargoId}`);
//     const { success, data, error } = await this.fetchJSON(`${this.BACKEND_URL}/beneficios/cargo/${cargoId}`);
//     //                                                          ↑↑↑↑ REMOVER /api EXTRA
    
//     console.log(" Resposta da API de benefícios:", { success, data, error });
    
//     if (success) {
//         // Tenta diferentes estruturas de resposta
//         const beneficiosData = data?.data || data || [];
//         if (Array.isArray(beneficiosData)) {
//             this.renderizarBeneficiosParaSelecao(this.containerBeneficios, beneficiosData);
//             return beneficiosData;
//         }
//     }
    
//     throw new Error(data?.message || "Falha ao carregar benefícios");
// }

// ===== MÉTODO MELHORADO PARA CARREGAR BENEFÍCIOS =====
async carregarBeneficiosPorCargo(cargoId) {
    if (!this.containerBeneficios) {
        console.error(" Container de benefícios não inicializado");
        return [];
    }

    this.containerBeneficios.innerHTML = "";

    if (!cargoId) {
        const mensagem = document.createElement("p");
        mensagem.className = "text-muted";
        mensagem.textContent = "Selecione um cargo para listar benefícios disponíveis.";
        this.containerBeneficios.appendChild(mensagem);
        return [];
    }

    try {
        console.log(` Carregando benefícios para cargo ID: ${cargoId}`);
        const url = `${this.BACKEND_URL}/beneficios/cargo/${cargoId}`;
        console.log("🔗 URL da API:", url);
        
        const { success, data, status } = await this.fetchJSON(url);
        
        console.log(" Resposta completa da API de benefícios:", { 
            success, 
            data, 
            status 
        });

        if (!success) {
            throw new Error(`API retornou erro: ${status} - ${data?.message || 'Erro desconhecido'}`);
        }

        // Tratamento flexível da resposta
        let beneficios = [];
        let contexto = null;
        
        if (Array.isArray(data)) {
            beneficios = data;
        } else if (Array.isArray(data?.data)) {
            beneficios = data.data;
            contexto = data.contexto;
        } else if (data?.success && Array.isArray(data.data)) {
            beneficios = data.data;
            contexto = data.contexto;
        }

        console.log(` ${beneficios.length} benefícios processados`, { contexto });

        if (beneficios.length === 0) {
            const mensagem = document.createElement("div");
            mensagem.className = "alert alert-info";
            mensagem.innerHTML = `
                <strong>Nenhum benefício disponível</strong><br>
                <small>Não foram encontrados benefícios para este cargo e setor.</small>
            `;
            this.containerBeneficios.appendChild(mensagem);
            return [];
        }

        this.renderizarBeneficiosParaSelecao(this.containerBeneficios, beneficios, contexto);
        console.log(` ${beneficios.length} benefícios renderizados para o cargo ${cargoId}`);
        
        return beneficios;
    } catch (error) {
        console.error(" Erro ao carregar benefícios por cargo:", error);
        
        const mensagem = document.createElement("div");
        mensagem.className = "alert alert-warning";
        mensagem.innerHTML = `
            <strong>Não foi possível carregar os benefícios</strong><br>
            <small>Erro: ${error.message}</small><br>
            <small>Verifique se existem benefícios cadastrados para este cargo e setor.</small>
        `;
        this.containerBeneficios.appendChild(mensagem);
        
        return [];
    }
}

// ===== MÉTODO MELHORADO PARA RENDERIZAR BENEFÍCIOS =====
// ===== MÉTODO MELHORADO PARA RENDERIZAR BENEFÍCIOS =====
// ===== MÉTODO MELHORADO PARA RENDERIZAR BENEFÍCIOS =====
// ===== MÉTODO MELHORADO PARA RENDERIZAR BENEFÍCIOS =====
// ===== MÉTODO MELHORADO PARA RENDERIZAR BENEFÍCIOS =====
// ===== MÉTODO MELHORADO PARA RENDERIZAR BENEFÍCIOS - CENTRALIZADO =====
// Substitua a função antiga por esta
renderizarBeneficiosParaSelecao(container, beneficios, contexto) {
  container.innerHTML = "";

  // container principal
  const beneficiosContainer = document.createElement("div");
  beneficiosContainer.className = "beneficios-container";
  // mantemos centralizado via CSS; não precisamos inline styles exceto por segurança
  beneficiosContainer.style.maxWidth = "100%";

  // header informativo
  const header = document.createElement("div");
  header.className = "beneficios-header";
  if (contexto) {
    header.innerHTML = `
      <strong>Benefícios para: ${contexto.cargo_nome || '-'} - ${contexto.setor_nome || '-'}</strong><br>
      <small>${beneficios.length} benefícios encontrados</small>
    `;
  } else {
    header.innerHTML = `
      <strong>Benefícios Encontrados: ${beneficios.length}</strong><br>
      <small>Marque os benefícios desejados</small>
    `;
  }
  beneficiosContainer.appendChild(header);

  if (!Array.isArray(beneficios) || beneficios.length === 0) {
    const emptyState = document.createElement("div");
    emptyState.className = "beneficios-empty";
    emptyState.innerHTML = `
      <div class="icon">📋</div>
      <strong>Nenhum benefício disponível</strong><br>
      <small>Não foram encontrados benefícios para este cargo e setor.</small>
    `;
    beneficiosContainer.appendChild(emptyState);
    container.appendChild(beneficiosContainer);
    return;
  }

  // set dos benefícios já selecionados (se estivermos editando)
  const beneficiosSelecionados = new Set();
  if (this.colaboradorSelecionado?.beneficios) {
    this.colaboradorSelecionado.beneficios.forEach(b => {
      if (b.beneficio_id) beneficiosSelecionados.add(String(b.beneficio_id));
      if (b.id) beneficiosSelecionados.add(String(b.id));
    });
  }

  // agrupar por tipo
  const beneficiosPorTipo = {
    cargo_setor_específico: beneficios.filter(b => b.contexto === 'cargo_setor_especifico'),
    cargo_específico: beneficios.filter(b => b.contexto === 'cargo_especifico'),
    setor_específico: beneficios.filter(b => b.contexto === 'setor_especifico'),
    geral: beneficios.filter(b => !b.contexto || b.contexto === 'geral')
  };

  // renderiza cada categoria (a função abaixo faz todo o markup e event handling)
  this.renderizarCategoriaBeneficios(beneficiosContainer, "🎯 Específicos para este cargo e setor", beneficiosPorTipo.cargo_setor_específico, beneficiosSelecionados);
  this.renderizarCategoriaBeneficios(beneficiosContainer, "💼 Para este cargo", beneficiosPorTipo.cargo_específico, beneficiosSelecionados);
  this.renderizarCategoriaBeneficios(beneficiosContainer, "Para este setor", beneficiosPorTipo.setor_específico, beneficiosSelecionados);
  this.renderizarCategoriaBeneficios(beneficiosContainer, "🌐 Benefícios gerais", beneficiosPorTipo.geral, beneficiosSelecionados);

  container.appendChild(beneficiosContainer);
}

// Nova função — cria os itens e lida com seleção/salvamento
renderizarCategoriaBeneficios(parent, tituloCategoria, listaBeneficios = [], beneficiosSelecionadosSet = new Set()) {
  if (!Array.isArray(listaBeneficios) || listaBeneficios.length === 0) return;

  const cat = document.createElement("section");
  cat.className = "beneficio-categoria";

  // Título categoria
  const h6 = document.createElement("h6");
  h6.textContent = tituloCategoria;
  cat.appendChild(h6);

  // Grid/container dos benefícios (vertical centralizado)
  const grid = document.createElement("div");
  grid.className = "beneficios-grid";

  // helper: formata valor como moeda (br) — adapta se tiver valor string/number
  const formatCurrency = (v) => {
    if (v === undefined || v === null || v === "") return "";
    let num = typeof v === 'number' ? v : parseFloat(String(v).replace(/[^\d\-,.]/g, '').replace(',', '.'));
    if (isNaN(num)) return String(v);
    return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
  };

  listaBeneficios.forEach(b => {
    // determina id e campos seguros
    const id = b.beneficio_id || b.id || b.beneficioId || b._id || `${b.nome}_${Math.random().toString(36).slice(2,7)}`;
    const nome = (b.nome || b.nome_do_beneficio || b.titulo || b.title || '').trim();
    const descricao = b.descricao || b.desc || b.descricao_curta || '';
    const valorRaw = b.valor || b.valor_beneficio || b.valor_padrao || b.valor_personalizado || "";
    const valorFormatado = formatCurrency(valorRaw);

    // item container (label para facilitar clique em todo o card)
    const label = document.createElement("label");
    label.className = "beneficio-item";
    label.setAttribute("role", "checkbox");
    label.setAttribute("tabindex", "0");
    label.style.cursor = "pointer";

    // input checkbox (visível apenas pela custom box)
    const input = document.createElement("input");
    input.type = "checkbox";
    input.className = "beneficio-checkbox";
    input.value = String(id);
    input.dataset.nome = nome;
    input.dataset.valor = String(valorRaw);
    // checked se já selecionado
    if (beneficiosSelecionadosSet.has(String(id))) {
      input.checked = true;
      label.classList.add("selected");
    }

    // custom checkbox visual
    const customBox = document.createElement("span");
    customBox.className = "beneficio-checkbox-custom";
    customBox.setAttribute("aria-hidden", "true");

    // conteúdo do benefício
    const content = document.createElement("div");
    content.className = "beneficio-content";

    const header = document.createElement("div");
    header.className = "beneficio-header";

    const titleEl = document.createElement("p");
    titleEl.className = "beneficio-title";
    titleEl.textContent = nome || "Sem nome";

    const valueEl = document.createElement("p");
    valueEl.className = "beneficio-value";
    valueEl.textContent = valorFormatado || "";

    header.appendChild(titleEl);
    header.appendChild(valueEl);

    // descrição opcional
    if (descricao) {
      const descEl = document.createElement("p");
      descEl.className = "beneficio-desc";
      descEl.textContent = descricao;
      content.appendChild(header);
      content.appendChild(descEl);
    } else {
      content.appendChild(header);
    }

    // monta label: checkbox (posicionado via CSS absolute), customBox e content
    label.appendChild(input);
    label.appendChild(customBox);
    label.appendChild(content);

    // Eventos
    // 1) quando usuário clica/checa
    input.addEventListener('change', (ev) => {
      label.classList.toggle('selected', input.checked);
      // atualiza o set local (opcional)
      if (input.checked) beneficiosSelecionadosSet.add(String(input.value));
      else beneficiosSelecionadosSet.delete(String(input.value));

      // chama debounce de salvamento (se existir)
      try {
        if (typeof this.atualizarBeneficiosNoServidorDebounced === "function") {
          this.atualizarBeneficiosNoServidorDebounced();
        }
      } catch (err) {
        console.warn("Erro ao chamar atualizarBeneficiosNoServidorDebounced:", err);
      }
    });

    // 2) clicando no card (label) já alterna input por ser label, mas acrescentamos suporte a keypress
    label.addEventListener('keydown', (ev) => {
      if (ev.key === 'Enter' || ev.key === ' ') {
        ev.preventDefault();
        input.checked = !input.checked;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });

    // 3) clique no conteúdo também deve alternar (caso label não envolva tudo)
    label.addEventListener('click', (ev) => {
      // evitar duplo toggle se clicar no input direto
      if (ev.target === input) return;
      input.checked = !input.checked;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });

    grid.appendChild(label);
  });

  cat.appendChild(grid);
  parent.appendChild(cat);
}

// ===== MÉTODO AUXILIAR PARA RENDERIZAR CATEGORIAS - CENTRALIZADO =====
renderizarCategoriaBeneficios(container, titulo, beneficios, beneficiosSelecionados) {
    if (!beneficios || beneficios.length === 0) return;

    const categoriaDiv = document.createElement("div");
    categoriaDiv.className = "beneficio-categoria";
    categoriaDiv.style.cssText = `
        width: 100%;
        max-width: 600px;
        margin: 0 auto 20px auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    `;
    
    const tituloCategoria = document.createElement("h6");
    tituloCategoria.className = "text-white mb-3";
    tituloCategoria.style.textAlign = "center";
    tituloCategoria.textContent = `${titulo} (${beneficios.length})`;
    categoriaDiv.appendChild(tituloCategoria);

    const grid = document.createElement("div");
    grid.className = "beneficios-grid";
    grid.style.cssText = `
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        gap: 12px;
    `;

    beneficios.forEach(beneficio => {
        const item = this.criarItemBeneficio(beneficio, beneficiosSelecionados);
        grid.appendChild(item);
    });

    categoriaDiv.appendChild(grid);
    container.appendChild(categoriaDiv);
}

// ===== MÉTODO PARA CRIAR ITEM DE BENEFÍCIO - LARGURA FIXA =====
criarItemBeneficio(beneficio, beneficiosSelecionados) {
    const item = document.createElement("label");
    item.className = "beneficio-item";
    item.style.cssText = `
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    `;
    
    const isSelected = beneficiosSelecionados.has(String(beneficio.id));
    if (isSelected) {
        item.classList.add("selected");
    }

    // Checkbox real (hidden)
    const input = document.createElement("input");
    input.type = "checkbox";
    input.className = "beneficio-checkbox";
    input.id = `beneficio_${beneficio.id}`;
    input.value = beneficio.id;
    input.checked = isSelected;
    input.dataset.valor = beneficio.valor_aplicado || 0;
    input.dataset.nome = beneficio.nome_do_beneficio || "Benefício";

    // Checkbox customizado
    const checkboxCustom = document.createElement("span");
    checkboxCustom.className = "beneficio-checkbox-custom";

    // Badge de contexto
    const contextoBadge = this.getContextoBadge(beneficio.contexto);
    
    // Informações do contexto
    const contextoInfo = [];
    if (beneficio.nome_cargo) contextoInfo.push(`Cargo: ${beneficio.nome_cargo}`);
    if (beneficio.nome_setor) contextoInfo.push(`Setor: ${beneficio.nome_setor}`);

    const content = document.createElement("div");
    content.className = "beneficio-content";
    
    content.innerHTML = `
        <div class="beneficio-header">
            <h6 class="beneficio-title">${beneficio.nome_do_beneficio || "Benefício"}</h6>
            ${contextoBadge}
        </div>
        <p class="beneficio-value">
            ${Number(beneficio.valor_aplicado || 0).toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}
        </p>
        ${beneficio.descricao_beneficio ? 
            `<p class="beneficio-desc">${beneficio.descricao_beneficio}</p>` : 
            ''}
        ${contextoInfo.length > 0 ? 
            `<div class="beneficio-contexto">
                <small>${contextoInfo.join(' • ')}</small>
            </div>` : 
            ''}
    `;

    item.appendChild(input);
    item.appendChild(checkboxCustom);
    item.appendChild(content);

    // Event listener para atualizar estado visual
    input.addEventListener("change", () => {
        item.classList.toggle("selected", input.checked);
        console.log(`Benefício ${beneficio.nome_do_beneficio} ${input.checked ? 'selecionado' : 'desselecionado'}`);

        // salva automaticamente (debounced)
        try {
            this.atualizarBeneficiosNoServidorDebounced();
        } catch (err) {
            console.error("Erro ao disparar atualização de benefícios:", err);
        }
    });

    return item;
}

// ===== MÉTODO AUXILIAR PARA BADGE DE CONTEXTO =====
getContextoBadge(contexto) {
    const badges = {
        'cargo_setor_especifico': '🎯 Específico',
        'cargo_especifico': '💼 Cargo',
        'setor_especifico': 'Setor',
        'geral': '🌐 Geral'
    };
    
    const badgeText = badges[contexto] || contexto || 'Geral';
    return `<span class="beneficio-badge">${badgeText}</span>`;
}

// ===== MÉTODO AUXILIAR PARA RENDERIZAR CATEGORIAS =====
renderizarCategoriaBeneficios(container, titulo, beneficios, beneficiosSelecionados) {
    if (!beneficios || beneficios.length === 0) return;

    const categoriaDiv = document.createElement("div");
    categoriaDiv.className = "beneficio-categoria";
    
    const tituloCategoria = document.createElement("h6");
    tituloCategoria.className = "text-white mb-3";
    tituloCategoria.textContent = `${titulo} (${beneficios.length})`;
    categoriaDiv.appendChild(tituloCategoria);

    const grid = document.createElement("div");
    grid.className = "beneficios-grid";

    beneficios.forEach(beneficio => {
        const item = this.criarItemBeneficio(beneficio, beneficiosSelecionados);
        grid.appendChild(item);
    });

    categoriaDiv.appendChild(grid);
    container.appendChild(categoriaDiv);
}

// ===== MÉTODO PARA CRIAR ITEM DE BENEFÍCIO COM CHECKBOX =====
criarItemBeneficio(beneficio, beneficiosSelecionados) {
    const item = document.createElement("label");
    item.className = "beneficio-item";
    
    const isSelected = beneficiosSelecionados.has(String(beneficio.id));
    if (isSelected) {
        item.classList.add("selected");
    }

    // Checkbox real (hidden)
    const input = document.createElement("input");
    input.type = "checkbox";
    input.className = "beneficio-checkbox";
    input.id = `beneficio_${beneficio.id}`;
    input.value = beneficio.id;
    input.checked = isSelected;
    input.dataset.valor = beneficio.valor_aplicado || 0;
    input.dataset.nome = beneficio.nome_do_beneficio || "Benefício";

    // Checkbox customizado
    const checkboxCustom = document.createElement("span");
    checkboxCustom.className = "beneficio-checkbox-custom";

    // Badge de contexto
    const contextoBadge = this.getContextoBadge(beneficio.contexto);
    
    // Informações do contexto
    const contextoInfo = [];
    if (beneficio.nome_cargo) contextoInfo.push(`Cargo: ${beneficio.nome_cargo}`);
    if (beneficio.nome_setor) contextoInfo.push(`Setor: ${beneficio.nome_setor}`);

    const content = document.createElement("div");
    content.className = "beneficio-content";
    
    content.innerHTML = `
        <div class="beneficio-header">
            <h6 class="beneficio-title">${beneficio.nome_do_beneficio || "Benefício"}</h6>
            ${contextoBadge}
        </div>
        <p class="beneficio-value">
            ${Number(beneficio.valor_aplicado || 0).toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}
        </p>
        ${beneficio.descricao_beneficio ? 
            `<p class="beneficio-desc">${beneficio.descricao_beneficio}</p>` : 
            ''}
        ${contextoInfo.length > 0 ? 
            `<div class="beneficio-contexto">
                <small>${contextoInfo.join(' • ')}</small>
            </div>` : 
            ''}
    `;

    item.appendChild(input);
    item.appendChild(checkboxCustom);
    item.appendChild(content);

    // Event listener para atualizar estado visual
 // dentro de criarItemBeneficio(...)
input.addEventListener("change", () => {
    item.classList.toggle("selected", input.checked);
    console.log(`Benefício ${beneficio.nome_do_beneficio} ${input.checked ? 'selecionado' : 'desselecionado'}`);

    // salva automaticamente (debounced)
    try {
      this.atualizarBeneficiosNoServidorDebounced();
    } catch (err) {
      console.error("Erro ao disparar atualização de benefícios:", err);
    }
});


    return item;
}

// ===== MÉTODO AUXILIAR PARA BADGE DE CONTEXTO =====
getContextoBadge(contexto) {
    const badges = {
        'cargo_setor_especifico': '🎯 Específico',
        'cargo_especifico': '💼 Cargo',
        'setor_especifico': 'Setor',
        'geral': '🌐 Geral'
    };
    
    const badgeText = badges[contexto] || contexto || 'Geral';
    return `<span class="beneficio-badge">${badgeText}</span>`;
}

// ===== Persistência de benefícios (debounced) =====
_atualizarBeneficiosTimeout = null;

/**
 * Debounce wrapper — chama a função real após 600ms sem novas mudanças.
 */
atualizarBeneficiosNoServidorDebounced() {
  if (this._atualizarBeneficiosTimeout) clearTimeout(this._atualizarBeneficiosTimeout);
  this._atualizarBeneficiosTimeout = setTimeout(() => this.atualizarBeneficiosNoServidor(), 600);
}

/**
 * Envia os benefícios atualmente marcados no modal para o servidor.
 * Endpoint esperado: PATCH /api/colaborador/:id/beneficios
 * (Se a sua API usa outro endpoint, ajuste a URL abaixo)
 */
async atualizarBeneficiosNoServidor() {
  try {
    if (!this.colaboradorSelecionado || !this.colaboradorSelecionado.id) {
      this.mostrarMensagem("Salve o colaborador antes de alterar benefícios.", "warning");
      return;
    }

    // Coleta os checkboxes visíveis do modal (containerBeneficios)
    const container = this.containerBeneficios || document.getElementById("containerBeneficios");
    if (!container) {
      console.warn("Container de benefícios não disponível ao salvar.");
      return;
    }

    const checkboxes = container.querySelectorAll('.beneficio-checkbox');
    const beneficiosSelecionados = [];

    checkboxes.forEach(cb => {
      if (cb.checked) {
        beneficiosSelecionados.push({
          beneficio_id: cb.value,
          nome_do_beneficio: cb.dataset.nome || "",
          valor_personalizado: parseFloat(cb.dataset.valor) || 0
        });
      }
    });

    // URL padrão — ajuste se seu backend usa outra rota
    const url = `${this.BACKEND_URL}/colaborador/${this.colaboradorSelecionado.id}/beneficios`;

    // Tentativa 1: PATCH para endpoint específico de benefícios
    let resp = await this.fetchJSON(url, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ beneficios: beneficiosSelecionados })
    });

    // Se endpoint PATCH não existir, tenta PUT no recurso do colaborador como fallback
    if (!resp.success && resp.status === 404) {
      const fallbackUrl = `${this.BACKEND_URL}/colaborador/${this.colaboradorSelecionado.id}`;
      resp = await this.fetchJSON(fallbackUrl, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ beneficios: beneficiosSelecionados })
      });
    }

    if (resp.success) {
      // Atualiza estado local
      this.colaboradorSelecionado.beneficios = beneficiosSelecionados;
      this.mostrarMensagem("Benefícios atualizados com sucesso.", "success");
      console.log("Benefícios salvos:", beneficiosSelecionados);
      return true;
    } else {
      console.error("Falha ao salvar benefícios:", resp);
      this.mostrarMensagem("Erro ao salvar benefícios. Verifique o servidor.", "danger");
      return false;
    }

  } catch (error) {
    console.error("Erro ao atualizar benefícios:", error);
    this.mostrarMensagem("Erro ao salvar benefícios.", "danger");
    return false;
  }
}

// ===== MÉTODO AUXILIAR PARA RENDERIZAR CATEGORIAS =====
renderizarCategoriaBeneficios(container, titulo, beneficios, beneficiosSelecionados) {
    if (!beneficios || beneficios.length === 0) return;

    const categoriaDiv = document.createElement("div");
    categoriaDiv.className = "beneficio-categoria";
    
    const tituloCategoria = document.createElement("h6");
    tituloCategoria.className = "text-white mb-3";
    tituloCategoria.textContent = `${titulo} (${beneficios.length})`;
    categoriaDiv.appendChild(tituloCategoria);

    const grid = document.createElement("div");
    grid.className = "beneficios-grid";

    beneficios.forEach(beneficio => {
        const item = this.criarItemBeneficio(beneficio, beneficiosSelecionados);
        grid.appendChild(item);
    });

    categoriaDiv.appendChild(grid);
    container.appendChild(categoriaDiv);
}

// ===== MÉTODO PARA CRIAR ITEM DE BENEFÍCIO COM CHECKBOX =====
criarItemBeneficio(beneficio, beneficiosSelecionados) {
    const item = document.createElement("label");
    item.className = "beneficio-item";
    
    const isSelected = beneficiosSelecionados.has(String(beneficio.id));
    if (isSelected) {
        item.classList.add("selected");
    }

    // Checkbox real (hidden)
    const input = document.createElement("input");
    input.type = "checkbox";
    input.className = "beneficio-checkbox";
    input.id = `beneficio_${beneficio.id}`;
    input.value = beneficio.id;
    input.checked = isSelected;
    input.dataset.valor = beneficio.valor_aplicado || 0;
    input.dataset.nome = beneficio.nome_do_beneficio || "Benefício";

    // Checkbox customizado
    const checkboxCustom = document.createElement("span");
    checkboxCustom.className = "beneficio-checkbox-custom";

    // Badge de contexto
    const contextoBadge = this.getContextoBadge(beneficio.contexto);
    
    // Informações do contexto
    const contextoInfo = [];
    if (beneficio.nome_cargo) contextoInfo.push(`Cargo: ${beneficio.nome_cargo}`);
    if (beneficio.nome_setor) contextoInfo.push(`Setor: ${beneficio.nome_setor}`);

    const content = document.createElement("div");
    content.className = "beneficio-content";
    
    content.innerHTML = `
        <div class="beneficio-header">
            <h6 class="beneficio-title">${beneficio.nome_do_beneficio || "Benefício"}</h6>
            ${contextoBadge}
        </div>
        <p class="beneficio-value">
            ${Number(beneficio.valor_aplicado || 0).toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}
        </p>
        ${beneficio.descricao_beneficio ? 
            `<p class="beneficio-desc">${beneficio.descricao_beneficio}</p>` : 
            ''}
        ${contextoInfo.length > 0 ? 
            `<div class="beneficio-contexto">
                <small>${contextoInfo.join(' • ')}</small>
            </div>` : 
            ''}
    `;

    item.appendChild(input);
    item.appendChild(checkboxCustom);
    item.appendChild(content);

    // Event listener para atualizar estado visual
    input.addEventListener("change", () => {
        item.classList.toggle("selected", input.checked);
        console.log(`Benefício ${beneficio.nome_do_beneficio} ${input.checked ? 'selecionado' : 'desselecionado'}`);
    });

    return item;
}

// ===== MÉTODO AUXILIAR PARA BADGE DE CONTEXTO =====
getContextoBadge(contexto) {
    const badges = {
        'cargo_setor_especifico': '🎯 Específico',
        'cargo_especifico': '💼 Cargo',
        'setor_especifico': 'Setor',
        'geral': '🌐 Geral'
    };
    
    const badgeText = badges[contexto] || contexto || 'Geral';
    return `<span class="beneficio-badge">${badgeText}</span>`;
}

// ===== MÉTODO AUXILIAR PARA RENDERIZAR CATEGORIAS =====
renderizarCategoriaBeneficios(container, titulo, beneficios, beneficiosSelecionados) {
    if (!beneficios || beneficios.length === 0) return;

    const categoriaDiv = document.createElement("div");
    categoriaDiv.className = "beneficio-categoria";
    
    const tituloCategoria = document.createElement("h6");
    tituloCategoria.className = "text-white mb-3";
    tituloCategoria.textContent = `${titulo} (${beneficios.length})`;
    categoriaDiv.appendChild(tituloCategoria);

    const grid = document.createElement("div");
    grid.className = "beneficios-grid";

    beneficios.forEach(beneficio => {
        const card = this.criarCardBeneficio(beneficio, beneficiosSelecionados);
        grid.appendChild(card);
    });

    categoriaDiv.appendChild(grid);
    container.appendChild(categoriaDiv);
}

// ===== MÉTODO PARA CRIAR CARD DE BENEFÍCIO =====
criarCardBeneficio(beneficio, beneficiosSelecionados) {
    const card = document.createElement("div");
    card.className = "beneficio-card";
    
    const isSelected = beneficiosSelecionados.has(String(beneficio.id));
    if (isSelected) {
        card.classList.add("selected");
    }

    const input = document.createElement("input");
    input.type = "checkbox";
    input.className = "beneficio-checkbox";
    input.id = `beneficio_${beneficio.id}`;
    input.value = beneficio.id;
    input.checked = isSelected;
    input.dataset.valor = beneficio.valor_aplicado || 0;
    input.dataset.nome = beneficio.nome_do_beneficio || "Benefício";

    // Badge de contexto
    const contextoBadge = this.getContextoBadge(beneficio.contexto);
    
    // Informações do contexto
    const contextoInfo = [];
    if (beneficio.nome_cargo) contextoInfo.push(`Cargo: ${beneficio.nome_cargo}`);
    if (beneficio.nome_setor) contextoInfo.push(`Setor: ${beneficio.nome_setor}`);

    const cardContent = document.createElement("div");
    cardContent.className = "beneficio-card-content";
    
    cardContent.innerHTML = `
        <div class="beneficio-card-header">
            <h6 class="beneficio-card-title">${beneficio.nome_do_beneficio || "Benefício"}</h6>
            ${contextoBadge}
        </div>
        <p class="beneficio-card-value">
            ${Number(beneficio.valor_aplicado || 0).toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}
        </p>
        ${beneficio.descricao_beneficio ? 
            `<p class="beneficio-card-desc">${beneficio.descricao_beneficio}</p>` : 
            ''}
        ${contextoInfo.length > 0 ? 
            `<div class="beneficio-card-contexto">
                <small>${contextoInfo.join(' • ')}</small>
            </div>` : 
            ''}
    `;

    card.appendChild(input);
    card.appendChild(cardContent);

    // Event listener para clique no card
    card.addEventListener("click", (e) => {
        // Impede que o clique no checkbox dispare o evento duas vezes
        if (e.target.tagName === 'INPUT') return;
        
        input.checked = !input.checked;
        card.classList.toggle("selected", input.checked);
        this.atualizarBeneficiosNoServidorDebounced();

        console.log(`Benefício ${beneficio.nome_do_beneficio} ${input.checked ? 'selecionado' : 'desselecionado'}`);
    });

    return card;
}

// ===== MÉTODO AUXILIAR PARA BADGE DE CONTEXTO =====
getContextoBadge(contexto) {
    const badges = {
        'cargo_setor_especifico': '🎯 Específico',
        'cargo_especifico': '💼 Cargo',
        'setor_especifico': 'Setor',
        'geral': '🌐 Geral'
    };
    
    const badgeText = badges[contexto] || contexto || 'Geral';
    return `<span class="beneficio-badge">${badgeText}</span>`;
}

// ===== MÉTODO AUXILIAR PARA RENDERIZAR CATEGORIAS =====
renderizarCategoriaBeneficios(container, titulo, beneficios, beneficiosSelecionados) {
    if (!beneficios || beneficios.length === 0) return;

    const categoriaDiv = document.createElement("div");
    categoriaDiv.className = "beneficio-categoria";
    
    const tituloCategoria = document.createElement("h6");
    tituloCategoria.className = "text-primary mb-2";
    tituloCategoria.textContent = `${titulo} (${beneficios.length})`;
    categoriaDiv.appendChild(tituloCategoria);

    const grid = document.createElement("div");
    grid.className = "beneficios-grid";

    beneficios.forEach(beneficio => {
        const card = this.criarCardBeneficio(beneficio, beneficiosSelecionados);
        grid.appendChild(card);
    });

    categoriaDiv.appendChild(grid);
    container.appendChild(categoriaDiv);
}

// // ===== MÉTODO PARA CRIAR CARD DE BENEFÍCIO =====
// criarCardBeneficio(beneficio, beneficiosSelecionados) {
//     const card = document.createElement("div");
//     card.className = "beneficio-card";
    
//     const isSelected = beneficiosSelecionados.has(String(beneficio.id));
//     if (isSelected) {
//         card.classList.add("selected");
//     }

//     const input = document.createElement("input");
//     input.type = "checkbox";
//     input.className = "beneficio-checkbox";
//     input.id = `beneficio_${beneficio.id}`;
//     input.value = beneficio.id;
//     input.checked = isSelected;
//     input.dataset.valor = beneficio.valor_aplicado || 0;
//     input.dataset.nome = beneficio.nome_do_beneficio || "Benefício";

//     // Badge de contexto
//     const contextoBadge = this.getContextoBadge(beneficio.contexto);
    
//     // Informações do contexto
//     const contextoInfo = [];
//     if (beneficio.nome_cargo) contextoInfo.push(`Cargo: ${beneficio.nome_cargo}`);
//     if (beneficio.nome_setor) contextoInfo.push(`Setor: ${beneficio.nome_setor}`);

//     const cardContent = document.createElement("div");
//     cardContent.className = "beneficio-card-content";
    
//     cardContent.innerHTML = `
//         <div class="beneficio-card-header">
//             <h6 class="beneficio-card-title">${beneficio.nome_do_beneficio || "Benefício"}</h6>
//             ${contextoBadge}
//         </div>
//         <p class="beneficio-card-value">
//             ${Number(beneficio.valor_aplicado || 0).toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}
//         </p>
//         ${beneficio.descricao_beneficio ? 
//             `<p class="beneficio-card-desc">${beneficio.descricao_beneficio}</p>` : 
//             ''}
//         ${contextoInfo.length > 0 ? 
//             `<div class="beneficio-card-contexto">
//                 <small>${contextoInfo.join(' • ')}</small>
//             </div>` : 
//             ''}
//     `;

//     card.appendChild(input);
//     card.appendChild(cardContent);

//     // Event listener para clique no card
//     card.addEventListener("click", (e) => {
//         if (e.target.tagName !== 'INPUT') {
//             input.checked = !input.checked;
//             card.classList.toggle("selected", input.checked);
//         }
//     });

//     return card;
// }

// ===== MÉTODO AUXILIAR PARA BADGE DE CONTEXTO =====
getContextoBadge(contexto) {
    const badges = {
        'cargo_setor_especifico': '🎯 Específico',
        'cargo_especifico': '💼 Cargo',
        'setor_especifico': 'Setor',
        'geral': '🌐 Geral'
    };
    
    const badgeText = badges[contexto] || contexto || 'Geral';
    return `<span class="beneficio-badge">${badgeText}</span>`;
}

// ===== MÉTODO AUXILIAR PARA RENDERIZAR CATEGORIAS =====
renderizarCategoriaBeneficios(container, titulo, beneficios, beneficiosSelecionados) {
    if (!beneficios || beneficios.length === 0) return;

    const categoriaDiv = document.createElement("div");
    categoriaDiv.className = "beneficio-categoria mb-3";
    
    const tituloCategoria = document.createElement("h6");
    tituloCategoria.className = "text-primary mb-2";
    tituloCategoria.textContent = `${titulo} (${beneficios.length})`;
    categoriaDiv.appendChild(tituloCategoria);

    beneficios.forEach(beneficio => {
        const item = document.createElement("div");
        item.className = "beneficio-item mb-2 p-2 border rounded";
        
        const input = document.createElement("input");
        input.type = "checkbox";
        input.className = "beneficio-checkbox me-2";
        input.id = `beneficio_${beneficio.id}`;
        input.value = beneficio.id;
        input.checked = beneficiosSelecionados.has(String(beneficio.id));
        input.dataset.valor = beneficio.valor_aplicado || 0;
        input.dataset.nome = beneficio.nome_do_beneficio || "Benefício";
        
        const label = document.createElement("label");
        label.className = "beneficio-label";
        label.htmlFor = input.id;
        label.style.cursor = "pointer";
        
        // Badge de contexto
        const contextoBadge = beneficio.contexto ? 
            `<span class="badge bg-secondary ms-2">${beneficio.contexto.replace('_', ' ')}</span>` : '';
        
        label.innerHTML = `
            <div class="beneficio-info">
                <strong>${beneficio.nome_do_beneficio || "Benefício"}</strong>
                ${contextoBadge}
                <span class="beneficio-valor text-success float-end">
                    ${Number(beneficio.valor_aplicado || 0).toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}
                </span>
            </div>
            ${beneficio.descricao_beneficio ? 
                `<small class="beneficio-descricao text-muted">${beneficio.descricao_beneficio}</small>` : 
                ''}
            ${beneficio.nome_cargo || beneficio.nome_setor ? 
                `<div class="beneficio-contexto mt-1">
                    <small class="text-info">
                        ${beneficio.nome_cargo ? `Cargo: ${beneficio.nome_cargo}` : ''}
                        ${beneficio.nome_setor ? `Setor: ${beneficio.nome_setor}` : ''}
                    </small>
                </div>` : 
                ''}
        `;
        
        item.appendChild(input);
        item.appendChild(label);
        categoriaDiv.appendChild(item);
    });

    container.appendChild(categoriaDiv);
}

    renderizarBeneficiosParaSelecao(container, beneficios) {
        // Header
        const header = document.createElement("div");
        header.className = "beneficios-header";
        header.innerHTML = `<strong>Benefícios encontrados: ${beneficios.length}</strong>`;
        container.appendChild(header);

        // IDs dos benefícios já selecionados (para edição)
        const beneficiosSelecionados = new Set();
        if (this.colaboradorSelecionado?.beneficios) {
            this.colaboradorSelecionado.beneficios.forEach(b => {
                if (b.beneficio_id) beneficiosSelecionados.add(String(b.beneficio_id));
                if (b.id) beneficiosSelecionados.add(String(b.id));
            });
        }

        // Lista de benefícios
        beneficios.forEach(beneficio => {
            const item = document.createElement("div");
            item.className = "beneficio-item";
            
            const input = document.createElement("input");
            input.type = "checkbox";
            input.className = "beneficio-checkbox";
            input.id = `beneficio_${beneficio.id}`;
            input.value = beneficio.id;
            input.checked = beneficiosSelecionados.has(String(beneficio.id));
            input.dataset.valor = beneficio.valor_aplicado || 0;
            input.dataset.nome = beneficio.nome_do_beneficio || "Benefício";
            
            const label = document.createElement("label");
            label.className = "beneficio-label";
            label.htmlFor = input.id;
            label.innerHTML = `
                <div class="beneficio-info">
                    <strong>${beneficio.nome_do_beneficio || "Benefício"}</strong>
                    <span class="beneficio-valor">${Number(beneficio.valor_aplicado || 0).toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</span>
                </div>
                ${beneficio.descricao_beneficio ? `<small class="beneficio-descricao">${beneficio.descricao_beneficio}</small>` : ''}
            `;
            
            item.appendChild(input);
            item.appendChild(label);
            container.appendChild(item);
        });
    }

    // ===== MODAL DE COLABORADOR =====
    resetarModalColaborador() {
        this.colaboradorSelecionado = null;
        
        if (this.modalColab) {
            this.modalColab.hide();
        }
        
        if (this.overlay) {
            this.overlay.style.display = "none";
        }
        
        if (this.formColaborador) {
            this.formColaborador.reset();
        }
        
        if (this.previewImage) {
            this.previewImage.src = "/img/fundofoda.png";
        }
        
        const titulo = document.getElementById("tituloModalColaborador");
        if (titulo) {
            titulo.textContent = "Cadastrar Colaborador";
        }
        
        // Limpar benefícios
        const containerBeneficios = document.getElementById("containerBeneficios");
        if (containerBeneficios) {
            containerBeneficios.innerHTML = '<p class="text-muted">Selecione um cargo para listar benefícios disponíveis.</p>';
        }
    }

   async abrirModalCadastro() {
    if (!this.usuarioAtual) {
        await this.carregarUsuario();
    }
    
    if (!this.usuarioAtual) {
        this.mostrarMensagem(" Usuário não autenticado", "danger");
        return;
    }

    this.resetarModalColaborador();
    
    // Atualizar título
    if (this.tituloModal) {
        this.tituloModal.textContent = "Cadastrar Colaborador";
    }
    
    // Carregar cargos
    await this.carregarCargosParaSelect();
    
    // Obter próximo número de registro
    await this.obterProximoRegistro();
    
    // Mostrar modal
    if (this.modalColab) {
        this.modalColab.show();
    } else {
        console.error(" Modal de colaborador não inicializado");
        this.mostrarMensagem("Erro ao abrir modal de cadastro", "danger");
    }
}

async obterProximoRegistro() {
    if (!this.inputNumeroRegistro) {
        console.warn("⚠️ Campo número de registro não encontrado");
        return;
    }

    try {
        const { success, data } = await this.fetchJSON(
            `${this.BACKEND_URL}/colaborador/nextRegistro?empresa_id=${this.usuarioAtual.empresa_id}`
        );
        
        if (success) {
            this.inputNumeroRegistro.value = data?.proximoRegistro || "C001";
            console.log(" Próximo registro:", this.inputNumeroRegistro.value);
        } else {
            this.inputNumeroRegistro.value = "C001";
        }
    } catch (error) {
        console.error(" Erro ao obter próximo registro:", error);
        this.inputNumeroRegistro.value = "C001";
    }
}

   async abrirModalEdicao() {
    if (!this.colaboradorSelecionado) {
        console.error(" Nenhum colaborador selecionado para edição");
        return;
    }
    
    console.log("Abrindo modal de edição para:", this.colaboradorSelecionado.nome);
    
    // Fechar overlay se estiver aberto
    if (this.overlay) {
        this.overlay.style.display = "none";
    }

    // Preencher dados básicos com verificações de segurança
    this.preencherDadosBasicos();

    // Atualizar preview da foto
  if (this.previewImage) {
        const fotoUrl = this.getFotoUrl(this.colaboradorSelecionado.foto);
        this.previewImage.src = fotoUrl;
        this.previewImage.onerror = () => {
            this.previewImage.src = "/img/fundofoda.png";
        };
    }
    // Atualizar título do modal
    if (this.tituloModal) {
        this.tituloModal.textContent = "Editar Colaborador";
    }

    // Carregar cargos e selecionar o atual
    await this.carregarCargosParaSelect();
    
    await this.selecionarCargoAtual();
    
    // Mostrar modal
    if (this.modalColab) {
        this.modalColab.show();
    } else {
        console.error(" Modal de colaborador não inicializado");
    }
}

preencherDadosBasicos() {
    const campos = {
        "numero_registro": this.colaboradorSelecionado.numero_registro || "",
        "nome": this.colaboradorSelecionado.nome || "",
        "cpf": this.colaboradorSelecionado.cpf || "",
        "setor": this.colaboradorSelecionado.setor || "",
        "tipo_jornada": this.colaboradorSelecionado.tipo_jornada || "",
        "horas_diarias": this.colaboradorSelecionado.horas_diarias || "",
        "salario": this.colaboradorSelecionado.salario ?? ""
    };

    Object.entries(campos).forEach(([campo, valor]) => {
        const elemento = document.getElementById(campo);
        if (elemento) {
            elemento.value = valor;
        } else {
            console.warn(`⚠️ Elemento não encontrado: ${campo}`);
        }
    });
}

async selecionarCargoAtual() {
    if (!this.selectCargo) {
        console.error(" Select de cargo não inicializado");
        return;
    }

    if (!this.colaboradorSelecionado?.cargo || !this.colaboradorSelecionado?.setor) {
        console.warn("⚠️ Colaborador não tem cargo ou setor definido");
        return;
    }

    console.log("🎯 Procurando cargo:", this.colaboradorSelecionado.cargo, "no setor:", this.colaboradorSelecionado.setor);

    try {
        // Aguardar carregamento dos cargos
        await new Promise(resolve => setTimeout(resolve, 500));
        
        if (!this.selectCargo.options) {
            console.error(" Select de cargo não tem options disponíveis");
            return;
        }

        // Converter HTMLOptionsCollection para array de forma segura
        const options = Array.from(this.selectCargo.options).filter(opt => opt.value !== "");
        
        console.log(` ${options.length} opções disponíveis no select`);

        if (options.length === 0) {
            console.warn("⚠️ Nenhuma opção de cargo disponível");
            this.mostrarMensagemCargoNaoEncontrado();
            return;
        }

        // Busca considerando cargo E setor
        const cargoProcurado = this.colaboradorSelecionado.cargo.toLowerCase();
        const setorProcurado = this.colaboradorSelecionado.setor.toLowerCase();
        
        const optionEncontrado = options.find(opt => {
            const textoOption = opt.textContent.toLowerCase();
            const datasetNome = opt.dataset.nome?.toLowerCase();
            const datasetSetor = opt.dataset.setor?.toLowerCase();
            
            // Estratégias de busca:
            // 1. Match exato no texto completo
            const matchTextoCompleto = textoOption.includes(cargoProcurado) && textoOption.includes(setorProcurado);
            
            // 2. Match no dataset (cargo e setor separados)
            const matchDataset = datasetNome === cargoProcurado && datasetSetor === setorProcurado;
            
            // 3. Match parcial
            const matchParcial = datasetNome?.includes(cargoProcurado) && datasetSetor?.includes(setorProcurado);
            
            return matchTextoCompleto || matchDataset || matchParcial;
        });
        
        if (optionEncontrado) {
            this.selectCargo.value = optionEncontrado.value;
            console.log(" Cargo selecionado:", {
                valor: optionEncontrado.value,
                texto: optionEncontrado.textContent,
                cargo: optionEncontrado.dataset.nome,
                setor: optionEncontrado.dataset.setor
            });
            
            // Atualizar também o campo setor se necessário
            const setorInput = document.getElementById("setor");
            if (setorInput && optionEncontrado.dataset.setor) {
                setorInput.value = optionEncontrado.dataset.setor;
            }
            
            // Carregar benefícios automaticamente
            setTimeout(async () => {
                await this.carregarBeneficiosPorCargo(optionEncontrado.value);
            }, 200);
        } else {
            console.warn(` Cargo "${this.colaboradorSelecionado.cargo}" no setor "${this.colaboradorSelecionado.setor}" não encontrado`);
            console.log("Opções disponíveis:", options.map(opt => ({
                valor: opt.value,
                texto: opt.textContent,
                cargo: opt.dataset.nome,
                setor: opt.dataset.setor
            })));
            this.mostrarMensagemCargoNaoEncontrado();
        }
    } catch (error) {
        console.error(" Erro ao selecionar cargo:", error);
        this.mostrarMensagem("Erro ao carregar cargo do colaborador", "warning");
    }
}
mostrarMensagemCargoNaoEncontrado() {
    if (this.containerBeneficios) {
        this.containerBeneficios.innerHTML = `
            <div class="alert alert-warning">
                <strong>Cargo "${this.colaboradorSelecionado.cargo}" não encontrado na lista.</strong><br>
                <small>Selecione um cargo válido na lista acima para carregar os benefícios.</small>
            </div>
        `;
    }
}

// ===== MÉTODO salvarColaborador ATUALIZADO =====
// ===== MÉTODO salvarColaborador CORRIGIDO - CADASTRO EM DUAS ETAPAS =====
async salvarColaborador(event) {
    event.preventDefault();

    try {
        console.log(' Iniciando salvamento do colaborador...');

        if (!this.usuarioAtual) {
            await this.carregarUsuario();
        }

        if (!this.usuarioAtual?.empresa_id) {
            throw new Error("Empresa não identificada");
        }

        // ===== VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS =====
        const camposObrigatorios = {
            numero_registro: this.inputNumeroRegistro?.value,
            nome: this.inputNome?.value,
            cpf: this.inputCPF?.value,
            senha: document.getElementById("senha")?.value,
            setor: this.inputSetor?.value,
            cargo: this.selectCargo?.value,
            tipo_jornada: this.inputTipoJornada?.value,
            horas_diarias: this.inputHorasDiarias?.value,
            salario: this.inputSalario?.value
        };

        // Verificar campos vazios
        const camposVazios = Object.entries(camposObrigatorios)
            .filter(([key, value]) => !value || value.toString().trim() === '')
            .map(([key]) => key);

        if (camposVazios.length > 0) {
            throw new Error(`Campos obrigatórios não preenchidos: ${camposVazios.join(', ')}`);
        }

        // ===== COLETAR DADOS DO FORMULÁRIO =====
        const optionSelecionada = this.selectCargo?.options[this.selectCargo.selectedIndex];
        const cargoNome = optionSelecionada?.dataset.nome || "";
        const setorDoCargo = optionSelecionada?.dataset.setor || "";
        const setorFinal = camposObrigatorios.setor || setorDoCargo;

        const dadosColaborador = {
            empresa_id: this.usuarioAtual.empresa_id,
            numero_registro: camposObrigatorios.numero_registro.trim(),
            nome: camposObrigatorios.nome.trim(),
            cpf: camposObrigatorios.cpf.trim().replace(/\D/g, ''),
            senha: camposObrigatorios.senha,
            setor: setorFinal.trim(),
            cargo: cargoNome.trim(),
            cargo_id: camposObrigatorios.cargo,
            tipo_jornada: camposObrigatorios.tipo_jornada.trim(),
            horas_diarias: parseFloat(camposObrigatorios.horas_diarias) || 0,
            salario: parseFloat(camposObrigatorios.salario) || 0
        };

        // Validações específicas
        if (dadosColaborador.horas_diarias <= 0) {
            throw new Error("Horas diárias devem ser maiores que zero");
        }

        if (dadosColaborador.salario <= 0) {
            throw new Error("Salário deve ser maior que zero");
        }

        // ===== COLETAR BENEFÍCIOS SELECIONADOS =====
        const beneficiosSelecionados = [];
        const checkboxes = this.containerBeneficios?.querySelectorAll('.beneficio-checkbox:checked') || [];
        
        checkboxes.forEach(checkbox => {
            if (checkbox.value && checkbox.value !== 'undefined') {
                beneficiosSelecionados.push({
                    beneficio_id: checkbox.value,
                    nome_do_beneficio: checkbox.dataset.nome || "",
                    valor_personalizado: parseFloat(checkbox.dataset.valor) || 0
                });
            }
        });

        console.log(' Dados para envio:', {
            ...dadosColaborador,
            beneficios_count: beneficiosSelecionados.length
        });

        // ===== PREPARAR ENVIO =====
        const formDataEnvio = new FormData();
        
        // Adicionar campos individuais
        Object.entries(dadosColaborador).forEach(([key, value]) => {
            formDataEnvio.append(key, value.toString());
        });

        // ❌ NÃO ADICIONAR BENEFÍCIOS NO CADASTRO INICIAL
        // formDataEnvio.append('beneficios', JSON.stringify(beneficiosSelecionados));

        // Adicionar foto se existir
        if (this.fotoInput?.files[0]) {
            formDataEnvio.append('foto', this.fotoInput.files[0]);
            console.log(' Foto anexada:', this.fotoInput.files[0].name);
        }

        // ===== DETERMINAR URL E MÉTODO =====
        const isEdicao = this.colaboradorSelecionado?.id;
        let url, method, colaboradorId;

        if (isEdicao) {
            // EDIÇÃO: atualizar colaborador existente
            url = `${this.BACKEND_URL}/colaborador/atualizar`;
            method = "PUT";
            colaboradorId = this.colaboradorSelecionado.id;
            formDataEnvio.append('id', colaboradorId.toString());
        } else {
            // CADASTRO: criar novo colaborador
            url = `${this.BACKEND_URL}/colaborador/register`;
            method = "POST";
        }

        console.log(` Enviando para: ${url}`);
        console.log(` Método: ${method}`);

        // ===== ETAPA 1: SALVAR/ATUALIZAR COLABORADOR =====
        const response = await fetch(url, {
            method: method,
            headers: {
                'Authorization': `Bearer ${this.token}`
            },
            credentials: 'include',
            body: formDataEnvio
        });

        // Tratar resposta da API
        const responseData = await response.json().catch(() => ({}));
        
        console.log(' Resposta da API:', {
            status: response.status,
            ok: response.ok,
            data: responseData
        });

        if (!response.ok) {
            const mensagemErro = responseData?.message || 
                                responseData?.error || 
                                `Erro ${response.status} ao salvar colaborador`;
            throw new Error(mensagemErro);
        }

        // Extrair ID do colaborador criado/atualizado
        if (!isEdicao) {
            // Para cadastro novo, pegar ID da resposta
            colaboradorId = responseData?.id || 
                           responseData?.data?.id || 
                           responseData?.colaborador?.id ||
                           responseData?.insertId;
            
            if (!colaboradorId) {
                console.error(' ID do colaborador não encontrado na resposta:', responseData);
                throw new Error('Colaborador criado, mas ID não foi retornado pela API');
            }
            
            console.log(` Colaborador criado com ID: ${colaboradorId}`);
        } else {
            console.log(` Colaborador ${colaboradorId} atualizado`);
        }

        // ===== ETAPA 2: SALVAR BENEFÍCIOS (SE HOUVER) =====
        if (beneficiosSelecionados.length > 0) {
            console.log(` Salvando ${beneficiosSelecionados.length} benefícios para colaborador ${colaboradorId}...`);
            
            try {
                const beneficiosUrl = `${this.BACKEND_URL}/colaborador/${colaboradorId}/beneficios`;
                
                const beneficiosResponse = await fetch(beneficiosUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.token}`
                    },
                    credentials: 'include',
                    body: JSON.stringify({ 
                        beneficios: beneficiosSelecionados 
                    })
                });

                const beneficiosData = await beneficiosResponse.json().catch(() => ({}));
                
                if (beneficiosResponse.ok) {
                    console.log('Benefícios salvos com sucesso');
                } else {
                    console.warn(' Erro ao salvar benefícios:', beneficiosData);
                    this.mostrarMensagem(
                        'Colaborador salvo, mas houve um problema ao vincular os benefícios. ' +
                        'Você pode editá-lo para adicionar os benefícios.', 
                        'warning'
                    );
                }
            } catch (beneficiosError) {
                console.error(' Erro ao salvar benefícios:', beneficiosError);
                this.mostrarMensagem(
                    'Colaborador salvo, mas não foi possível vincular os benefícios. ' +
                    'Edite o colaborador para adicionar os benefícios.', 
                    'warning'
                );
            }
        } else {
            console.log('ℹ Nenhum benefício selecionado');
        }

        // ===== SUCESSO FINAL =====
        const mensagemSucesso = isEdicao 
            ? "Colaborador atualizado com sucesso!" 
            : " Colaborador cadastrado com sucesso!";
        
        this.mostrarMensagem(mensagemSucesso, "success");
        
        // Fechar modal e recarregar lista
        if (this.modalColab) {
            this.modalColab.hide();
        }
        
        // Aguardar um pouco antes de recarregar para garantir que o banco foi atualizado
        await new Promise(resolve => setTimeout(resolve, 500));
        await this.carregarColaboradores(this.setorSelect?.value || "");

    } catch (error) {
        console.error(' Erro ao salvar colaborador:', error);
        this.mostrarMensagem(
            error.message || "Erro ao salvar colaborador. Verifique o console.", 
            "danger"
        );
    }
}
    async excluirColaborador() {
        if (!this.colaboradorSelecionado) return;
        
        if (!confirm(`Tem certeza que deseja excluir ${this.colaboradorSelecionado.nome}?`)) {
            return;
        }

        try {
            const { success } = await this.fetchJSON(
                `${this.BACKEND_URL}/colaborador/${this.colaboradorSelecionado.id}`,
                { method: "DELETE" }
            );

            if (!success) {
                throw new Error("Erro ao excluir colaborador");
            }

            this.mostrarMensagem(` Colaborador ${this.colaboradorSelecionado.nome} excluído com sucesso!`, "success");
            
            this.resetarModalColaborador();
            await this.carregarColaboradores(this.setorSelect?.value || "");
            
        } catch (error) {
            console.error("Erro ao excluir colaborador:", error);
            this.mostrarMensagem("Erro ao excluir colaborador", "danger");
        }
    }

    // ===== CONFIGURAÇÃO DE EVENT LISTENERS =====
    configurarEventListeners() {
        console.log("🔧 Configurando event listeners...");
        
        // Botão de trocar exibição
        this.btnTrocarExibicao?.addEventListener("click", () => {
            this.modoExibicao = this.modoExibicao === "cards" ? "lista" : "cards";
            this.renderizarColaboradores();
        });

        // Filtro de setor
        this.setorSelect?.addEventListener("change", () => {
            this.carregarColaboradores(this.setorSelect.value || "");
        });

        // Busca
        this.buscaInput?.addEventListener("input", () => {
            const termo = (this.buscaInput.value || "").toLowerCase();
            const filtrados = this.colaboradores.filter(colab =>
                (colab.nome || "").toLowerCase().includes(termo) ||
                (colab.cpf || "").includes(termo) ||
                (colab.numero_registro || "").includes(termo)
            );
            this.renderizarColaboradores(filtrados);
        });

        // Overlay events
        this.overlay?.addEventListener("click", (event) => {
            if (event.target === this.overlay) {
                this.resetarModalColaborador();
            }
        });

        this.btnFecharColab?.addEventListener("click", () => {
            this.resetarModalColaborador();
        });

        this.btnEditarColab?.addEventListener("click", () => {
            this.abrirModalEdicao();
        });

        this.btnExcluirColab?.addEventListener("click", () => {
            this.excluirColaborador();
        });

        // Botão registrar
        this.btnRegistrar?.addEventListener("click", () => {
            this.abrirModalCadastro();
        });

        // Select de cargo no modal
     const selectCargo = document.getElementById("cargo");
if (selectCargo) {
    selectCargo.addEventListener("change", async (event) => {
        const cargoId = event.target.value;
        const optionSelecionada = event.target.options[event.target.selectedIndex];
        
        // Atualizar automaticamente o campo setor com o setor do cargo
        if (optionSelecionada && optionSelecionada.dataset.setor) {
            const setorInput = document.getElementById("setor");
            if (setorInput) {
                setorInput.value = optionSelecionada.dataset.setor;
                console.log(" Setor atualizado para:", optionSelecionada.dataset.setor);
            }
        }
        
        await this.carregarBeneficiosPorCargo(cargoId);
    });
}


        // Formulário de colaborador
        this.formColaborador?.addEventListener("submit", (event) => {
            this.salvarColaborador(event);
        });

        console.log(" Event listeners configurados");
    }
}

// Inicializar a aplicação quando o DOM estiver pronto
document.addEventListener("DOMContentLoaded", () => {
    new GerenciamentoColaboradores();
});