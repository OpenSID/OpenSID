<?php

declare(strict_types=1);

namespace Yajra\DataTables\Html;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Traits\Macroable;
use Illuminate\View\Component;
use InvalidArgumentException;
use Livewire\Livewire;
use Throwable;
use Yajra\DataTables\Html\Enums\LayoutPosition;

class Layout extends Fluent
{
    use Macroable;

    public static function make(array $options = []): static
    {
        return new static($options);
    }

    public function top(array|string|null $options, ?int $order = null): static
    {
        $this->attributes[LayoutPosition::Top->withOrder($order)] = $options;

        return $this;
    }

    public function topStart(string|array|null $options, ?int $order = null): static
    {
        $this->attributes[LayoutPosition::TopStart->withOrder($order)] = $options;

        return $this;
    }

    public function topEnd(string|array|null $options, ?int $order = null): static
    {
        $this->attributes[LayoutPosition::TopEnd->withOrder($order)] = $options;

        return $this;
    }

    public function topView(string $selector, ?int $order = null): static
    {
        return $this->top($this->renderCustomElement($selector), $order);
    }

    public function topStartView(string $selector, ?int $order = null): static
    {
        return $this->topStart($this->renderCustomElement($selector), $order);
    }

    public function topEndView(string $selector, ?int $order = null): static
    {
        return $this->topEnd($this->renderCustomElement($selector), $order);
    }

    public function bottom(array|string|null $options, ?int $order = null): static
    {
        $this->attributes[LayoutPosition::Bottom->withOrder($order)] = $options;

        return $this;
    }

    public function bottomStart(string|array|null $options, ?int $order = null): static
    {
        $this->attributes[LayoutPosition::BottomStart->withOrder($order)] = $options;

        return $this;
    }

    public function bottomEnd(string|array|null $options, ?int $order = null): static
    {
        $this->attributes[LayoutPosition::BottomEnd->withOrder($order)] = $options;

        return $this;
    }

    public function bottomView(string $selector, ?int $order = null): static
    {
        return $this->bottom($this->renderCustomElement($selector), $order);
    }

    public function bottomStartView(string $selector, ?int $order = null): static
    {
        return $this->bottomStart($this->renderCustomElement($selector), $order);
    }

    public function bottomEndView(string $selector, ?int $order = null): static
    {
        return $this->bottomEnd($this->renderCustomElement($selector), $order);
    }

    /**
     * @throws Throwable
     */
    public function addView(
        Component|Renderable|string $view,
        LayoutPosition $layoutPosition,
        ?int $order = null
    ): static {
        if ($view instanceof Component) {
            $view = Blade::renderComponent($view);
        }

        $html = $view instanceof Renderable ? $view->render() : Blade::render($view);

        $element = json_encode($html);

        if ($element === false) {
            throw new InvalidArgumentException("Cannot render view [$html] to json.");
        }

        $this->attributes[$layoutPosition->withOrder($order)] = $this->renderCustomElement($element, false);

        return $this;
    }

    /**
     * @param  class-string  $component
     *
     * @throws Throwable
     */
    public function addLivewire(
        string $component,
        LayoutPosition $layoutPosition,
        ?int $order = null
    ): static {
        $html = json_encode(Livewire::mount($component));

        if ($html === false) {
            throw new InvalidArgumentException("Cannot render Livewire component [$component] to json.");
        }

        $this->attributes[$layoutPosition->withOrder($order)] = $this->renderCustomElement($html, false);

        return $this;
    }

    private function renderCustomElement(string $element, bool $asJsSelector = true): string
    {
        $html = $asJsSelector ? "$('{$element}').html()" : $element;

        return "function() { return $html; }";
    }
}
