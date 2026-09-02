# Weblizards Dynamic Dropdown Bundle

## Installation

```bash
composer require weblizards/dynamic-dropdown-bundle
```

Anschließend das Bundle in Pimcore aktivieren.
Hierzu folgende Zeile in `config/bundles.php` hinzufügen:

```php
return [
    // ...
    \Weblizards\DynamicDropdownBundle\WeblizardsDynamicDropdownBundle::class => ['all' => true],
];
```

Assets installieren:
```bash
bin/console assets:install # --symlink --relative
```