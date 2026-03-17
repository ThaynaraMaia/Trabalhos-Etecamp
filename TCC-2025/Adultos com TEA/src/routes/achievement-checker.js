// achievement-checker.js
// Adicione este script em public/js/achievement-checker.js

class AchievementChecker {
  constructor() {
    this.isChecking = false;
    this.notificationQueue = [];
  }

  // Verificar conquistas
  async check() {
    if (this.isChecking) return;
    
    this.isChecking = true;
    
    try {
      const response = await fetch('/conquistas/verificar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error('Erro ao verificar conquistas');
      }

      const data = await response.json();

      if (data.success && data.novasConquistas && data.novasConquistas.length > 0) {
        // Adicionar notificações à fila
        data.novasConquistas.forEach(conquista => {
          this.notificationQueue.push(conquista);
        });

        // Mostrar notificações
        this.showNextNotification();
      }
    } catch (err) {
      console.error('Erro ao verificar conquistas:', err);
    } finally {
      this.isChecking = false;
    }
  }

  // Mostrar próxima notificação da fila
  showNextNotification() {
    if (this.notificationQueue.length === 0) return;

    const conquista = this.notificationQueue.shift();
    this.showNotification(conquista);

    // Agendar próxima notificação
    if (this.notificationQueue.length > 0) {
      setTimeout(() => this.showNextNotification(), 5000);
    }
  }

  // Mostrar notificação individual
  showNotification(conquista) {
    // Remover notificações existentes
    const existing = document.querySelector('.achievement-notification');
    if (existing) {
      existing.remove();
    }

    const notification = document.createElement('div');
    notification.className = 'achievement-notification';
    notification.innerHTML = `
      <div class="achievement-notification-content">
        <div class="achievement-notification-icon">${conquista.icone}</div>
        <div class="achievement-notification-text">
          <div class="achievement-notification-title">🎉 Conquista Desbloqueada!</div>
          <div class="achievement-notification-subtitle">${conquista.nome}</div>
          <div class="achievement-notification-xp">+${conquista.xp} XP</div>
        </div>
      </div>
      <button class="achievement-notification-close" onclick="this.parentElement.remove()">×</button>
    `;

    document.body.appendChild(notification);

    // Animar entrada
    setTimeout(() => {
      notification.classList.add('show');
    }, 100);

    // Tocar som (opcional)
    this.playSound();

    // Auto-remover após 4 segundos
    setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => {
        if (notification.parentElement) {
          notification.remove();
        }
      }, 500);
    }, 4000);
  }

  // Tocar som de conquista (opcional)
  playSound() {
    try {
      const audio = new Audio('/sounds/achievement.mp3');
      audio.volume = 0.3;
      audio.play().catch(() => {
        // Ignorar erro se não conseguir tocar
      });
    } catch (err) {
      // Som é opcional
    }
  }
}

// Criar instância global
window.achievementChecker = new AchievementChecker();

// Verificar conquistas automaticamente em certas ações
document.addEventListener('DOMContentLoaded', () => {
  // Interceptar formulários de ação
  const forms = document.querySelectorAll('form[data-check-achievements]');
  forms.forEach(form => {
    form.addEventListener('submit', () => {
      setTimeout(() => {
        window.achievementChecker.check();
      }, 1000);
    });
  });

  // Verificar ao carregar página (para conquistas de login)
  if (window.location.pathname === '/') {
    setTimeout(() => {
      window.achievementChecker.check();
    }, 1500);
  }
});

// CSS para as notificações
const style = document.createElement('style');
style.textContent = `
  .achievement-notification {
    position: fixed;
    top: 90px;
    right: -450px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
    z-index: 10001;
    min-width: 350px;
    max-width: 400px;
    transition: right 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    border: 2px solid rgba(255, 255, 255, 0.2);
  }

  .achievement-notification.show {
    right: 30px;
  }

  .achievement-notification-content {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .achievement-notification-icon {
    font-size: 3rem;
    flex-shrink: 0;
    animation: bounceIcon 0.6s ease-out;
  }

  @keyframes bounceIcon {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
  }

  .achievement-notification-text {
    flex: 1;
  }

  .achievement-notification-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 5px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  .achievement-notification-subtitle {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 3px;
  }

  .achievement-notification-xp {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
  }

  .achievement-notification-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.5rem;
    line-height: 1;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .achievement-notification-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
  }

  @media (max-width: 768px) {
    .achievement-notification {
      right: auto;
      left: -100%;
      min-width: auto;
      max-width: calc(100% - 40px);
      margin: 0 20px;
    }

    .achievement-notification.show {
      left: 20px;
      right: auto;
    }
  }
`;
document.head.appendChild(style);