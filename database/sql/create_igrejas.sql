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



INSERT INTO membros (
    congregacao_id, nome, rg, cpf, data_nascimento, sexo, telefone, email,
    estado_civ_id, escolaridade_id, profissao, endereco, numero, complemento,
    bairro, cep, data_batismo, denominacao_origem, ministerio_id, data_consagracao
) VALUES
(1, 'Rafael Silva', '1234567', '123.456.789-00', '1985-03-12', 'Masculino', '(11)98888-1111', 'rafael.silva@email.com', 1, 2, 'Engenheiro', 'Rua das Flores', '101', NULL, 'Centro', '01001-000', '2005-05-20', 'Batista', 1, '2015-08-10'),
(1, 'Aline Souza', '2345678', '234.567.890-11', '1990-07-25', 'Feminino', '(11)97777-2222', 'aline.souza@email.com', 2, 3, 'Arquiteta', 'Av. Paulista', '202', 'Apto 12', 'Bela Vista', '01310-100', '2010-09-15', 'Presbiteriana', 2, '2018-04-22'),
(1, 'Carlos Pereira', '3456789', '345.678.901-22', '1978-11-03', 'Masculino', '(21)96666-3333', 'carlos.pereira@email.com', 1, 1, 'Professor', 'Rua do Sol', '15', NULL, 'Copacabana', '22070-001', '2000-02-10', 'Católica', 3, NULL),
(1, 'Fernanda Lima', '4567890', '456.789.012-33', '1982-01-18', 'Feminino', '(21)95555-4444', 'fernanda.lima@email.com', 3, 2, 'Advogada', 'Rua das Palmeiras', '56', 'Bloco B', 'Botafogo', '22250-040', '2002-07-12', 'Metodista', NULL, NULL),
(1, 'Marcos Oliveira', '5678901', '567.890.123-44', '1995-05-09', 'Masculino', '(31)94444-5555', 'marcos.oliveira@email.com', 1, 3, 'Médico', 'Av. Afonso Pena', '300', NULL, 'Centro', '30130-001', '2015-03-21', 'Assembleia de Deus', 1, '2020-06-01'),
(1, 'Juliana Costa', '6789012', '678.901.234-55', '1988-10-22', 'Feminino', '(31)93333-6666', 'juliana.costa@email.com', 2, 2, 'Psicóloga', 'Rua da Liberdade', '45', 'Casa 2', 'Savassi', '30140-120', '2009-11-15', NULL, NULL, NULL),
(1, 'Pedro Santos', '7890123', '789.012.345-66', '1992-06-14', 'Masculino', '(41)92222-7777', NULL, 1, 2, 'Analista de Sistemas', 'Rua XV de Novembro', '500', NULL, 'Centro', '80020-310', '2013-07-10', 'Batista', 2, NULL),
(1, 'Beatriz Almeida', '8901234', '890.123.456-77', '1999-12-30', 'Feminino', '(41)91111-8888', 'bea.almeida@email.com', 2, 1, 'Estudante', 'Av. Curitiba', '99', NULL, 'Juvevê', '80030-200', NULL, NULL, NULL, NULL),
(1, 'Ricardo Gomes', '9012345', '901.234.567-88', '1975-02-27', 'Masculino', '(61)98888-9999', 'ricardo.gomes@email.com', 1, 2, 'Administrador', 'SQN 210', 'Bloco H', 'Apto 404', 'Asa Norte', '70862-000', '1998-09-19', 'Presbiteriana', 1, '2010-11-20'),
(1, 'Patrícia Mendes', '0123456', '012.345.678-99', '1987-08-05', 'Feminino', '(61)97777-0000', NULL, 2, 3, 'Enfermeira', 'SHCGN 703', 'Bloco D', NULL, 'Asa Norte', '70730-000', '2008-12-01', 'Católica', NULL, NULL),
-- adicionando mais 20 fictícios
(1, 'André Rocha', '1111111', '111.222.333-44', '1983-04-11', 'Masculino', '(71)91111-2222', 'andre.rocha@email.com', 1, 1, 'Empresário', 'Rua Bahia', '10', NULL, 'Barra', '40140-120', '2004-07-18', 'Luterana', NULL, NULL),
(1, 'Tatiane Freitas', '2222222', '222.333.444-55', '1991-09-19', 'Feminino', '(71)92222-3333', 'tatiane.freitas@email.com', 2, 2, 'Designer', 'Av. Sete de Setembro', '220', 'Sala 5', 'Comércio', '40060-001', '2011-10-22', 'Batista', 2, '2019-03-10'),
(1, 'Luiz Henrique', '3333333', '333.444.555-66', '1980-12-01', 'Masculino', '(85)93333-4444', NULL, 1, 3, 'Contador', 'Rua Fortaleza', '77', NULL, 'Aldeota', '60125-000', '2000-01-15', 'Metodista', NULL, NULL),
(1, 'Carla Martins', '4444444', '444.555.666-77', '1994-07-07', 'Feminino', '(85)94444-5555', 'carla.martins@email.com', 3, 1, 'Estudante', 'Av. Beira Mar', '555', 'Apto 803', 'Meireles', '60165-120', NULL, NULL, 3, NULL),
(1, 'Diego Fernandes', '5555555', '555.666.777-88', '1989-11-15', 'Masculino', '(31)95555-6666', 'diego.fernandes@email.com', 2, 2, 'Policial', 'Rua Ouro Preto', '90', NULL, 'Barro Preto', '30180-001', '2009-08-25', NULL, NULL, NULL),
(1, 'Amanda Ribeiro', '6666666', '666.777.888-99', '1996-02-02', 'Feminino', '(31)96666-7777', 'amanda.ribeiro@email.com', 1, 2, 'Publicitária', 'Rua Tupis', '123', 'Loja 4', 'Centro', '30190-050', '2016-05-10', 'Batista', NULL, NULL),
(1, 'João Paulo', '7777777', '777.888.999-00', '1977-03-23', 'Masculino', '(21)97777-8888', 'joao.paulo@email.com', 1, 2, 'Motorista', 'Rua Niterói', '333', NULL, 'Centro', '24020-000', '1995-11-05', 'Assembleia de Deus', 1, '2005-06-14'),
(1, 'Luciana Prado', '8888888', '888.999.000-11', '1984-06-16', 'Feminino', '(21)98888-9990', NULL, 2, 3, 'Jornalista', 'Av. Brasil', '455', NULL, 'Centro', '20040-002', '2006-07-01', 'Metodista', 2, NULL),
(1, 'Gustavo Carvalho', '9999999', '999.000.111-22', '1993-08-29', 'Masculino', '(27)99999-0001', 'gustavo.carvalho@email.com', 1, 1, 'Técnico em TI', 'Rua Vitória', '12', NULL, 'Centro', '29010-020', '2014-12-20', 'Católica', NULL, NULL),
(1, 'Renata Castro', '1010101', '101.111.222-33', '1986-05-04', 'Feminino', '(27)91010-2020', NULL, 2, 2, 'Enfermeira', 'Av. Marechal Mascarenhas', '99', 'Apto 301', 'Praia do Canto', '29055-260', '2007-08-18', 'Batista', 3, NULL),
(1, 'Felipe Duarte', '1212121', '121.212.121-44', '1997-01-12', 'Masculino', '(11)91212-1212', 'felipe.duarte@email.com', 1, 3, 'Estagiário', 'Rua das Laranjeiras', '80', NULL, 'Moema', '04010-050', '2017-03-03', 'Católica', NULL, NULL),
(1, 'Marta Nunes', '1313131', '131.313.131-55', '1979-09-30', 'Feminino', '(11)91313-1313', NULL, 3, 2, 'Cabeleireira', 'Rua Augusta', '200', 'Loja 7', 'Consolação', '01305-000', '1998-12-15', 'Assembleia de Deus', 2, '2008-02-19'),
(1, 'Sérgio Moraes', '1414141', '141.414.141-66', '1981-12-24', 'Masculino', '(31)91414-1414', 'sergio.moraes@email.com', 1, 1, 'Pedreiro', 'Rua Contagem', '75', NULL, 'Santa Tereza', '31015-000', '2001-04-21', NULL, NULL, NULL),
(1, 'Eliane Barbosa', '1515151', '151.515.151-77', '1990-03-08', 'Feminino', '(31)91515-1515', NULL, 2, 2, 'Dentista', 'Rua Ouro', '10', NULL, 'Floresta', '31015-200', '2010-06-11', 'Presbiteriana', 1, '2019-09-15'),
(1, 'Thiago Moreira', '1616161', '161.616.161-88', '1995-11-27', 'Masculino', '(41)91616-1616', 'thiago.moreira@email.com', 1, 3, 'Consultor', 'Rua Marechal', '300', 'Sala 8', 'Centro', '80020-100', '2014-01-22', 'Batista', NULL, NULL),
(1, 'Camila Pires', '1717171', '171.717.171-99', '1989-07-15', 'Feminino', '(41)91717-1717', 'camila.pires@email.com', 2, 1, 'Fotógrafa', 'Rua Curitiba', '12', NULL, 'Centro', '80010-150', '2008-11-10', 'Metodista', 3, NULL),
(1, 'Rodrigo Faria', '1818181', '181.818.181-00', '1982-02-02', 'Masculino', '(51)91818-1818', NULL, 1, 2, 'Vendedor', 'Av. Ipiranga', '999', NULL, 'Centro', '90040-000', '2003-05-30', 'Católica', NULL, NULL),
(1, 'Priscila Teixeira', '1919191', '191.919.191-11', '1991-09-09', 'Feminino', '(51)91919-1919', 'priscila.teixeira@email.com', 2, 3, 'Enfermeira', 'Rua da Praia', '250', NULL, 'Centro', '90020-000', '2012-07-07', 'Batista', 1, NULL),
(1, 'Eduardo Batista', '2020202', '202.020.202-22', '1987-06-19', 'Masculino', '(61)92020-2020', NULL, 1, 2, 'Servidor Público', 'SQS 308', 'Bloco G', 'Apto 101', 'Asa Sul', '70362-000', '2007-09-23', 'Presbiteriana', NULL, NULL);