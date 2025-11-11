<?php

namespace App\Helpers;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
class ColorHelper
{
    public static function colorCultivo(string $cultivo): string
    {
        return match (strtolower($cultivo)) {
            
            'tomate' => '#F59E0B',
            'lechuga' => '#10B981',
            'pepino' => '#F59E0B',
            'fresa' => '#EC4899',
            default => '',
        };
    }

    public static function colorUbicacion(string $ubicacion): string
    {
        return match (strtolower($ubicacion)) {
            'norte' => 'blue',
            'sur' => 'yellow',
            'este' => 'purple',
            'oeste' => 'orange',
            default => 'gray',
        };
    }
      public function getHexColorAttribute()
    {
        $hexColors = [
            'primary' => '#3B82F6',  // Azul
            'success' => '#10B981',  // Verde
            'warning' => '#F59E0B',  // Amarillo/Naranja
            'danger' => '#EF4444',   // Rojo
            'info' => '#06B6D4',     // Cyan
            'gray' => '#6B7280',     // Gris
            'purple' => '#8B5CF6',   // Morado
            'pink' => '#EC4899',     // Rosa
            'indigo' => '#6366F1',   // Índigo
            'orange' => '#F97316',   // Naranja
        ];

        FilamentColor::register([
    'danger' => [
        50 => 'oklch(0.969 0.015 12.422)',
        100 => 'oklch(0.941 0.03 12.58)',
        200 => 'oklch(0.892 0.058 10.001)',
        300 => 'oklch(0.81 0.117 11.638)',
        400 => 'oklch(0.712 0.194 13.428)',
        500 => 'oklch(0.645 0.246 16.439)',
        600 => 'oklch(0.586 0.253 17.585)',
        700 => 'oklch(0.514 0.222 16.935)',
        800 => 'oklch(0.455 0.188 13.697)',
        900 => 'oklch(0.41 0.159 10.272)',
        950 => 'oklch(0.271 0.105 12.094)',
    ],
]);
    }
}
