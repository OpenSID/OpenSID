<?php

namespace Yajra\DataTables\Html\Options\Plugins;

/**
 * DataTables - Column control plugin option builder.
 *
 * @see https://datatables.net/extensions/columncontrol/
 * @see https://datatables.net/extensions/columncontrol/config
 */
trait ColumnControl
{
    /**
     * Set column control options.
     *
     * @see https://www.datatables.net/extensions/columncontrol/
     */
    public function columnControl(array $options): static
    {
        $this->attributes['columnControl'] = $options;

        return $this;
    }

    protected function addColumnControl(int|string $target, array $content): static
    {
        if (! isset($this->attributes['columnControl']) || ! is_array($this->attributes['columnControl'])) {
            $this->attributes['columnControl'] = [];
        }

        // get existing target and merge content if exists
        foreach ($this->attributes['columnControl'] as &$control) {
            if (isset($control['target']) && $control['target'] === $target) {
                $existingContent = $control['content'] ?? [];
                $mergedContent = array_merge($existingContent, $content);

                // Remove duplicates properly for mixed array types (strings and arrays)
                $uniqueContent = [];
                foreach ($mergedContent as $item) {
                    $serialized = is_array($item) ? serialize($item) : $item;
                    if (! in_array($serialized, array_map(fn ($i) => is_array($i) ? serialize($i) : $i, $uniqueContent))) {
                        $uniqueContent[] = $item;
                    }
                }

                $control['content'] = $uniqueContent;

                return $this;
            }
        }

        $this->attributes['columnControl'][] = ['target' => $target, 'content' => $content];

        return $this;
    }

    public function columnControlHeader(array $content, int $target = 0): static
    {
        $this->addColumnControl('thead:'.$target, $content);

        return $this;
    }

    public function columnControlFooter(array $content, int $target = 0): static
    {
        $this->addColumnControl('tfoot:'.$target, $content);

        return $this;
    }

    public function columnControlFooterSearch(array $content = []): static
    {
        $this->columnControlFooter(empty($content) ? ['search'] : $content);

        return $this;
    }

    public function columnControlSearchDropdown(int|string $target = 0): static
    {
        $this->addColumnControl($target, ['order', 'searchDropdown']);

        return $this;
    }

    public function columnControlSearch(?array $content = null, int|string $target = 1): static
    {
        $this->addColumnControl($target, $content ?? ['search']);

        return $this;
    }

    public function columnControlSpacer(int|string $target = 1): static
    {
        $this->addColumnControl($target, [['extend' => 'spacer']]);

        return $this;
    }

    public function columnControlTitle(null|string|array $title = null, int|string $target = 1): static
    {
        if (is_array($title)) {
            $this->addColumnControl($target, [['extend' => 'title', ...$title]]);
        } else {
            $this->addColumnControl($target, [['extend' => 'title', 'text' => $title]]);
        }

        return $this;
    }
}
