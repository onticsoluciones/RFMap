# Installation instructions

After cloning the repository, download Composer:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Install the dependencies:

```bash
php composer.phar install
```

Copy phinx.yml.dist to phinx.yml:

```bash
cp phinx.yml.dist phinx.yml
```

You can edit the database path if desired, by default it will be created in:

```
./data/rfmap.sqlite
```

Run the schema migration tool to create the database:

```bash
vendor/bin/phinx migrate
```
