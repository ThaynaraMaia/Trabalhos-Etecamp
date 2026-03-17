// frontend/public/js/verificarLogin.js
document.addEventListener("DOMContentLoaded", async () => {
  console.log("verificarLogin.js carregado. Verificando autenticação...");

  // Pegar token do LocalStorage, SessionStorage ou cookie
  let token = localStorage.getItem("token") || sessionStorage.getItem("token");
  if (!token && document.cookie.includes("token=")) {
    const match = document.cookie.match(/token=([^;]+)/);
    if (match) token = match[1];
  }

  console.log("🔑 Token encontrado?", token ? `${token.substring(0, 20)}...` : "Não");

  if (!token) {
    console.warn("⚠️ Nenhum token encontrado. Página acessível sem autenticação (ex: home).");
    // limpa resquícios se houver
    localStorage.removeItem("token");
    sessionStorage.removeItem("token");
    return;
  }

  // Ajuste a porta se seu backend rodar em outra porta (3001 é o padrão que você usa)
  const BACKEND_URL = "http://localhost:3001";
  const urlValidacao = `${BACKEND_URL}/api/auth/me`;
  console.log("🌐 Validando token em:", urlValidacao);

  try {
    const res = await fetch(urlValidacao, {
      method: "GET",
      headers: { "Authorization": `Bearer ${token}` }
    });

    console.log("📡 /api/auth/me status:", res.status, res.statusText);

    // Se não for 2xx, não tentar parsear JSON automaticamente
    if (!res.ok) {
      // tenta ler texto para log (pode ser HTML de erro)
      let txt;
      try { txt = await res.text(); } catch(e) { txt = `<unable to read body: ${e}>`; }
      console.warn("⚠️ Validação retornou não-OK:", res.status, txt);
      if (res.status === 401) {
        console.warn(" Token inválido (401). Removendo token localmente.");
        localStorage.removeItem("token");
        sessionStorage.removeItem("token");
        // redireciona apenas se estiver numa rota protegida
        if (!["/", "/home"].includes(window.location.pathname)) window.location.href = "/";
      }
      // Para outros status (500, 404, etc) mantemos token e não redirecionamos
      return;
    }

    // se 2xx, verificar content-type antes de parsear
    const contentType = res.headers.get("content-type") || "";
    if (!contentType.includes("application/json")) {
      const txt = await res.text();
      console.warn("⚠️ /api/auth/me retornou não-JSON:", contentType, txt);
      // Evita exceção de JSON.parse tentando parsear HTML
      return;
    }

    const data = await res.json();
    console.log("📥 Resposta de /api/auth/me (JSON):", data);

    // validador simples de formato esperado
    if (!data || !data.success || !data.usuario) {
      console.warn("⚠️ Resposta de validação sem dados esperados. Mantendo token (pode ser temporário).");
      return;
    }

    const tipo = (data.usuario.tipo_usuario || data.usuario.tipo || "").toString().toLowerCase();
    console.log(" Token válido. Tipo de usuário:", tipo);

    // redirecionar para rota apropriada se necessário
    const path = window.location.pathname;
    console.log("📍 Página atual:", path);

    if (tipo === "gestor" && !path.startsWith("/gestor")) {
      console.log(" Redirecionando gestor para /gestor/documentacao");
      window.location.href = "/gestor/documentacao";
    } else if (tipo === "colaborador" && !path.startsWith("/colaborador")) {
      console.log(" Redirecionando colaborador para /colaborador/holerites");
      window.location.href = "/colaborador/holerites";
    } else {
      console.log(" Usuário na página correta. Sem redirecionamento.");
    }

  } catch (err) {
    console.error(" Erro ao validar token (rede/fetch):", err);
    console.warn("⚠️ Erro de conexão. Mantendo token para tentativas futuras.");
    // se quiser, aqui pode-se implementar tentativa de reconexão exponencial
  }
});
