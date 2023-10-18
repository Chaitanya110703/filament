@php
    use Filament\Support\Enums\ActionSize;
    use Filament\Support\Enums\Alignment;
    use Filament\Support\Enums\IconSize;
@endphp

@props([
    'actions',
    'alignment' => Alignment::Start,
    'fullWidth' => false,
    'reversed' => false,
    'wrap' => true,
])

@if ($actions instanceof \Illuminate\Contracts\View\View)
    {{ $actions }}
@elseif (is_array($actions))
    @php
        $actions = array_filter(
            $actions,
            fn ($action): bool => $action->isVisible(),
        );

        if (! $alignment instanceof Alignment) {
            $alignment = Alignment::tryFrom($alignment) ?? $alignment;
        }
    @endphp

    @if (count($actions))
        <div
            {{
                $attributes->class([
                    'fi-ac shrink-0 gap-3',
                    'flex items-center' => ! $fullWidth,
                    'flex-wrap' => (! $fullWidth) && $wrap,
                    'sm:flex-nowrap' => (! $fullWidth) && ($wrap === '-sm'),
                    'flex-row-reverse' => $reversed,
                    match ($alignment) {
                        Alignment::Start, Alignment::Left => 'justify-start',
                        Alignment::Center => 'justify-center',
                        Alignment::End, Alignment::Right => 'justify-end',
                        Alignment::Between => 'justify-between',
                        'start md:end' => 'justify-start md:justify-end',
                        default => $alignment,
                    } => ! $fullWidth,
                    'grid grid-cols-[repeat(auto-fit,minmax(0,1fr))]' => $fullWidth,
                ])
            }}
        >
            @foreach ($actions as $action)
                @php
                    $labeledFromBreakpoint = $action->getLabeledFromBreakpoint();

                    $iconSize = $action->getIconSize();

                    if (! $iconSize instanceof IconSize) {
                        $iconSize = IconSize::tryFrom($iconSize) ?? $iconSize;
                    }

                    $size = $action->getSize();

                    if (! $size instanceof ActionSize) {
                        $size = ActionSize::tryFrom($size) ?? $size;
                    }
                @endphp

                <span
                    @class([
                        'inline-flex',
                        'bar' => $action->isIconButton() || $labeledFromBreakpoint,
                        match (true) {
                            ($size === ActionSize::ExtraSmall) && ($iconSize === IconSize::Small) => '-m-1.5',
                            ($size === ActionSize::ExtraSmall) && ($iconSize === IconSize::Medium) => '-m-1',
                            ($size === ActionSize::ExtraSmall) && ($iconSize === IconSize::Large) => '-m-0.5',
                            ($size === ActionSize::Small) && ($iconSize === IconSize::Small) => '-m-2',
                            ($size === ActionSize::Small) && ($iconSize === IconSize::Medium) => '-m-1.5',
                            ($size === ActionSize::Small) && ($iconSize === IconSize::Large) => '-m-1',
                            ($size === ActionSize::Medium) && ($iconSize === IconSize::Small) => '-m-2.5',
                            ($size === ActionSize::Medium) && ($iconSize === IconSize::Medium) => '-m-2',
                            ($size === ActionSize::Medium) && ($iconSize === IconSize::Large) => '-m-1.5',
                            ($size === ActionSize::Large) && ($iconSize === IconSize::Small) => '-m-3',
                            ($size === ActionSize::Large) && ($iconSize === IconSize::Medium) => '-m-2.5',
                            ($size === ActionSize::Large) && ($iconSize === IconSize::Large) => '-m-2',
                            ($size === ActionSize::ExtraLarge) && ($iconSize === IconSize::Small) => '-m-3.5',
                            ($size === ActionSize::ExtraLarge) && ($iconSize === IconSize::Medium) => '-m-3',
                            ($size === ActionSize::ExtraLarge) && ($iconSize === IconSize::Large) => '-m-2.5',
                            default => null,
                        } => $action->isIconButton() || $labeledFromBreakpoint,
                        match ($labeledFromBreakpoint) {
                            'sm' => 'sm:mx-0',
                            'md' => 'md:mx-0',
                            'lg' => 'lg:mx-0',
                            'xl' => 'xl:mx-0',
                            '2xl' => '2xl:mx-0',
                            default => null,
                        },
                    ])
                >
                    {{ $action }}
                </span>
            @endforeach
        </div>
    @endif
@endif
