insert into bases_doutrinarias (nome) values 
('Batista'), ('Pentecostal'), ('Luterana'), ('Metodista'), ('Anglicana'), ('Congregacional'), ('Presbiteriana'), 
('Carismática'), ('Reformada'), ('Outra');

insert into denominacoes (nome, base_doutrinaria, ativa, ministerios_eclesiasticos) values ('Assembleia de Deus Jerusalém', 2, true, '["Pastor", "Evangelista", "Diácono"]');
insert into congregacoes (denominacao_id, identificacao, ativa) values (1, 'Ilha Solteira', true);
insert into dominios (congregacao_id, dominio, ativo) values (1, 'adjerusalemilha.local', true);
insert into congregacao_configs (congregacao_id, logo_caminho, banner_caminho, conjunto_cores, font_family, tema_id) values 
(1, 'images/logo.png', 'images/banner.png', '{"primaria": "#3490dc", "secundaria": "#ffed4a", "terciaria": "#e3342f"}', 'Arial, sans-serif', null);

insert into denominacoes (nome, base_doutrinaria, ativa, ministerios_eclesiasticos) values ('Agape House', 2, true, '["Pastor", "Evangelista", "Diácono"]');
insert into congregacoes (denominacao_id, identificacao, ativa) values (2, 'Ilha Solteira', true);
insert into dominios (congregacao_id, dominio, ativo) values (2, 'agapehouseisa.local', true);
insert into congregacao_configs (congregacao_id, logo_caminho, banner_caminho, conjunto_cores, font_family, tema_id) values 
(2, 'images/logo_agape2.jpeg', 'images/banner_agape.jpg', '{"primaria": "#3490dc", "secundaria": "#ffed4a", "terciaria": "#e3342f"}', 'Arial, sans-serif', null);

insert into users (name, email, password, email_verified_at, denominacao_id, congregacao_id, membro_id) values 
('kleros.admin', 'admin@kleros.com', null, '$2y$12$vYj8Ljo3wkj9vXg.zePicejHiV6n9kOOib6clWt.gqrwddLrgdPka', null, null, null);

insert into ministerios (nome) values 
('Pastor(a)'),
('Evangelista'),
('Diácono(isa)'),
('Presbítero'),
('Missionário(a)');

insert into setores (congregacao_id, nome, descricao) values 
(1, 'Administrativo', 'Descrição do Setor Administrativo'),
(1, 'Organizacional', 'Descrição do Setor Organizacional'),
(1, 'Estudos', 'Descrição do Setor de Estudos');

insert into departamentos (congregacao_id, nome, descricao) values 
(1, 'Limpeza', 'Atividades voltadas para a limpeza da congregação'),
(1, 'Comunicação', 'Atividades voltadas para a comunicação da congregação'),
(1, 'Social', 'Atividades voltadas para a assistência social da congregação'),
(1, 'Infantil', 'Atividades voltadas para as crianças da congregação');

insert into membros (congregacao_id, nome, data_nascimento, telefone, ativo) values (1, 'Joana da Silva', '1990-01-01', '123456789', true);
insert into grupos (congregacao_id, nome, descricao, membro_id) values (1, 'Shekinah', 'Grupo de Mulheres', 1);