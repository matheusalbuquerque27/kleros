-- Semeadura das extensões disponíveis
-- Execute em ambiente de desenvolvimento

INSERT INTO extensoes (congregacao_id, module, enabled, options, created_at, updated_at)
VALUES
    (1, 'biblia', 1, NULL, NOW(), NOW()),
    (1, 'recados', 1, NULL, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    enabled = VALUES(enabled),
    options = VALUES(options),
    updated_at = VALUES(updated_at);
