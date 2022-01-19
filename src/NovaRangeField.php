<?php

namespace Madewithlove\NovaRangeField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaRangeField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-range-field';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        if (is_array($name)) $name = implode('-', $name);
        if (is_array($attribute)) $attribute = implode('-', $attribute);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        if ($request->exists($requestAttribute)) {
            [$min, $max] = $this->parseAttribute($attribute);
            $values = $this->parseResponse($request[$requestAttribute]);
            data_set($model, $min, data_get($values, 'min'));
            data_set($model, $max, data_get($values, 'max'));
        }
    }

    protected function resolveAttribute($resource, $attribute): string|null
    {
        [$min, $max] = $this->parseAttribute($attribute);
        $minValue = data_get($resource, $min);
        $maxValue = data_get($resource, $max);

        return json_encode([
            'min' => $minValue,
            'max' => $maxValue,
        ]);
    }

    protected function parseAttribute($attribute): array
    {
        return explode('-', $attribute);
    }

    protected function parseResponse($attribute): array
    {
        if ($attribute === null) {
            return [
                'min' => null,
                'max' => null,
            ];
        }

        return json_decode($attribute, true);
    }

    public function min(float $min): self
    {
        return $this->withMeta(['min' => $min]);
    }

    public function max(float $max): self
    {
        return $this->withMeta(['max' => $max]);
    }

    public function interval(float $interval): self
    {
        return $this->withMeta(['interval' => $interval]);
    }

    public function withoutTooltip(): self
    {
        return $this->withMeta(['tooltip' => false]);
    }

    public function tooltipOnHover(): self
    {
        return $this->withMeta(['tooltip' => 'hover']);
    }

    public function formatter(string $formatter): self
    {
        return $this->withMeta(['formatter' => $formatter]);
    }
}
