
// COMENTARIOS

document.addEventListener("DOMContentLoaded", () => {
    const notificarBtns = document.querySelectorAll(".btn-notificar");

    notificarBtns.forEach(btn => {
        btn.addEventListener("click", async () => {
            const comentarioId = btn.getAttribute("data-id1");

            // Evita cliques duplos
            btn.disabled = true;
            btn.textContent = "Enviando...";

            try {
                const res = await fetch("notificarComent.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "id_comentario=" + encodeURIComponent(comentarioId)
                });

                if (!res.ok) {
                    throw new Error(`Erro de rede: ${res.status} ${res.statusText}`);
                }

                const data = await res.json();

                if (data.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Sucesso!",
                        text: data.message
                    });

                    // Habilita o botão de excluir, se existir
                    const excluirLink = document.querySelector(`a.btn-excluir[data-id1='${comentarioId}']`);
                    if (excluirLink) {
                        excluirLink.classList.remove("disabled");
                    }

                    btn.textContent = "Notificado ✔️";

                } else if (data.status === "info") {
                    Swal.fire({
                        icon: "info",
                        title: "Aviso",
                        text: data.message
                    });

                    const excluirLink = document.querySelector(`a.btn-excluir[data-id1='${comentarioId}']`);
                    if (excluirLink) {
                        excluirLink.classList.remove("disabled");
                    }

                    btn.textContent = "Já Notificado";

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erro",
                        text: data.message
                    });

                    btn.disabled = false;
                    btn.textContent = "Notificar";
                }

            } catch (err) {
                console.error("Erro na requisição:", err);
                Swal.fire({
                    icon: "error",
                    title: "Erro de Conexão",
                    text: "Não foi possível se comunicar com o servidor."
                });
                btn.disabled = false;
                btn.textContent = "Notificar";
            }
        });
    });
});