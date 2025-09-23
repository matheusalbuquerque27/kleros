-- Dados de exemplo para testar a view cursos/painel
-- Execute em um ambiente de desenvolvimento.

SET @now := NOW();
SET @congregacao_id := (SELECT id FROM congregacoes ORDER BY id ASC LIMIT 1);

INSERT INTO cursos (id, congregacao_id, titulo, descricao, ativo, publico, icone, created_at, updated_at)
VALUES
    (9901, @congregacao_id, 'Formação de Líderes', 'Trilha focada no desenvolvimento de líderes de ministérios e células.', 1, 0, NULL, @now, @now),
    (9902, NULL, 'Discipulado Essencial', 'Curso introdutório divulgado para toda a comunidade com foco em discipulado.', 1, 1, NULL, @now, @now),
    (9903, NULL, 'Acolhimento e Hospitalidade', 'Capacitação para equipes de recepção e acolhimento aos visitantes.', 1, 1, NULL, @now, @now)
ON DUPLICATE KEY UPDATE
    congregacao_id = VALUES(congregacao_id),
    titulo = VALUES(titulo),
    descricao = VALUES(descricao),
    ativo = VALUES(ativo),
    publico = VALUES(publico),
    icone = VALUES(icone),
    updated_at = VALUES(updated_at);

INSERT INTO modulos (id, curso_id, nome, descricao, ativo, publico, icone, cor, url, ordem, created_at, updated_at)
VALUES
    (9901, 9901, 'Fundamentos Bíblicos', 'Introdução bíblica ao papel da liderança cristã.', 1, 0, NULL, '#677b96', NULL, 1, @now, @now),
    (9902, 9901, 'Mentoria e Acompanhamento', 'Ferramentas práticas para discipular e mentorear novos líderes.', 1, 0, NULL, '#0a1929', 'https://example.com/mentoria', 2, @now, @now),
    (9903, 9902, 'Evangelho em Ação', 'Como compartilhar o evangelho nas rotinas diárias.', 1, 1, NULL, '#f44916', 'https://example.com/evangelho', 1, @now, @now),
    (9904, 9902, 'Dinâmicas para Grupos Pequenos', 'Atividades para consolidar os encontros de discipulado.', 1, 1, NULL, NULL, NULL, 2, @now, @now),
    (9905, 9903, 'Recepção com Excelência', 'Boas práticas para recepcionar visitantes em cultos e eventos.', 1, 1, NULL, '#1e88e5', 'https://example.com/acolhimento', 1, @now, @now)
ON DUPLICATE KEY UPDATE
    curso_id = VALUES(curso_id),
    nome = VALUES(nome),
    descricao = VALUES(descricao),
    ativo = VALUES(ativo),
    publico = VALUES(publico),
    icone = VALUES(icone),
    cor = VALUES(cor),
    url = VALUES(url),
    ordem = VALUES(ordem),
    updated_at = VALUES(updated_at);
