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

When a Builder block contains a Repeater alongside two layout components (`Group`, `Fieldset`, `Grid`, `Section`, ...) with `->visible()` — where the second group has multiple fields — saving the form crashes with:

```
Call to a member function getStateSnapshot() on null
  at vendor/filament/forms/src/Components/Repeater.php:1215
```

The initial page load works fine. The crash only occurs when saving. This problem was introduced in v4.9.4.

## Expected Behavior

The page should re-render without errors.

## Steps to Reproduce

1. Create a Filament resource with a Builder field containing the block schema shown below
2. Create a new record — add a builder block and add at least one repeater item
3. Save — the record is created successfully
4. Edit the record — the page loads fine
5. Click Save again
6. The page crashes with `Call to a member function getStateSnapshot() on null`

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
            ->live()
            ->required(),

        Group::make()
            ->schema([
                TextInput::make('url')
                    ->label('Internal URL')
                    ->required(),
            ])
            ->visible(fn (Get $get) => $get('link') === 'internal'),

        Group::make()
            ->schema([
                TextInput::make('url')
                    ->label('External URL')
                    ->required(),

                Toggle::make('nofollow')
                    ->default(false)
                    ->inline(false),

                Toggle::make('sponsored')
                    ->default(false)
                    ->inline(false),
            ])
            ->visible(fn (Get $get) => $get('link') === 'external'),
    ]),
```

## Reproduction Repository

https://github.com/psyao/filament-builder-bug

## Relevant Log Output

```
# Error - Internal Server Error

Call to a member function getStateSnapshot() on null

PHP 8.4.19
Laravel 12.56.0
filament-builder-bug.test

## Stack Trace

0 - vendor/filament/forms/src/Components/Repeater.php:1215
1 - vendor/filament/forms/resources/views/components/repeater/index.blade.php:93
2 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:37
3 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:38
4 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
5 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:16
6 - vendor/laravel/framework/src/Illuminate/View/View.php:208
7 - vendor/laravel/framework/src/Illuminate/View/View.php:191
8 - vendor/laravel/framework/src/Illuminate/View/View.php:160
9 - vendor/filament/support/src/Components/ViewComponent.php:125
10 - vendor/filament/schemas/src/Components/Component.php:219
11 - vendor/filament/schemas/src/Schema.php:205
12 - vendor/filament/support/src/Components/ViewComponent.php:122
13 - vendor/laravel/framework/src/Illuminate/Support/helpers.php:131
14 - vendor/filament/forms/resources/views/components/builder.blade.php:268
15 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:37
16 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:38
17 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
18 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:16
19 - vendor/laravel/framework/src/Illuminate/View/View.php:208
20 - vendor/laravel/framework/src/Illuminate/View/View.php:191
21 - vendor/laravel/framework/src/Illuminate/View/View.php:160
22 - vendor/filament/support/src/Components/ViewComponent.php:125
23 - vendor/filament/schemas/src/Components/Component.php:219
24 - vendor/filament/schemas/src/Schema.php:205
25 - vendor/filament/support/src/Components/ViewComponent.php:122
26 - vendor/filament/schemas/src/Components/EmbeddedSchema.php:39
27 - vendor/filament/support/src/Components/ViewComponent.php:122
28 - vendor/filament/schemas/src/Components/Component.php:219
29 - vendor/filament/schemas/src/Schema.php:205
30 - vendor/filament/support/src/Components/ViewComponent.php:122
31 - vendor/laravel/framework/src/Illuminate/Support/helpers.php:131
32 - vendor/filament/schemas/resources/views/components/form.blade.php:17
33 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:37
34 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:38
35 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
36 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:16
37 - vendor/laravel/framework/src/Illuminate/View/View.php:208
38 - vendor/laravel/framework/src/Illuminate/View/View.php:191
39 - vendor/laravel/framework/src/Illuminate/View/View.php:160
40 - vendor/filament/support/src/Components/ViewComponent.php:125
41 - vendor/filament/schemas/src/Components/Component.php:219
42 - vendor/filament/schemas/src/Schema.php:205
43 - vendor/filament/support/src/Components/ViewComponent.php:122
44 - vendor/laravel/framework/src/Illuminate/Support/helpers.php:131
45 - vendor/filament/filament/resources/views/pages/page.blade.php:2
46 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:37
47 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:38
48 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:76
49 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:16
50 - vendor/laravel/framework/src/Illuminate/View/View.php:208
51 - vendor/laravel/framework/src/Illuminate/View/View.php:191
52 - vendor/laravel/framework/src/Illuminate/View/View.php:160
53 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:259
54 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:303
55 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:251
56 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:104
57 - vendor/livewire/livewire/src/LivewireManager.php:102
58 - vendor/livewire/livewire/src/Mechanisms/HandleRequests/HandleRequests.php:129
59 - vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:46
60 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:265
61 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:211
62 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:822
63 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
64 - vendor/laravel/boost/src/Middleware/InjectBoost.php:22
65 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
66 - vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php:50
67 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
68 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php:87
69 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
70 - vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php:48
71 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
72 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:120
73 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
74 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
75 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
76 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
77 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
78 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
79 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
80 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
81 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
82 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
83 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
84 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
85 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
86 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
87 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
88 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:27
89 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
90 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:47
91 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
92 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
93 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
94 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
95 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
96 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:61
97 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
98 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
99 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
100 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
101 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
102 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
103 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
104 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
105 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
106 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
107 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
108 - public/index.php:20
109 - /Applications/Herd.app/Contents/Resources/valet/server.php:167

## Request

POST /livewire/update

## Headers

* **cookie**: XSRF-TOKEN [REDACTED]]
* **accept-language**: en-US,en;q=0.9,fr-CH;q=0.8,fr;q=0.7
* **accept-encoding**: gzip, deflate
* **referer**: http://filament-builder-bug.test/admin/pages/2/edit
* **origin**: http://filament-builder-bug.test
* **accept**: */*
* **x-livewire**: 
* **content-type**: application/json
* **user-agent**: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36
* **content-length**: 1704
* **connection**: keep-alive
* **host**: filament-builder-bug.test

## Route Context

controller: Livewire\Mechanisms\HandleRequests\HandleRequests@handleUpdate
route name: default.livewire.update
middleware: web

```
