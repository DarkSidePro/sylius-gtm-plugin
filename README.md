# Sylius Google Tag Manager Plugin

Integracja Google Tag Manager (GTM) z Sylius 2.x. Plugin umożliwia automatyczne wysyłanie eventów e-commerce do dataLayer GTM, takich jak:
- add_to_cart
- remove_from_cart
- view_item
- view_item_list
- add_payment_info
- begin_checkout
- purchase

i inne zgodne z Enhanced Ecommerce.

## Instalacja

1. Zainstaluj plugin przez Composer:

```bash
composer require darksidepro/sylius-gtm-plugin
```

**Uwaga:** Plugin automatycznie rejestruje się dzięki Symfony Flex (jeśli jest dostępny). W przeciwnym przypadku musisz ręcznie dodać do pliku `config/bundles.php`:

```php
<?php

return [
    // ... inne bundles
    DarkSidePro\SyliusGtmPlugin\DarkSideProSyliusGtmPlugin::class => ['all' => true],
];
```

2. Dodaj do pliku `.env`:

```env
GTM_CONTAINER_ID=GTM-XXXXXXX
```

3. Zaimportuj szablony GTM w swoim layoutcie:

W `<head>`:
```twig
{% include '@SyliusGtm/Gtm/head.html.twig' with { gtm_container_id: gtm_config_provider.getContainerId() } %}
```

Zaraz po otwarciu `<body>`:
```twig
{% include '@SyliusGtm/Gtm/body.html.twig' with { gtm_container_id: gtm_config_provider.getContainerId() } %}
```

4. Upewnij się, że serwis `GtmConfigProvider` jest dostępny w Twig (np. przez własny TwigExtension lub globalną zmienną).

5. Plugin automatycznie obsługuje eventy e-commerce i przekazuje je do dataLayer przez flashBag/session.

## Tłumaczenia
Pliki tłumaczeń znajdują się w `src/Resources/translations/`.

## Testy
Testy jednostkowe znajdują się w katalogu `tests/`. Uruchomisz je przez:

```
vendor/bin/phpunit
```

## Wsparcie dla Sylius Plus
Plugin wspiera wielosklepowość i jest zgodny z Sylius Plus.

## Licencja
MIT
