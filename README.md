# Zend Viewhelper Adapter for Plates integration for Expressive

Provides integration of [Zend-View-Helpers](https://docs.zendframework.com/zend-view/helpers/intro/) with [Plates](http://platesphp.com/) for
[Expressive](https://github.com/zendframework/zend-expressive).

## Installation

Install this library using composer:

```bash
$ composer require fduarte42/zend-expressive-plates-viewhelper
```

We recommend using a dependency injection container, and typehint against
[container-interop](https://github.com/container-interop/container-interop). We
can recommend the following implementations:

- [zend-servicemanager](https://github.com/zendframework/zend-servicemanager):
  `composer require zendframework/zend-servicemanager`
- [pimple-interop](https://github.com/moufmouf/pimple-interop):
  `composer require mouf/pimple-interop`
- [Aura.Di](https://github.com/auraphp/Aura.Di)

