<?php

namespace Filament\Support\Concerns;

use Closure;
use Filament\Actions\ActionGroup;
use Filament\Actions\StaticAction;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;

trait HasIcon
{
    protected string | Closure | null $icon = null;

    protected IconPosition | string | Closure | null $iconPosition = null;

    protected IconSize | string | Closure | null $iconSize = null;

    public function icon(string | Closure | null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function iconPosition(IconPosition | string | Closure | null $position): static
    {
        $this->iconPosition = $position;

        return $this;
    }

    public function iconSize(IconSize | string | Closure | null $size): static
    {
        $this->iconSize = $size;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    public function getIconPosition(): IconPosition | string
    {
        return $this->evaluate($this->iconPosition) ?? IconPosition::Before;
    }

    public function getIconSize(): IconSize | string | null
    {
        $iconSize = $this->evaluate($this->iconSize);

        if ((($this instanceof ActionGroup) || ($this instanceof StaticAction)) && $this->isIconButton()) {
            $iconSize ??= match ($this->getSize()) {
                ActionSize::ExtraSmall => IconSize::Small,
                ActionSize::Small, ActionSize::Medium => IconSize::Medium,
                ActionSize::Large, ActionSize::ExtraLarge => IconSize::Large,
                default => IconSize::Medium,
            };
        }

        return $iconSize;
    }
}
