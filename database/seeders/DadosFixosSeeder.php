<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DadosFixosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bases doutrinárias
        DB::table('bases_doutrinarias')->insert([
            ['nome' => 'Batista'],
            ['nome' => 'Pentecostal'],
            ['nome' => 'Luterana'],
            ['nome' => 'Metodista'],
            ['nome' => 'Anglicana'],
            ['nome' => 'Congregacional'],
            ['nome' => 'Presbiteriana'],
            ['nome' => 'Carismática'],
            ['nome' => 'Reformada'],
            ['nome' => 'Outra'],
        ]);

        // Temas
        DB::table('temas')->insert([
            ['nome' => 'Clássico', 'propriedades' => json_encode(["borda" => "4px", "cor-fundo" => "#ffffff", "cor-texto" => "#000000"])],
            ['nome' => 'Moderno',  'propriedades' => json_encode(["borda" => "10px", "cor-fundo" => "#3d3d3d", "cor-texto" => "#ffffff"])],
            ['nome' => 'Vintage',  'propriedades' => json_encode(["borda" => "30px", "cor-fundo" => "#F5F5DC", "cor-texto" => "#3e3e3e"])],
        ]);

        // Denominações
        DB::table('denominacoes')->insert([
            ['id' => 1, 'nome' => 'Assembleia de Deus Jerusalém', 'base_doutrinaria' => 2, 'ativa' => true, 'ministerios_eclesiasticos' => json_encode(["Pastor", "Evangelista", "Diácono"])],
            ['id' => 2, 'nome' => 'Agape House', 'base_doutrinaria' => 2, 'ativa' => true, 'ministerios_eclesiasticos' => json_encode(["Pastor", "Evangelista", "Diácono"])],
        ]);

        // Congregações
        DB::table('congregacoes')->insert([
            ['id' => 1, 'denominacao_id' => 1, 'identificacao' => 'Ilha Solteira', 'ativa' => true],
            ['id' => 2, 'denominacao_id' => 2, 'identificacao' => 'Ilha Solteira', 'ativa' => true],
        ]);

        // Domínios
        DB::table('dominios')->insert([
            ['congregacao_id' => 1, 'dominio' => 'adjerusalemilha.local', 'ativo' => true],
            ['congregacao_id' => 2, 'dominio' => 'agapehouseisa.local', 'ativo' => true],
        ]);

        // Configurações das congregações
        DB::table('congregacao_configs')->insert([
            [
                'congregacao_id' => 1,
                'logo_caminho' => 'images/logo.png',
                'banner_caminho' => 'images/banner.png',
                'conjunto_cores' => json_encode([
                    "primaria" => "#9acbe7",
                    "secundaria" => "#1060a5",
                    "terciaria" => "#dcc43d",
                    "texto" => "#000000",
                    "fundo" => "#e9f8fd"
                ]),
                'font_family' => 'Teko',
                'tema_id' => 1
            ],
            [
                'congregacao_id' => 2,
                'logo_caminho' => 'images/logo_agape2.jpeg',
                'banner_caminho' => 'images/banner_agape.jpg',
                'conjunto_cores' => json_encode([
                    "primaria" => "#343A40",
                    "secundaria" => "#6C757D",
                    "terciaria" => "#DEE2E6",
                    "texto" => "#212529",
                    "fundo" => "#F8F9FA"
                ]),
                'font_family' => 'Roboto',
                'tema_id' => 3
            ]
        ]);

        // Usuário admin
        DB::table('users')->insert([
            'name' => 'kleros.admin',
            'email' => 'admin@kleros.com',
            'password' => '$2y$12$vYj8Ljo3wkj9vXg.zePicejHiV6n9kOOib6clWt.gqrwddLrgdPka', // já está criptografada
            'email_verified_at' => now(),
            'denominacao_id' => null,
            'congregacao_id' => null,
            'membro_id' => null,
        ]);

        DB::table('situacao_visitantes')->insert([
            ['titulo' => 'Membro de outra denominação'],
            ['titulo' => 'Não congrega no momento'],
            ['titulo' => 'Não Evangélico'],
            ['titulo' => 'Sem Religião'],
        ]);

        DB::table('escolaridades')->insert([
            ['titulo' => 'Não frequentou a escola'],
            ['titulo' => 'Ensino Básico'],
            ['titulo' => 'Ensino Fundamental'],
            ['titulo' => 'Ensino Médio'],
            ['titulo' => 'Ensino Técnico'],
            ['titulo' => 'Ensino Superior'],
            ['titulo' => 'Pós-Graduação'],
            ['titulo' => 'Mestrado'],
            ['titulo' => 'Doutorado'],
        ]);

        DB::table('estado_civs')->insert([
            ['titulo' => 'Solteiro'],
            ['titulo' => 'Casado'],
            ['titulo' => 'Divorciado'],
            ['titulo' => 'Viúvo'],
            ['titulo' => 'Separado'],
        ]);
    }
}
