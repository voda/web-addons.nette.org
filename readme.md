Nette Addons Portal
===================


Installation
------------

**Run in console**

```
# Clone repository
git clone git://github.com/nette/addons.nette.org.git
cd addons.nette.org

# Install dependencies via Composer
composer install

# Create local config
cp ./app/config/config.local.template.neon ./app/config/config.local.neon

# Edit your local configuration (use your favorite editor)
nano ./app/config/config.local.neon
```

**Set up database**

1. Open Adminer (or whatever tool you use)
2. Create database
3. Import `app/model/db/schema.sql`, `app/model/db/data.sql` and `app/model/db/schema-triggers.sql` (in this order)


Tests
-----
You can run tests via CLI `./tests/run-tests.sh tests/cases`.

[![Build Status](https://travis-ci.org/nette/web-addons.nette.org.png?branch=master)](https://travis-ci.org/nette/web-addons.nette.org)
