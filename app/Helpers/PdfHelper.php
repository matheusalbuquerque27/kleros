<?php

namespace App\Helpers;

use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\PdfBuilder;

class PdfHelper
{
    /**
     * Cria um PDF a partir de uma view Blade.
     * Usa o driver configurado em LARAVEL_PDF_DRIVER (padrão: dompdf).
     */
    public static function view(string $view, array $data = []): PdfBuilder
    {
        return Pdf::view($view, $data);
    }

    /**
     * Cria um PDF a partir de HTML.
     * Usa o driver configurado em LARAVEL_PDF_DRIVER (padrão: dompdf).
     */
    public static function html(string $html): PdfBuilder
    {
        return Pdf::html($html);
    }
}
