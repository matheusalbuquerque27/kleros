<?php

namespace Modules\Drive\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Arquivo;
use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DriveController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $this->ensureDefaultFolders();

        $relativePath = $this->sanitizePath($request->query('path', ''));
        $viewMode = $this->resolveViewMode($request->query('view'));

        $fullPath = $this->fullPath($relativePath);
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if ($relativePath !== '' && ! $disk->exists($fullPath)) {
            return redirect()->route('drive.painel')->with('error', 'A pasta informada não foi encontrada.');
        }

        $items = $this->listItems($fullPath, $relativePath);

        return view('drive::painel', [
            'appName' => config('app.name'),
            'items' => $items,
            'currentPath' => $relativePath,
            'parentPath' => $this->parentPath($relativePath),
            'breadcrumbs' => $this->breadcrumbs($relativePath),
            'viewMode' => $viewMode,
        ]);
    }

    public function upload(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:51200'],
            'current_path' => ['nullable', 'string'],
            'view' => ['nullable', 'string'],
        ]);

        $relativePath = $this->sanitizePath($validated['current_path'] ?? '');
        $fullPath = $this->fullPath($relativePath);
        $disk = Storage::disk('public');
        $disk->makeDirectory($fullPath);

        $file = $validated['file'];

        $storedPath = $file->store($fullPath, 'public');

        $arquivo = Arquivo::firstOrNew([
            'congregacao_id' => $this->congregacaoId(),
            'caminho' => $storedPath,
        ]);

        $arquivo->nome = $file->getClientOriginalName();
        $arquivo->tipo = $this->resolveTipo($file->getClientOriginalExtension());
        $arquivo->save();

        return redirect()->route('drive.painel', array_filter([
            'path' => $relativePath ?: null,
            'view' => $validated['view'] ?? null,
        ]))->with('success', 'Arquivo enviado com sucesso.');
    }

    public function storeFolder(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'folder_name' => ['required', 'string', 'max:50', 'regex:/^[^\\\\\/]+$/'],
            'current_path' => ['nullable', 'string'],
            'view' => ['nullable', 'string'],
        ]);

        $relativePath = $this->sanitizePath($validated['current_path'] ?? '');
        $folderName = trim($validated['folder_name']);

        $disk = Storage::disk('public');
        $target = $this->fullPath($this->joinRelativePath($relativePath, $folderName));

        if ($disk->exists($target)) {
            return back()->withInput()->with('error', 'Já existe uma pasta com este nome neste local.');
        }

        $disk->makeDirectory($target);

        return redirect()->route('drive.painel', array_filter([
            'path' => $relativePath ?: null,
            'view' => $validated['view'] ?? null,
        ]))->with('success', 'Pasta criada com sucesso.');
    }

    public function destroy(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'target' => ['required', 'string'],
            'type' => ['required', 'in:file,directory'],
            'current_path' => ['nullable', 'string'],
            'view' => ['nullable', 'string'],
            'origin' => ['nullable', 'in:storage,database'],
            'arquivo_id' => ['nullable', 'integer'],
        ]);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $origin = $validated['origin'] ?? 'storage';

        if ($validated['type'] === 'directory') {
            $relativeTarget = $this->sanitizePath($validated['target']);
            $fullTarget = $this->fullPath($relativeTarget);

            if (! $disk->exists($fullTarget)) {
                return back()->with('error', 'Pasta não encontrada.');
            }

            $disk->deleteDirectory($fullTarget);

            Arquivo::where('congregacao_id', $this->congregacaoId())
                ->where('caminho', 'like', $fullTarget . '/%')
                ->delete();

            return redirect()->route('drive.painel', array_filter([
                'path' => $this->sanitizePath($validated['current_path'] ?? ''),
                'view' => $validated['view'] ?? null,
            ]))->with('success', 'Pasta excluída com sucesso.');
        }

        if ($origin === 'database') {
            if (empty($validated['arquivo_id'])) {
                return back()->with('error', 'Arquivo não encontrado.');
            }

            $arquivo = Arquivo::query()
                ->where('congregacao_id', $this->congregacaoId())
                ->where('id', $validated['arquivo_id'])
                ->first();

            if (! $arquivo) {
                return back()->with('error', 'Arquivo não encontrado.');
            }

            $storagePath = $this->normalizeStoragePath($arquivo->caminho);

            if ($storagePath && $disk->exists($storagePath)) {
                $disk->delete($storagePath);
            }

            $arquivo->delete();
        } else {
            $relativeTarget = $this->sanitizePath($validated['target']);
            $fullTarget = $this->fullPath($relativeTarget);

            if (! $disk->exists($fullTarget)) {
                return back()->with('error', 'Arquivo não encontrado.');
            }

            $disk->delete($fullTarget);

            Arquivo::where('congregacao_id', $this->congregacaoId())
                ->where('caminho', $fullTarget)
                ->delete();
        }

        return redirect()->route('drive.painel', array_filter([
            'path' => $this->sanitizePath($validated['current_path'] ?? ''),
            'view' => $validated['view'] ?? null,
        ]))->with('success', 'Arquivo excluído com sucesso.');
    }

    private function listItems(string $fullPath, string $relativePath): array
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if ($relativePath === '') {
            return $this->rootDirectories($disk, $fullPath);
        }

        $directories = collect($disk->directories($fullPath))->map(function (string $directory) use ($relativePath) {
            $name = basename($directory);
            $relative = $this->joinRelativePath($relativePath, $name);

            return [
                'name' => $name,
                'display_name' => Str::limit($name, 28),
                'relative_path' => $relative,
                'delete_target' => $relative,
                'type' => 'directory',
                'icon' => 'bi bi-folder',
                'origin' => 'storage',
                'deletable' => true,
            ];
        })->keyBy('relative_path');

        $diskFiles = collect($disk->files($fullPath));

        $recordsByPath = Arquivo::query()
            ->where('congregacao_id', $this->congregacaoId())
            ->whereIn('caminho', $diskFiles->all())
            ->get()
            ->keyBy('caminho');

        $files = $diskFiles->map(function (string $file) use ($relativePath, $disk, $recordsByPath) {
            $name = basename($file);
            $relative = $this->joinRelativePath($relativePath, $name);
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $size = $disk->size($file);
            $updatedAt = Carbon::createFromTimestamp($disk->lastModified($file));
            $record = $recordsByPath->get($file);
            $displayName = $record?->nome ?? $name;

            return [
                'name' => $displayName,
                'display_name' => Str::limit($displayName, 28),
                'relative_path' => $relative,
                'delete_target' => $relative,
                'type' => 'file',
                'icon' => $this->iconFromExtension($extension),
                'extension' => $extension ?: 'file',
                'url' => $this->resolveFileUrl($file),
                'size' => $size,
                'size_label' => $this->formatSize($size),
                'updated_at' => $updatedAt,
                'updated_label' => $updatedAt->diffForHumans(),
                'origin' => 'storage',
                'arquivo_id' => $record?->id,
            ];
        })->keyBy('delete_target');

        if ($relativePath === 'Imagens') {
            $files = $this->mergeDatabaseFiles($files, 'imagem', $disk, $relativePath, $fullPath);
        }

        if ($relativePath === 'Documentos') {
            $files = $this->mergeDatabaseFiles($files, 'documento', $disk, $relativePath, $fullPath);
        }

        return $directories
            ->merge($files)
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }

    private function rootDirectories(FilesystemAdapter $disk, string $fullPath): array
    {
        $defaults = collect($this->defaultFolders())->map(function (string $name) {
            return [
                'name' => $name,
                'display_name' => Str::limit($name, 28),
                'relative_path' => $name,
                'delete_target' => $name,
                'type' => 'directory',
                'icon' => 'bi bi-folder',
                'origin' => 'storage',
                'deletable' => false,
            ];
        })->keyBy('relative_path');

        $existing = collect($disk->directories($fullPath))->map(function (string $directory) {
            $name = basename($directory);

            return [
                'name' => $name,
                'display_name' => Str::limit($name, 28),
                'relative_path' => $name,
                'delete_target' => $name,
                'type' => 'directory',
                'icon' => 'bi bi-folder',
                'origin' => 'storage',
                'deletable' => ! in_array($name, $this->defaultFolders(), true),
            ];
        })->keyBy('relative_path');

        return $defaults
            ->merge($existing)
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }

    private function defaultFolders(): array
    {
        return ['Imagens', 'Documentos'];
    }

    private function mergeDatabaseFiles(Collection $files, string $tipo, FilesystemAdapter $disk, string $relativePath, string $fullPath): Collection
    {
        $records = Arquivo::query()
            ->where('congregacao_id', $this->congregacaoId())
            ->where('tipo', $tipo)
            ->orderBy('nome')
            ->get();

        foreach ($records as $arquivo) {
            $storagePath = $this->normalizeStoragePath($arquivo->caminho);

            if ($storagePath === null) {
                continue;
            }

            if (Str::startsWith($storagePath, $fullPath . '/')) {
                continue;
            }

            $extension = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
            $exists = $disk->exists($storagePath);
            $size = $exists ? $disk->size($storagePath) : null;
            $updatedAt = $exists ? Carbon::createFromTimestamp($disk->lastModified($storagePath)) : null;

            $files->put('db-' . $arquivo->id, [
                'name' => $arquivo->nome,
                'display_name' => Str::limit($arquivo->nome, 28),
                'relative_path' => $relativePath,
                'delete_target' => $storagePath,
                'type' => 'file',
                'icon' => $this->iconFromExtension($extension ?: null),
                'extension' => $extension ?: 'file',
                'url' => $this->resolveFileUrl($storagePath),
                'size' => $size,
                'size_label' => $size !== null ? $this->formatSize($size) : '---',
                'updated_at' => $updatedAt,
                'updated_label' => $updatedAt ? $updatedAt->diffForHumans() : '---',
                'origin' => 'database',
                'arquivo_id' => $arquivo->id,
                'original_path' => $arquivo->caminho,
            ]);
        }

        return $files;
    }

    private function resolveFileUrl(string $storagePath): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $rawUrl = $disk->url($storagePath);

        $path = parse_url($rawUrl, PHP_URL_PATH) ?: '/' . ltrim($storagePath, '/');
        $path = ltrim($path, '/');

        return url($path);
    }

    private function normalizeStoragePath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $normalized = ltrim($path, '/');

        if (Str::startsWith($normalized, 'storage/')) {
            $normalized = substr($normalized, strlen('storage/'));
        }

        return $normalized;
    }

    private function sanitizePath(?string $path): string
    {
        $path = str_replace('\\', '/', (string) $path);
        $segments = array_filter(explode('/', $path), fn ($segment) => $segment !== '' && $segment !== '.' && $segment !== '..');

        return implode('/', $segments);
    }

    private function joinRelativePath(string $base, string $segment): string
    {
        $combined = trim($base, '/');
        $segment = trim($segment, '/');

        if ($combined === '') {
            return $segment;
        }

        if ($segment === '') {
            return $combined;
        }

        return $combined . '/' . $segment;
    }

    private function resolveViewMode(?string $view): string
    {
        return in_array($view, ['list', 'grid'], true) ? $view : 'grid';
    }

    private function resolveTipo(?string $extension): string
    {
        $extension = strtolower((string) $extension);

        $imagens = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'svg'];
        $videos = ['mp4', 'mov', 'avi', 'mkv'];
        $audios = ['mp3', 'wav', 'aac', 'ogg', 'flac'];

        return match (true) {
            in_array($extension, $imagens, true) => 'imagem',
            in_array($extension, $videos, true) => 'video',
            in_array($extension, $audios, true) => 'audio',
            default => 'documento',
        };
    }

    private function iconFromExtension(?string $extension): string
    {
        $map = [
            'pdf' => 'bi bi-filetype-pdf',
            'doc' => 'bi bi-file-earmark-word',
            'docx' => 'bi bi-file-earmark-word',
            'xls' => 'bi bi-file-earmark-excel',
            'xlsx' => 'bi bi-file-earmark-excel',
            'ppt' => 'bi bi-file-earmark-ppt',
            'pptx' => 'bi bi-file-earmark-ppt',
            'zip' => 'bi bi-file-earmark-zip',
            'rar' => 'bi bi-file-earmark-zip',
            'mp3' => 'bi bi-file-earmark-music',
            'wav' => 'bi bi-file-earmark-music',
            'aac' => 'bi bi-file-earmark-music',
            'ogg' => 'bi bi-file-earmark-music',
            'flac' => 'bi bi-file-earmark-music',
            'mp4' => 'bi bi-file-earmark-play',
            'mov' => 'bi bi-file-earmark-play',
            'avi' => 'bi bi-file-earmark-play',
            'mkv' => 'bi bi-file-earmark-play',
            'png' => 'bi bi-file-earmark-image',
            'jpg' => 'bi bi-file-earmark-image',
            'jpeg' => 'bi bi-file-earmark-image',
            'gif' => 'bi bi-file-earmark-image',
            'bmp' => 'bi bi-file-earmark-image',
            'webp' => 'bi bi-file-earmark-image',
            'svg' => 'bi bi-file-earmark-image',
        ];

        return $map[$extension] ?? 'bi bi-file-earmark';
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);

        $value = $bytes / (1024 ** $power);

        return ($power === 0 ? number_format($value, 0) : number_format($value, 2)) . ' ' . $units[$power];
    }

    private function parentPath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $segments = explode('/', $path);
        array_pop($segments);
        $parent = implode('/', $segments);

        return $parent !== '' ? $parent : null;
    }

    private function breadcrumbs(string $relativePath): array
    {
        $segments = $relativePath === '' ? [] : explode('/', $relativePath);
        $breadcrumbs = [
            ['label' => 'Início', 'path' => ''],
        ];

        $current = '';

        foreach ($segments as $segment) {
            $current = $this->joinRelativePath($current, $segment);
            $breadcrumbs[] = [
                'label' => $segment,
                'path' => $current,
            ];
        }

        return $breadcrumbs;
    }

    private function ensureDefaultFolders(): void
    {
        $disk = Storage::disk('public');
        $base = $this->basePath();

        $disk->makeDirectory($base);

        foreach (['Imagens', 'Documentos'] as $folder) {
            $disk->makeDirectory($base . '/' . $folder);
        }
    }

    private function basePath(): string
    {
        return 'congregacoes/' . $this->congregacaoId() . '/drive';
    }

    private function fullPath(string $relativePath = ''): string
    {
        $base = $this->basePath();

        return $relativePath === '' ? $base : $base . '/' . $relativePath;
    }

    private function congregacaoId(): int
    {
        $congregacao = app('congregacao');

        abort_if(! $congregacao, 404, 'Congregação não encontrada.');

        return (int) $congregacao->id;
    }

    private function authorizeAccess(): void
    {
        abort_unless(Auth::check(), 403);
    }
}
