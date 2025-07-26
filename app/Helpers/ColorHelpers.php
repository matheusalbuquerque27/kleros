<?php

/**
 * Converte um valor hexadecimal de cor para RGB.
 * @param string $hex Cor em formato HEX (ex: #RRGGBB ou #RGB).
 * @return array [r, g, b]
 */
function hexToRgb($hex) {
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return [$r, $g, $b];
}

/**
 * Calcula a luminância perceptiva de uma cor RGB.
 * Baseado na fórmula para sRGB.
 * @param array $rgb [r, g, b]
 * @return float Luminância (0 a 1)
 */
function getLuminance($rgb) {
    list($r, $g, $b) = $rgb;

    $r /= 255;
    $g /= 255;
    $b /= 255;

    $R = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
    $G = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
    $B = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

    return 0.2126 * $R + 0.7152 * $G + 0.0722 * $B;
}

/**
 * Determina a cor do texto de contraste (preto ou branco) para um fundo dado.
 * @param string $backgroundColor Cor de fundo (HEX).
 * @param float $threshold Limite de luminância para contraste (0.5 é um bom padrão).
 * @param string $lightColor Cor para texto claro (ex: #ffffff).
 * @param string $darkColor Cor para texto escuro (ex: #000000).
 * @return string Cor do texto de contraste (HEX).
 */
function getContrastTextColor($backgroundColor, $threshold = 0.5, $lightColor = '#ffffff', $darkColor = '#000000') {
    $rgb = hexToRgb($backgroundColor);
    $luminance = getLuminance($rgb);

    if ($luminance < $threshold) {
        return $lightColor; // Fundo escuro, texto claro
    } else {
        return $darkColor;  // Fundo claro, texto escuro
    }
}