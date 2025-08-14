insert into bases_doutrinarias (nome) values 
('Batista'), ('Pentecostal'), ('Luterana'), ('Metodista'), ('Anglicana'), ('Congregacional'), ('Presbiteriana'), 
('Carismática'), ('Reformada'), ('Outra');

insert into tema (nome, propriedades) values 
('Clássico', { "borda": "4px", "cor-fundo": "#ffffff", "cor-texto": "#000000"}),
('Moderno', {"borda": "10px", "cor-fundo": "#3d3d3d", "cor-texto": "#ffffff"}),
('Vintage', {"borda": "30px", "cor-fundo": "#F5F5DC", "cor-texto": "#3e3e3e"});

insert into denominacoes (nome, base_doutrinaria, ativa, ministerios_eclesiasticos) values ('Assembleia de Deus Jerusalém', 2, true, '["Pastor", "Evangelista", "Diácono"]');
insert into congregacoes (denominacao_id, identificacao, ativa) values (1, 'Ilha Solteira', true);
insert into dominios (congregacao_id, dominio, ativo) values (1, 'adjerusalemilha.local', true);
insert into congregacao_configs (congregacao_id, logo_caminho, banner_caminho, conjunto_cores, font_family, tema_id) values 
(1, 'images/logo.png', 'images/banner.png', '{"primaria": "#9acbe7", "secundaria": "#1060a5", "terciaria": "#dcc43d", "texto": "#000000", "fundo":"#e9f8fd"}', 'Teko', null);

insert into denominacoes (nome, base_doutrinaria, ativa, ministerios_eclesiasticos) values ('Agape House', 2, true, '["Pastor", "Evangelista", "Diácono"]');
insert into congregacoes (denominacao_id, identificacao, ativa) values (2, 'Ilha Solteira', true);
insert into dominios (congregacao_id, dominio, ativo) values (2, 'agapehouseisa.local', true);
insert into congregacao_configs (congregacao_id, logo_caminho, banner_caminho, conjunto_cores, font_family, tema_id) values 
(2, 'images/logo_agape2.jpeg', 'images/banner_agape.jpg', '{"primaria": "#343A40", "secundaria": "#6C757D", "terciaria": "#DEE2E6", "texto":"#212529", "fundo": "#F8F9FA"}', 'Roboto', null);

insert into users (name, email, password, email_verified_at, denominacao_id, congregacao_id, membro_id) values 
('kleros.admin', 'admin@kleros.com', '$2y$12$vYj8Ljo3wkj9vXg.zePicejHiV6n9kOOib6clWt.gqrwddLrgdPka', null, null, null, null);


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

insert into situacao_visitantes (titulo) values ('Membro de outra denominação'), ('Não congrega no momento'),
('Não Evangélico'), ('Sem Religião')

insert into escolaridades (titulo) values 
('Não frequentou a escola'), ('Ensino Básico'), 
('Ensino Fundamental'), ('Ensino Médio'), 
('Ensino Técnico'), ('Ensino Superior'), 
('Pós-Graduação'), ('Mestrado'), 
('Doutorado');

insert into estado_civs (titulo) values 
('Solteiro(a)'), ('Casado(a)'), 
('Divorciado(a)'), ('Viúvo(a)'), 
('Separado(a)');


INSERT INTO visitantes (congregacao_id, nome, telefone, data_visita, sit_visitante_id) VALUES
(1, 'Carlos Silva', '(11) 90012-0001', '2025-07-10', 1),
(1, 'Fernanda Oliveira', '(11) 90012-0002', '2025-03-22', 2),
(1, 'Marcos Pereira', '(11) 90012-0003', '2025-05-05', 3),
(1, 'Juliana Costa', '(11) 90012-0004', '2025-01-28', 4),
(1, 'Rodrigo Almeida', '(11) 90012-0005', '2025-06-18', 1),
(1, 'Patrícia Martins', '(11) 90012-0006', '2025-02-11', 2),
(1, 'Gabriel Rocha', '(11) 90012-0007', '2025-05-22', 3),
(1, 'Aline Sousa', '(11) 90012-0008', '2025-04-14', 4),
(1, 'Thiago Lima', '(11) 90012-0009', '2025-03-02', 1),
(1, 'Renata Mendes', '(11) 90012-0010', '2025-07-03', 2),
(1, 'André Barbosa', '(11) 90012-0011', '2025-06-09', 3),
(1, 'Luciana Cardoso', '(11) 90012-0012', '2025-01-20', 4),
(1, 'Felipe Moraes', '(11) 90012-0013', '2025-04-27', 1),
(1, 'Camila Ribeiro', '(11) 90012-0014', '2025-06-29', 2),
(1, 'Bruno Fernandes', '(11) 90012-0015', '2025-03-18', 3),
(1, 'Larissa Carvalho', '(11) 90012-0016', '2025-02-08', 4),
(1, 'Diego Batista', '(11) 90012-0017', '2025-07-26', 1),
(1, 'Vanessa Teixeira', '(11) 90012-0018', '2025-04-03', 2),
(1, 'Gustavo Azevedo', '(11) 90012-0019', '2025-05-14', 3),
(1, 'Beatriz Correia', '(11) 90012-0020', '2025-01-31', 4),
(1, 'Leonardo Nogueira', '(11) 90012-0021', '2025-03-25', 1),
(1, 'Mariana Cunha', '(11) 90012-0022', '2025-06-15', 2),
(1, 'Ricardo Duarte', '(11) 90012-0023', '2025-02-24', 3),
(1, 'Paula Farias', '(11) 90012-0024', '2025-05-07', 4),
(1, 'Eduardo Tavares', '(11) 90012-0025', '2025-07-18', 1),
(1, 'Natália Araújo', '(11) 90012-0026', '2025-04-21', 2),
(1, 'Alexandre Freitas', '(11) 90012-0027', '2025-03-08', 3),
(1, 'Sabrina Macedo', '(11) 90012-0028', '2025-01-29', 4),
(1, 'Rafael Moreira', '(11) 90012-0029', '2025-06-23', 1),
(1, 'Tatiane Castro', '(11) 90012-0030', '2025-05-11', 2),
(1, 'Marcelo Pires', '(11) 90012-0031', '2025-02-16', 3),
(1, 'Cláudia Rezende', '(11) 90012-0032', '2025-03-29', 4),
(1, 'Pedro Monteiro', '(11) 90012-0033', '2025-07-06', 1),
(1, 'Simone Xavier', '(11) 90012-0034', '2025-06-01', 2),
(1, 'Maurício Peixoto', '(11) 90012-0035', '2025-05-19', 3),
(1, 'Elaine Santana', '(11) 90012-0036', '2025-04-06', 4),
(1, 'Otávio Campos', '(11) 90012-0037', '2025-02-03', 1),
(1, 'Isabela Barros', '(11) 90012-0038', '2025-06-27', 2),
(1, 'Fábio Porto', '(11) 90012-0039', '2025-03-13', 3),
(1, 'Patrícia Viana', '(11) 90012-0040', '2025-05-25', 4),
(1, 'Daniel Brito', '(11) 90012-0041', '2025-04-10', 1),
(1, 'Monique Queiroz', '(11) 90012-0042', '2025-01-26', 2),
(1, 'Henrique Sales', '(11) 90012-0043', '2025-07-15', 3),
(1, 'Priscila Paiva', '(11) 90012-0044', '2025-06-05', 4),
(1, 'Caio Matos', '(11) 90012-0045', '2025-02-21', 1),
(1, 'Letícia Antunes', '(11) 90012-0046', '2025-05-02', 2),
(1, 'Mateus Galvão', '(11) 90012-0047', '2025-03-16', 3),
(1, 'Sueli Bastos', '(11) 90012-0048', '2025-04-25', 4),
(1, 'Igor Assis', '(11) 90012-0049', '2025-06-12', 1),
(1, 'Viviane Prado', '(11) 90012-0050', '2025-01-23', 2);




insert into eventos (congregacao_id, nome, descricao, data_inicio, data_fim, ativo) values 
(1, 'Culto de Oração', 'Culto semanal de oração e intercessão', '2025-09-01 19:00:00', '2025-09-01 21:00:00', true),
(1, 'Estudo Bíblico', 'Estudo bíblico sobre o livro de Gênesis', '2025-09-02 19:00:00', '2025-09-02 21:00:00', true),
(1, 'Encontro de Casais', 'Encontro especial para casais da congregação', '2025-09-03 18:00:00', '2025-09-03 22:00:00', true),
(1, 'Crianças em Ação', 'Atividades para crianças da congregação', '2023-09-04 15:00:00', '2025-09-04 17:00:00', true),
(1, 'Confraternização Anual', 'Confraternização anual da congregação com todos os membros e visitantes', '2025-09-15 12:00:00', '2025-09-15 18:00:00', true);