<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Storage;

trait ResolvesCongregacaoLogoDataUri
{
    protected function resolveCongregacaoLogoDataUri($congregacao): ?string
    {
        $logoPath = (string) data_get($congregacao, 'config.logo_caminho', '');

        if ($logoPath === '') {
            return null;
        }

        $normalizedPath = ltrim($logoPath, '/');
        $normalizedPath = str_starts_with($normalizedPath, 'storage/') ? substr($normalizedPath, 8) : $normalizedPath;
        $normalizedPath = str_starts_with($normalizedPath, 'public/') ? substr($normalizedPath, 7) : $normalizedPath;

        $candidates = array_values(array_unique(array_filter([
            $normalizedPath,
            'congregacoes/' . $congregacao->id . '/imagens/' . basename($normalizedPath),
        ])));

        $directoryPath = Storage::disk('public')->path('congregacoes/' . $congregacao->id . '/imagens');

        if (is_dir($directoryPath)) {
            $fallbackFiles = glob($directoryPath . '/*.{png,jpg,jpeg,webp,svg}', GLOB_BRACE) ?: [];

            usort($fallbackFiles, fn (string $a, string $b) => filemtime($b) <=> filemtime($a));

            foreach ($fallbackFiles as $fallbackFile) {
                $candidates[] = 'congregacoes/' . $congregacao->id . '/imagens/' . basename($fallbackFile);
            }

            $candidates = array_values(array_unique($candidates));
        }

        foreach ($candidates as $candidate) {
            if (! Storage::disk('public')->exists($candidate)) {
                continue;
            }

            $absolutePath = Storage::disk('public')->path($candidate);

            if (! is_file($absolutePath)) {
                continue;
            }

            $mimeType = mime_content_type($absolutePath) ?: 'image/png';

            return 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($absolutePath));
        }

        return null;
    }
}
