@php
    use Filament\Support\Enums\Alignment;
@endphp

@props([
    'actions',
    'alignment' => Alignment::End,
    'record' => null,
    'wrap' => false,
])

@php
    $actions = array_filter(
        $actions,
        function ($action) use ($record): bool {
            if (! $action instanceof \Filament\Tables\Actions\BulkAction) {
                $action->record($record);
            }

            return $action->isVisible();
        },
    );
@endphp

<x-filament-actions::actions
    :actions="$actions"
    :alignment="$alignment"
    :wrap="$wrap"
    :attributes="\Filament\Support\prepare_inherited_attributes($attributes)->class('fi-ta-actions')"
/>
