import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { getUserByNametag } from "../../../../../services/api";
import './Perfil.css';

const Perfil = () => {
  const { userNametag } = useParams();
  console.log("🔍 Nametag recebido da rota:", userNametag);
  const navigate = useNavigate();
  const [usuario, setUsuario] = useState(null);
  const [carregando, setCarregando] = useState(true);
  const [erro, setErro] = useState("");
  const [user, setUser] = useState(null);

useEffect(() => {
  const fetchUser = async () => {
    try {
      console.log("Buscando perfil do usuário Nametag:", userNametag);
      const res = await getUserByNametag(userNametag);
      console.log("📦 Resposta bruta:", res);

      const data = res.data || res;
      const usuarioFinal = Array.isArray(data) ? data[0] : data;

      console.log("✅ Usuário processado:", usuarioFinal);
      setUsuario(usuarioFinal);
    } catch (err) {
      console.error("❌ Erro detalhado:", err);
      setErro("Erro ao carregar perfil do usuário.");
    } finally {
      setCarregando(false);
    }
  };

  if (userNametag) {
    fetchUser();
  } else {
    setErro("Nametag do usuário não fornecido.");
    setCarregando(false);
  }
}, [userNametag]);


  console.log("userNametag:", userNametag);
  console.log("usuario:", usuario);
  console.log("carregando:", carregando);


  if (carregando) return <div className="carregando">Carregando...</div>;
  if (erro) return <div className="erro">{erro}</div>;
  if (!usuario) return <div className="erro">Usuário não encontrado.</div>;

  return (
    <div className="container">
      <div className="perfil-container">
        {/* Botão Voltar */}
        <button
          className="voltar-btn"
          onClick={() => navigate(-1)}
        >
          ← Voltar
        </button>

        {/* Cabeçalho */}
        <div className="perfil-header">
          <h1>{usuario.Apelido || "Usuário"}</h1>
          <p>@{usuario.nametag || "usuário"}</p>
        </div>

        {/* Foto */}
        <div className="perfil-foto">
          <img
            src={usuario.Foto || "/nenhuma.png"}
            alt={`Foto de perfil de ${usuario.Apelido}`}
            onError={(e) => {
              e.target.src = "/nenhuma.png";
            }}
          />
        </div>

        {/* Bio */}
        <div className="perfil-section">
          <h2>Sobre mim</h2>
          <p>{usuario.bio || "Este usuário ainda não escreveu uma bio."}</p>
        </div>

        {/* Pronomes */}
        <div className="perfil-section">
          <h2>Pronomes</h2>
          <div className="pronomes-container">
            {Array.isArray(usuario.pronomes) && usuario.pronomes.length > 0 ? (
              usuario.pronomes.map((p) => (
                <span key={p} className="pronome-badge">{p}</span>
              ))
            ) : usuario.pronomes ? (
              <span className="pronome-badge">{usuario.pronomes}</span>
            ) : (
              <p className="input-hint">Nenhum pronome informado</p>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Perfil;