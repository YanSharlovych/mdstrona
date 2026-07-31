#!/bin/sh

set -eu

cd /var/www/html

until [ -f wp-config.php ] && wp core version --allow-root >/dev/null 2>&1; do
  echo "Waiting for WordPress files..."
  sleep 3
done

if ! wp core is-installed --allow-root >/dev/null 2>&1; then
  wp core install \
    --url="${WP_URL}" \
    --title="${WP_TITLE}" \
    --admin_user="${WP_ADMIN_USER}" \
    --admin_password="${WP_ADMIN_PASSWORD}" \
    --admin_email="${WP_ADMIN_EMAIL}" \
    --skip-email \
    --allow-root
fi

if [ "$(wp theme list --status=active --field=name --allow-root)" != "aluteco" ]; then
  wp theme activate aluteco --allow-root
fi

wp eval-file /var/www/html/wp-content/themes/aluteco/inc/bootstrap-content.php --allow-root
wp rewrite structure '/%postname%/' --allow-root

echo "ALUTECO WordPress setup complete."
