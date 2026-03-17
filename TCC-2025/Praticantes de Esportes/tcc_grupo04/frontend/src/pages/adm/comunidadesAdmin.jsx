import React, { useState, useEffect } from "react";
import api from "../../api/api"; // Certifique-se de que o caminho está correto
import { useAuth } from "../../contexts/AuthProvider"; // Certifique-se de que o caminho está correto
import UserLayout from "../../componentes/layout/userlayout"; // Certifique-se de que o caminho está correto

const ComunidadesAdmin = () => {
    const { usuario } = useAuth(); // Puxa o usuário logado para verificar a role
    
    const [comunidades, setComunidades] = useState([]);
    const [mensagem, setMensagem] = useState("");
    const [loading, setLoading] = useState(false);
    
    // Estado para a confirmação de exclusão
    const [showDeleteConfirmation, setShowDeleteConfirmation] = useState(false);
    const [communityToDelete, setCommunityToDelete] = useState(null);


    const API_URL = 'http://localhost:4000/api'; // Assumindo a mesma URL da sua API
    const SERVER_BASE_URL = API_URL.replace('/api', '');

    const getImageUrl = (path, size = 50) => {
    if (!path || path.startsWith('http') || path.startsWith('blob:')) {
        return path || `https://via.placeholder.com/${size}/CCCCCC/808080?text=NP`;
    }
    return `${SERVER_BASE_URL}${path}`;
}

    // Função para buscar a lista de comunidades
    const fetchCommunities = async () => {
        setLoading(true);
        try {
            const res = await api.get("/chats/all-admin");
            // O array retornado tem os detalhes de meetup já formatados
            setComunidades(res.data); 
            setMensagem("");

        } catch (err) {
            console.error("Erro ao carregar comunidades:", err.response || err);
            if (err.response && err.response.status === 403) { // Acesso Negado
                setMensagem("Acesso negado. Apenas administradores podem gerenciar comunidades.");
            } else {
                setMensagem("Erro ao carregar a lista de comunidades.");
            }
        } finally {
            setLoading(false);
        }
    };

    // Efeito para buscar os dados ao carregar a página
    useEffect(() => {
        // Verifica se o usuário é admin antes de tentar buscar os dados
        if (usuario && usuario.tipo === 'admin') {
            fetchCommunities();
        } else if (usuario) {
            setMensagem("Acesso negado. Você não tem permissão para esta página.");
        }
        // Dependência `usuario` para re-executar se o estado de autenticação mudar
    }, [usuario]);

    // Manipulador de clique no botão de exclusão
    const handleDeleteClick = (community) => {
        setCommunityToDelete(community);
        setShowDeleteConfirmation(true);
    };

    // Confirmação e chamada à API DELETE
    const confirmDelete = async () => {
        if (!communityToDelete) return;
        
        setLoading(true);
        try {
            // Rota DELETE /api/chats/:chatId que verifica a role 'admin'
            await api.delete(`/chats/${communityToDelete._id}`);

            setMensagem(`Comunidade "${communityToDelete.name}" excluída com sucesso!`);
            
            // Remove a comunidade da lista local
            setComunidades(comunidades.filter(c => c._id !== communityToDelete._id));

        } catch (err) {
            console.error("Erro ao deletar comunidade:", err.response || err);
            setMensagem(err.response?.data?.msg || "Erro ao excluir comunidade. Verifique as permissões.");
        } finally {
            setShowDeleteConfirmation(false);
            setCommunityToDelete(null);
            setLoading(false);
        }
    };

    if (!usuario || usuario.tipo !== 'admin') {
        return <p className="text-center mt-4">{mensagem || "Acesso negado. Você precisa ser administrador."}</p>;
    }

    if (loading) {
        return <p className="text-center mt-4">Carregando comunidades...</p>;
    }

    return (
        <UserLayout>
            <div className="container mt-5">
                <h1 className="text-center mb-4">Gerenciamento de Comunidades</h1>
                {mensagem && <div className="alert alert-info">{mensagem}</div>}
                
                <ul className="list-group">
                    {comunidades.length > 0 ? (
                        comunidades.map((community) => (
                            <li key={community._id} className="list-group-item d-flex justify-content-between align-items-center">
                                
                                <div className="d-flex align-items-center">
                                    <img 
                                        // 🚨 CORREÇÃO: Usa getImageUrl
                                        src={getImageUrl(community.groupImage, 50)} 
                                        alt="Imagem da Comunidade" 
                                        style={{ width: '50px', height: '50px', borderRadius: '50%', marginRight: '15px', objectFit: 'cover' }} 
                                        // Adiciona tratamento de erro
                                        onError={(e) => {
                                            e.target.onerror = null; 
                                            e.target.src = getImageUrl(null, 50); // Fallback
                                        }} 
                                    />
                                    <div>
                                        {/* Exibe o nome e o tipo de esporte */}
                                        <h5 className="mb-1">{community.name}</h5>
                                        <small>Esporte: {community.sportType} | Membros: {community.numMembers}</small>
                                    </div>
                                </div>
                                
                                <div>
                                    <button
                                        className="btn btn-danger btn-sm"
                                        onClick={() => handleDeleteClick(community)}
                                        disabled={loading}
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </li>
                        ))
                    ) : (
                        <p className="text-center mt-4">Nenhuma comunidade aberta encontrada.</p>
                    )}
                </ul>

                {/* Modal de Confirmação de Exclusão */}
                {showDeleteConfirmation && (
                    <div className="modal-backdrop fade show"></div>
                )}
                {showDeleteConfirmation && (
                    <div className="modal d-block" tabIndex="-1">
                        <div className="modal-dialog">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title">Confirmar Exclusão</h5>
                                    <button type="button" className="btn-close" onClick={() => setShowDeleteConfirmation(false)}></button>
                                </div>
                                <div className="modal-body">
                                    <p>Tem certeza de que deseja excluir permanentemente a comunidade <strong>{communityToDelete?.name}</strong>?</p>
                                    <p className="text-danger">Esta ação não pode ser desfeita.</p>
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" onClick={() => setShowDeleteConfirmation(false)}>
                                        Cancelar
                                    </button>
                                    <button type="button" className="btn btn-danger" onClick={confirmDelete} disabled={loading}>
                                        {loading ? 'Excluindo...' : 'Excluir'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </UserLayout>
    );
};

export default ComunidadesAdmin;