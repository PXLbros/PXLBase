# pxlFramework

## Get Started

### Installation

1. Install a clean Laravel
2. Create `.env` file in root:
```
APP_ENV=local
APP_DEBUG=true
```

2. Run `php artisan fresh`
2. Add `pxlbros/pxlframework` to `composer.json` and run `composer update`
3. Add service provider `PXLBros\PXLFramework\PXLFrameworkServiceProvider` to `app/bootstrap.php`
4. Run `php artisan vendor:publish --provider="PXLBros\PXLFramework\PXLFrameworkServiceProvider"`  