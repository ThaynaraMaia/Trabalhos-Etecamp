-- scripts/create_analytics_tables.sql
-- Tabelas analíticas para a página de análise / relatórios
-- Execute via mysql client ou via Node (script provido)

/*
Tables:
- report_definitions: metadados e configuração do tipo de relatório (templates)
- report_runs: histórico de execuções (parâmetros, status, links para arquivo exportado)
- report_run_metrics: métricas calculadas (agregações por run) - chave para visualizações rápidas
- timeseries_points: pontos temporais para séries (para gráficos)
- metric_aggregates: cache de agregações para consultas rápidas (pre-aggregated)
- report_exports: histórico de arquivos exportados
- saved_filters: filtros salvos por usuário para reuso
- alerts: regras e notificações relacionadas a relatórios/thresholds
*/

CREATE TABLE IF NOT EXISTS report_definitions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) NOT NULL UNIQUE,         -- ex: folha_pagamento, custo_pessoal
  title VARCHAR(180) NOT NULL,
  description TEXT,
  category VARCHAR(60) NOT NULL,             -- financeiro, jornada, gestao, documentos...
  default_parameters JSON DEFAULT NULL,      -- parâmetros padrão/estrutura do formulário (JSON)
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS report_runs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  report_definition_id BIGINT UNSIGNED NULL, -- não colocamos FK por compatibilidade; index apenas
  run_key VARCHAR(64) NULL,                  -- identificador público (UUID etc)
  params JSON NULL,                          -- parâmetros preenchidos pelo usuário (JSON)
  requested_by VARCHAR(120) NULL,            -- usuário que solicitou (login/email/id)
  status ENUM('pending','running','failed','completed','cancelled') DEFAULT 'pending',
  result_rows INT DEFAULT NULL,              -- número de linhas retornadas (opcional)
  error_message TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  started_at TIMESTAMP NULL,
  finished_at TIMESTAMP NULL,
  -- arquivo resultante (se gerado/exportado)
  exported_file_path VARCHAR(512) DEFAULT NULL,
  exported_format VARCHAR(20) DEFAULT NULL,
  INDEX(idx_report_def_id) (report_definition_id),
  INDEX(idx_run_key) (run_key),
  INDEX(idx_status) (status),
  INDEX(idx_requested_by) (requested_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS report_run_metrics (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  report_run_id BIGINT UNSIGNED NOT NULL,
  metric_key VARCHAR(120) NOT NULL,      -- ex: total_salary, avg_hours, turnover_rate
  metric_label VARCHAR(250) NULL,
  metric_value DOUBLE NULL,
  metric_meta JSON NULL,                 -- additional info e.g. {unit:'BRL', pct:0.12}
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(idx_report_run_id) (report_run_id),
  INDEX(idx_metric_key) (metric_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS timeseries_points (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  report_run_id BIGINT UNSIGNED NULL,     -- opcional (ligado a um run)
  metric_key VARCHAR(120) NOT NULL,
  point_time DATETIME NOT NULL,
  value DOUBLE NOT NULL,
  meta JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(idx_metric_key_time) (metric_key, point_time),
  INDEX(idx_report_run_id) (report_run_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS metric_aggregates (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  metric_key VARCHAR(120) NOT NULL,
  agrupamento VARCHAR(120) NULL,         -- ex: centro_custo|funcionario|cargo
  period_start DATE NULL,
  period_end DATE NULL,
  value DOUBLE NULL,
  units VARCHAR(32) NULL,
  source VARCHAR(120) NULL,              -- origem da agregação (job name)
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(idx_metric_key_period) (metric_key, period_start, period_end),
  INDEX(idx_agrupamento) (agrupamento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS report_exports (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  report_run_id BIGINT UNSIGNED NULL,
  file_path VARCHAR(512) NOT NULL,
  file_format VARCHAR(20) NOT NULL,
  generated_by VARCHAR(120) NULL,
  file_size_bytes BIGINT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(idx_report_run_id) (report_run_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS saved_filters (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  owner VARCHAR(120) NULL,       -- usuário dono do filtro
  scope ENUM('user','team','global') DEFAULT 'user',
  definition JSON NOT NULL,      -- objeto JSON com campos do filtro
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX(idx_owner) (owner)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS alerts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  metric_key VARCHAR(120) NULL,
  condition_json JSON NULL,      -- ex: {"op":">","threshold":1000,"window_days":30}
  target VARCHAR(180) NULL,      -- pessoa/email/endpoint
  active TINYINT(1) DEFAULT 1,
  last_triggered TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(idx_metric_key) (metric_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: small housekeeping table to keep ETL job marks
CREATE TABLE IF NOT EXISTS analytics_jobs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_name VARCHAR(160) NOT NULL UNIQUE,
  last_run_at TIMESTAMP NULL,
  status ENUM('idle','running','error') DEFAULT 'idle',
  last_message TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
