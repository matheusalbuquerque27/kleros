<?php

namespace App\Http\Controllers;

use App\Models\Extensao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExtensoesController extends Controller
{
    public function index()
    {
        $modulesPath = base_path('modules');
        $congregacaoId = app()->bound('congregacao') ? optional(app('congregacao'))->id : null;

        $databaseStates = collect();

        if (\Illuminate\Support\Facades\Schema::hasTable('extensoes') && $congregacaoId) {
            $databaseStates = Extensao::query()
                ->where('congregacao_id', $congregacaoId)
                ->get()
                ->keyBy(fn ($record) => strtolower($record->module));
        }

        $modules = collect(File::directories($modulesPath))->map(function ($directory) use ($databaseStates) {
            $manifestPath = $directory . '/module.json';
            $moduleKey = strtolower(basename($directory));
            $manifest = File::exists($manifestPath)
                ? json_decode(File::get($manifestPath), true) ?: []
                : [];

            $name = data_get($manifest, 'name', Str::headline($moduleKey));
            $description = data_get($manifest, 'description', '');
            $enabled = (bool) data_get($manifest, 'enabled', false);

            if ($databaseStates->has($moduleKey)) {
                $enabled = (bool) $databaseStates[$moduleKey]->enabled;
            }

            return [
                'key' => $moduleKey,
                'name' => $name,
                'description' => $description,
                'enabled' => $enabled,
            ];
        })->sortBy('name')->values();

        return view('extensoes.painel', compact('modules'));
    }

    public function update(Request $request, string $module)
    {
        $moduleKey = strtolower($module);
        $modulesPath = base_path('modules/' . ucfirst($moduleKey));

        if (! File::isDirectory($modulesPath)) {
            abort(404, 'Extensão não encontrada.');
        }

        $congregacaoId = app()->bound('congregacao') ? optional(app('congregacao'))->id : null;

        if (! $congregacaoId) {
            abort(403, 'Extensão só pode ser gerenciada com uma congregação selecionada.');
        }

        $enabled = $request->boolean('enabled');

        Extensao::updateOrCreate([
            'congregacao_id' => $congregacaoId,
            'module' => $moduleKey,
        ], [
            'enabled' => $enabled,
        ]);

        return redirect()->route('extensoes.painel')
            ->with('msg', sprintf('Extensão %s %s com sucesso.', Str::headline($moduleKey), $enabled ? 'ativada' : 'desativada'));
    }
}
