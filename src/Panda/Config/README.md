# Configuration

> **[READ-ONLY]** Subtree split of the Panda Config Package

- [Introduction](#introduction)
- [Shared Configuration](#shared-configuration)
- [Configuration parsers](#configuration-parsers)
- [Accessing Configuration Values](#accessing-configuration-values)

## Introduction

All of the configuration files for the Panda framework are stored in the `config` directory. The main configuration file is named `config-default` followed by the extension according to the configuration type.

The main configuration file should include all the necessary config values for all the different services or features of your application.

## Shared Configuration

The configuration values are being loaded in a common/shared configuration that is accessible during runtime at any time without the access to the configuration object.

Shared configuration is a sub-set of the shared registry and it can keep its values during runtime across different objects.

## Configuration parsers

The default configuration parser for the configuration files is the `\Panda\Config\Parsers\JsonParser` which support json files.

The job of the parsers (from the `config` package) is to parse the configuration files and return an array of their values.

## Accessing Configuration Values

You may easily access your configuration values using the `SharedConfiguration` object.
The configuration values may be accessed using "dot" syntax, which is being translated in encapsulated groups of values.
A default value may also be specified and will be returned if the configuration option does not exist:

```php
use \Panda\Config\SharedConfiguration;

$config = new SharedConfiguration();
$value = $config->get('routes.base_dir', $default = null);
```

You can also set configuration values at runtime:

```php
use \Panda\Config\SharedConfiguration;

$config = new SharedConfiguration();
$config->set('logger.enabled', false);
```
