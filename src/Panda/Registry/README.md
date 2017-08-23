# Registry
[READ-ONLY] Subtree split of the Panda Registry Package

- [Introduction](#introduction)
- [Registry Structure](#registry-structure)
    - [Normal Registry](#normal-registry)
    - [Shared Registry](#shared-registry)

## Introduction

Panda Registry is one of the main packages being used across the framework to facilitate the storage of items in key-value pairs.

## Registry Structure 

The current registry implementation is able to store items in runtime and be accessed as long as the application is running.

### Normal Registry

The normal registry implementation is enclosed in a `Registry` object which implements a `RegistryInterface`.

This object can store items as long as the object is alive. When the object is destroyed, all items are lost.

### Shared Registry

The shared registry implementation is common/shared registry that is being created during runtime and resides in memory as long as the application is live and running.
You can use the SharedRegistry implementation when you need to store statically accessible values without the need of storing the registry object in memory.

At the same time, shared registry consumers can **share** the registry without having to call different objects and turning your code into spaghetti.
