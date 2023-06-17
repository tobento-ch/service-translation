# Translation Service

With the Translation Service you can translate messages easily.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
	- [Simple Example](#simple-example)
- [Documentation](#documentation)
    - [Translator](#translator)
        - [Create Translator](#create-translator)
        - [Translate Message](#translate-message)
    - [Resources](#resources)
        - [Create Resources](#create-resources)
        - [Add Resources](#add-resources)
        - [Filter Resources](#filter-resources)
        - [Sort Resources](#sort-resources)
        - [Get Resources / Translations](#get-resources-translations)
    - [Files Resources](#files-resources)
        - [Create Files Resources](#create-files-resources)
        - [Directory Structure](#directory-structure)
        - [Supported Files](#supported-files)
        - [Supporting Other Files](#supporting-other-files)
    - [Modifiers](#modifiers)
        - [Pluralization](#pluralization)
        - [Parameter Replacer](#parameter-replacer)
    - [Missing Translation Handler](#missing-translation-handler)
- [Credits](#credits)
___

# Getting started

Add the latest version of the translation service running this command.

```
composer require tobento/service-translation
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

## Simple Example

Here is a simple example of how to use the translation service:

```php
use Tobento\Service\Translation\Translator;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\Modifier\ParameterReplacer;
use Tobento\Service\Translation\Modifier\Pluralization;
use Tobento\Service\Translation\MissingTranslationHandler;

$translator = new Translator(
    new Resources(
        new Resource('*', 'de', [
            'Hello World' => 'Hallo Welt',
        ]),
    ),
    new Modifiers(
        new Pluralization(),
        new ParameterReplacer(),
    ),
    new MissingTranslationHandler(),
    'en',
);

var_dump($translator->trans('Hello World'));
// string(11) "Hello World"

var_dump($translator->trans('Hello World', [], 'de'));
// string(10) "Hallo Welt"
```

# Documentation

## Translator

### Create Translator

```php
use Tobento\Service\Translation\Translator;
use Tobento\Service\Translation\TranslatorInterface;
use Tobento\Service\Translation\LocaleAware;
use Tobento\Service\Translation\ResourcesAware;
use Tobento\Service\Translation\ModifiersAware;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\Modifier\ParameterReplacer;
use Tobento\Service\Translation\MissingTranslationHandler;

$translator = new Translator(
    resources: new Resources(
        new Resource('*', 'de', [
            'Hello World' => 'Hallo Welt',
        ]),
    ),
    modifiers: new Modifiers(
        new ParameterReplacer(),
    ),
    missingTranslationHandler: new MissingTranslationHandler(),
    locale: 'en',
    localeFallbacks: ['de' => 'en'],
    localeMapping: ['de' => 'de-CH'],
);

var_dump($translator instanceof TranslatorInterface);
// bool(true)

var_dump($translator instanceof LocaleAware);
// bool(true)

var_dump($translator instanceof ResourcesAware);
// bool(true)

var_dump($translator instanceof ModifiersAware);
// bool(true)
```

**Translator Interface**

```php
use Tobento\Service\Translation\TranslatorInterface;

$translated = $translator->trans(
    message: 'Hi :name',
    parameters: [':name' => 'John'],
    locale: 'de'
);
```

**Locale Aware**

```php
use Tobento\Service\Translation\LocaleAware;

// set the default locale:
$translator->setLocale('de');

// get the default locale:
var_dump($translator->getLocale());
// string(2) "de"

// set the locale fallbacks:
$translator->setLocaleFallbacks(['de' => 'en']);

// get the locale fallbacks:
var_dump($translator->getLocaleFallbacks());
// array(1) { ["de"]=> string(2) "en" }

// set the locale mapping:
$translator->setLocaleMapping(['de' => 'de-CH']);

// get the locale mapping:
var_dump($translator->getLocaleMapping());
// array(1) { ["de"]=> string(5) "de-CH" }
```

**Resources Aware**

See also [Resources](#resources) or [Files Resources](#files-resources) for more details.

```php
use Tobento\Service\Translation\ResourcesAware;
use Tobento\Service\Translation\ResourcesInterface;

// get the resources:
var_dump($translator->resources() instanceof ResourcesInterface);
// bool(true)

// returns the translations of the specified resource:
$translations = $translator->getResource(
    name: '*',
    locale: 'de' // or null to use default
);

var_dump($translations);
// array(1) { ["Hello World"]=> string(10) "Hallo Welt" }
```

**Modifiers Aware**

See also [Modifiers](#modifiers) for more details.

```php
use Tobento\Service\Translation\ModifiersAware;
use Tobento\Service\Translation\ModifiersInterface;

// get the modifiers:
var_dump($translator->modifiers() instanceof ModifiersInterface);
// bool(true)
```

### Translate Message

```php
use Tobento\Service\Translation\Translator;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\Modifier\Pluralization;
use Tobento\Service\Translation\Modifier\ParameterReplacer;
use Tobento\Service\Translation\MissingTranslationHandler;

$translator = new Translator(
    resources: new Resources(
        new Resource('*', 'de', [
            'Hi :name' => 'Hi :name',
            'It takes :minutes minute|It takes :minutes minutes' => 'Es dauert :minutes Minute|Es dauert :minutes Minuten'
        ]),
    ),
    modifiers: new Modifiers(
        new Pluralization(),
        new ParameterReplacer(),
    ),
    missingTranslationHandler: new MissingTranslationHandler(),
    locale: 'en',
    localeFallbacks: ['de' => 'en'],
);

$translated = $translator->trans(
    message: 'Hi :name',
    parameters: [':name' => 'John'],
    locale: 'de'
);

var_dump($translated);
// string(7) "Hi John"

$translated = $translator->trans(
    message: 'It takes :minutes minute|It takes :minutes minutes',
    parameters: [':minutes' => 5, 'count' => 5],
    locale: 'de'
);

var_dump($translated);
// string(19) "Es dauert 5 Minuten"
```

**Using specific resource**

Keep in mind that named resources are only loaded on the first resource request.\
Resources with "*" named are always loaded.

```php
use Tobento\Service\Translation\Translator;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\MissingTranslationHandler;

$translator = new Translator(
    resources: new Resources(
        new Resource('shop', 'de', [
            'noProducts' => 'Keine Produkte',
            'No items in your shopping bag.' => 'Keine Artikel sind in deinem Warenkorb.',
        ]),
    ),
    modifiers: new Modifiers(),
    missingTranslationHandler: new MissingTranslationHandler(),
    locale: 'en',
    localeFallbacks: ['de' => 'en'],
);

// with dot notation
$translated = $translator->trans(
    message: 'shop.noProducts',
    locale: 'de'
);

var_dump($translated);
// string(14) "Keine Produkte"

// with src parameter
$translated = $translator->trans(
    message: 'No items in your shopping bag.',
    parameters: ['src' => 'shop'],
    locale: 'de'
);

var_dump($translated);
// string(39) "Keine Artikel sind in deinem Warenkorb."
```

## Resources

### Create Resources

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\ResourcesInterface;
use Tobento\Service\Translation\Resource;

$resources = new Resources(
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
        group: 'front',
        priority: 10,
    ),
);

var_dump($resources instanceof ResourcesInterface);
// bool(true)
```

### Add Resources

You may add resources by using the **add** method:

**add resource**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;

$resources = new Resources();

$resources->add(new Resource('*', 'de', [
    'Hello World' => 'Hallo Welt',
]));
```

**add resources**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;

$resources = new Resources();

$resources->add(new Resources(
    new Resource('*', 'de', [
        'Hello World' => 'Hallo Welt',
    ]),
));
```

### Filter Resources

You may use the filter methods returning a new instance.

**filter**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\ResourceInterface;

$resources = new Resources(
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
        group: 'front',
    ),
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
        group: 'back',
    ),    
);

// filter by group:
$resources = $resources->filter(
    fn(ResourceInterface $r): bool => $r->group() === 'front'
);
```

**locale**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;

$resources = new Resources(
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
    ),
    new Resource(
        name: '*', 
        locale: 'de', 
        translations: ['Hello World' => 'Hallo Welt'],
    ),    
);

// filter by locale:
$resources = $resources->locale('en');
```

**locales**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;

$resources = new Resources(
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
    ),
    new Resource(
        name: '*', 
        locale: 'de', 
        translations: ['Hello World' => 'Hallo Welt'],
    ),    
);

// filter by locales:
$resources = $resources->locales(['en', 'de']);
```

**name**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;

$resources = new Resources(
    new Resource(
        name: 'shop', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
    ),
    new Resource(
        name: 'shop', 
        locale: 'de', 
        translations: ['Hello World' => 'Hallo Welt'],
    ),    
);

// filter by name:
$resources = $resources->name('shop');
```

### Sort Resources

**sort by priority**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;

$resources = new Resources(
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
        priority: 10,
    ),
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
        priority: 15,
    ),    
);

// sort by priority:
$resources = $resources->sort();
```

**sort by callback**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\ResourceInterface;

$resources = new Resources(
    new Resource(
        name: 'users', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
        priority: 10,
    ),
    new Resource(
        name: 'shop', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
        priority: 15,
    ),    
);

// sort by name:
$resources = $resources->sort(
    fn(ResourceInterface $a, ResourceInterface $b): int => $a->name() <=> $b->name()
);
```

### Get Resources / Translations

**all**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\ResourceInterface;

$resources = new Resources(
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
    ),  
);

foreach($resources->all() as $resource) {
    var_dump($resource instanceof ResourceInterface);
    // bool(true)
    
    var_dump($resource->name());
    // string(1) "*"
    
    var_dump($resource->locale());
    // string(2) "en"
    
    var_dump($resource->group());
    // string(7) "default"
    
    var_dump($resource->priority());
    // int(0)
    
    var_dump($resource->translations());
    // array(1) { ["Hello World"]=> string(10) "Hallo Welt" }
}
```

**translations**

```php
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;

$resources = new Resources(
    new Resource(
        name: '*', 
        locale: 'en', 
        translations: ['Hello World' => 'Hallo Welt'],
        priority: 10,
    ),
);

$translations = $resources->locale('en')->translations();
/*Array (
    [Hello World] => Hallo Welt
)*/
```

> :warning: **You must call locale() or locales() before all() or translations() method if you have added (sub or lazy) resources, otherwise they will not get created.**

```php
foreach($resources->locale('en')->all() as $resource) {
    var_dump($resource instanceof ResourceInterface);
    // bool(true)
}
```

## Files Resources

### Create Files Resources

```php
use Tobento\Service\Translation\FilesResources;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Translation\ResourcesInterface;

$resources = new FilesResources(
    (new Dirs())->dir(dir: 'private/trans/', group: 'front', priority: 10)
);

var_dump($resources instanceof ResourcesInterface);
// bool(true)
```

### Directory Structure

Files starting with the locale are stored as ```*``` resource name. They are all fetched and merged together on the first translations request.\
Files not starting with the locale are only loaded on the first resource request. Furthermore, files named like ```routes.shop.json``` and ```routes.blog.json``` are merged together as resource name ```routes```.

```
private/
    trans/
        en/
            en.php
            en.json
            en-shop.json
            shop.json
            routes.shop.json
            routes.blog.json
        de-CH/
            de-CH.json
            de-CH-shop.json
            shop.json
            routes.shop.json
```

### Supported Files

Currently supported files are json and php.

**json**

```json
{
    "Using Real Message": "Using Real Message",
    "usingKeywordMessage": "Using Keyword Message"
}
```

**php**

```php
return [
    'Using Real Message' => 'Using Real Message',
    'usingKeywordMessage' => 'Using Keyword Message',
];
```

### Supporting Other Files

You may support others files by providing your own resource factory:

```php
use Tobento\Service\Translation\FilesResources;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Translation\ResourceFactory;
use Tobento\Service\Translation\ResourceInterface;
use Tobento\Service\Filesystem\File;

class CustomResourceFactory extends ResourceFactory
{
    /**
     * Create a new Resource from file.
     *
     * @param string|File $file
     * @param string $locale
     * @param string $group
     * @param int $priority
     * @return ResourceInterface
     */    
    public function createResourceFromFile(
        string|File $file,
        string $locale,
        string $group = 'default',
        int $priority = 0,
    ): ResourceInterface {
        
        // Create your custom resource for the specific file extension
        
        // Otherwise use parent
        return parent::createResourceFromFile($file, $locale, $group, $priority);
    }
}

$resources = new FilesResources(
    (new Dirs())->dir(dir: 'private/trans/', group: 'front', priority: 10),
    new CustomResourceFactory()
);
```

## Modifiers

**Create Modifiers**

```php
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\ModifiersInterface;
use Tobento\Service\Translation\Modifier\ParameterReplacer;

$modifiers = new Modifiers(
    new ParameterReplacer(),
);

var_dump($modifiers instanceof ModifiersInterface);
// bool(true)
```

**Add Modifier**

```php
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\Modifier\ParameterReplacer;

$modifiers = new Modifiers();
$modifiers->add(new ParameterReplacer());
```

**Get all modifiers**

```php
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\Modifier\ParameterReplacer;
use Tobento\Service\Translation\ModifierInterface;

$modifiers = new Modifiers(new ParameterReplacer());

$allModifiers = $modifiers->all();
// array<int, ModifierInterface>
```

**Modify message**

```php
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\Modifier\ParameterReplacer;

$modifiers = new Modifiers(
    new ParameterReplacer(),
);

[$message, $parameters] = $modifiers->modify(
    message: 'Hi :name',
    parameters: [':name' => 'John'],
);

var_dump($message);
// string(7) "Hi John"
```

### Pluralization

```php
use Tobento\Service\Translation\Modifier\Pluralization;

$modifier = new Pluralization(key: 'count');

[$message, $parameters] = $modifier->modify(
    message: 'There is one apple|There are many apples',
    parameters: ['count' => 5],
);

var_dump($message);
// string(21) "There are many apples"

[$message, $parameters] = $modifier->modify(
    message: 'There is one apple|There are many apples',
    parameters: ['count' => 1],
);

var_dump($message);
// string(18) "There is one apple"
```

### Parameter Replacer

```php
use Tobento\Service\Translation\Modifier\ParameterReplacer;

$modifier = new ParameterReplacer();

[$message, $parameters] = $modifier->modify(
    message: 'Hi :name',
    parameters: [':name' => 'John'],
);

var_dump($message);
// string(7) "Hi John"
```

## Missing Translation Handler

You may add a logger to log missing messages:

```php
use Tobento\Service\Translation\Translator;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\MissingTranslationHandler;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('name');
$logger->pushHandler(new StreamHandler('path/to/your.log', Logger::WARNING));

$translator = new Translator(
    new Resources(
        new Resource('*', 'de', [
            'Hello World' => 'Hallo Welt',
        ]),
    ),
    new Modifiers(),
    new MissingTranslationHandler($logger), // any PSR-3 logger
);

var_dump($translator->trans('Hello World'));
```
# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)