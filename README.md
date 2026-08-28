# Watchline

Legacy Watchline webshop based on OpenCart 2.3.0.2 with VQMod/OCMod.

## Local development

Requirements:

- PHP 7.4 with `mysqli`, `gd`, `curl`, `mbstring`, `soap`, `zip` and XML extensions
- MySQL 8 or a compatible MariaDB version
- Laravel Herd, Valet or an equivalent local web server

For Herd, place the project in a parked directory as `watchline`. The included
`LocalValetDriver.php` maps OpenCart SEO URLs to `_route_`, so the local address
is `http://watchline.test`.

1. Create a clean local database and import the production dump outside Git.
2. Copy `config.php.example` to `config.php` and
   `admin/config.php.example` to `admin/config.php`, then set local database
   credentials.
3. Restore `image/` separately if product images are needed. It is shared
   deployment data and is intentionally ignored by Git.
4. Ensure `system/storage/*`, `system/cache_mfp`, `vqmod/vqcache` and
   `vqmod/logs` are writable by PHP.

The database dump, real configuration files, images, generated modifications,
uploads, downloads, logs and caches must never be committed.

Optional PayPal platform onboarding reads its OAuth credentials from
`PAYPAL_PLATFORM_SANDBOX_CLIENT_ID`, `PAYPAL_PLATFORM_SANDBOX_CLIENT_SECRET`,
`PAYPAL_PLATFORM_LIVE_CLIENT_ID` and `PAYPAL_PLATFORM_LIVE_CLIENT_SECRET`.
The CKEditor Leaflet plugin expects its Google Maps key through
`leaflet_maps_google_api_key`; neither integration has a built-in key.

## Production deployment

Production keeps these files/directories outside Git:

- `.htaccess`
- `config.php` and `admin/config.php`
- `image/`
- `webstanje.csv`
- `system/storage/` runtime contents
- VQMod caches and logs

Before connecting an existing production directory to this repository, take a
filesystem and database backup and verify the ignored files above exist. The
first deployment must compare the existing tree with `origin/main`; do not run
`git clean`, because it would remove shared production data. Later deployments
can use `git pull --ff-only origin main` after a clean status check.

`test.php` and `gath.php` are intentionally excluded because the production
copies contain hard-coded integration credentials and debug output.
