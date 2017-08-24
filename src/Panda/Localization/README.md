# Panda Localization Package

This is the localization package of the Panda Platform. It provides a simple translation structure and process for your code.

[![StyleCI](https://styleci.io/repos/69765487/shield?branch=master)](https://styleci.io/repos/69765487)

## Installation

This package is part of the [Panda Framework](https://github.com/PandaPlatform/panda-framework) but it's also available as a single package.

### Through the composer

Add the following line to your `composer.json` file:

```
"panda/l10n": "^2.0"
```

## Usage

The Translation class works using a given `FileProcessor` for the translation files. It uses the `FileProcessor` as a getter to get translations.

Each Processor can have its own implementation of handling files based on locale and packages.

### Translator

The Translator interface can be used across the entire application. You have to define the application's `FileProcessor` first and then you can load your translations freely.

Example:

```php

use Panda\Localization\Translation\JsonProcessor;
use Panda\Localization\Translator;

// Initialize the desired processor
$processor = new JsonProcessor('your_base_directory_for_translations');

// Initialize the Translator
$translator = new Translator($processor);

// Get a desired translation
$translation = $translator->translate('translation-key', 'package-name', 'en_US', 'default-translation-value-if-empty');
```

### JsonProcessor

When using the `JsonProcessor`, the translation files should be in the given structure:

```
BASE_DIRECTORY/{locale}/{package}.json
```

If no package name is given (or an empty package name), the 'default' package will be used (`default.json`).
