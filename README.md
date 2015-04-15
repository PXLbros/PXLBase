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

## Usage

### Layout Variables

#### $page_title
Page title

#### $meta_description
Meta description

#### $css_includes
<link>'s with included CSS-files

#### $js_includes
<script>'s with included JS-files

#### $inline_js
JS variables

#### $content
Page content

#### $current_page
Unique identifier of current page