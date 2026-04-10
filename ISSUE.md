# Bug Report

## Package

filament/forms

## Package Version

v4.10.0

## Laravel Version

v12.56.0

## Livewire Version

v3.7.15

## PHP Version

PHP 8.4.19


## Problem Description

When a Builder block contains a layout component (e.g., `Group`, `Fieldset`, `Grid`) with `->visible()`, any Livewire update request crashes with:

```
Call to a member function getStateSnapshot() on null
  at vendor/filament/forms/src/Components/Repeater.php:1215
```

Any Livewire re-render (e.g., expanding/collapsing a builder block, interacting with any other field on the page) will cause the crash as long as there is a layout component (`Group`, `Fieldset`, `Grid`, etc.) with `->visible()` in the block schema.

The error occurs in `Repeater::getItemLabel()`, which calls `$this->getChildSchema($key)->getStateSnapshot()` without checking if `getChildSchema($key)` returns null. The initial page load works fine. The crash only happens on subsequent Livewire re-renders.

The root cause is that `cachedDefaultChildSchemas` gets populated as an empty `[]` via the `??=` operator before the repeater's state is hydrated. Once cached, it never refreshes, so `getChildSchema($key)` returns null for valid item keys on subsequent renders.

## Expected Behavior

The page should re-render without errors. `getItemLabel()` should guard against a null `$container` and return null early, rather than unconditionally calling `$container->getStateSnapshot()`.

## Steps to Reproduce

1. Create a Filament resource with a Builder field
2. Inside a block, add a Repeater alongside a layout component (`Group`, `Fieldset`, or `Grid`) with `->visible()`
3. Store at least one record with repeater items in the database
4. Visit the edit page — initial load works fine
5. Trigger any Livewire update (e.g., expand/collapse a block, interact with any field)
6. The page crashes

Minimal block example:

```php
Block::make('services')
    ->schema([
        Repeater::make('items')
            ->schema([
                Select::make('service')
                    ->options([
                        1 => 'Service 1',
                        2 => 'Service 2',
                    ])
                    ->required(),
            ]),

        Select::make('link')
            ->options([
                'internal' => 'Internal link',
                'external' => 'External link',
            ])
            ->default('internal')
            ->native(false)
            ->required(),

        Group::make()
            ->schema([
                TextInput::make('url')
                    ->required(),
            ])
            ->visible(fn (Get $get) => $get('link') === 'internal'),
    ]),
```

## Reproduction Repository

https://github.com/TODO

## Relevant Log Output

```
[2026-04-10 09:22:31] local.ERROR: Call to a member function getStateSnapshot() on null
(View: vendor/filament/forms/resources/views/components/repeater/index.blade.php)

  at vendor/filament/forms/src/Components/Repeater.php:1215
```
