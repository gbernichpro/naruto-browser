# =============================================================================
#  Naruto MMORPG Browser Game
#  Imagem de producao: Apache + PHP 8.3 (mod_php)
# =============================================================================
#  O jogo e PHP procedural servido direto da raiz, sem front controller e sem
#  build de assets. Apache com mod_php e o alvo mais proximo do ambiente onde
#  o codigo nasceu (WAMP/XAMPP), o que evita diferencas de comportamento com
#  .htaccess e caminhos relativos.
# =============================================================================

FROM php:8.3-apache

# --- Dependencias de sistema e extensoes PHP -------------------------------
#  mysqli   : obrigatoria (todo o acesso a banco passa pelo shim mysql_*)
#  mbstring : PHPMailer
#  opcache  : o jogo inclui centenas de arquivos por request
#  curl/openssl ja vem compilados na imagem oficial.
RUN apt-get update && apt-get install -y --no-install-recommends \
        libonig-dev \
        default-mysql-client \
    && docker-php-ext-install -j"$(nproc)" mysqli mbstring opcache \
    && apt-get purge -y --auto-remove libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# --- Apache ----------------------------------------------------------------
#  remoteip: sem isso, todo jogador chega com o IP do proxy do Coolify, e o
#  jogo usa IP para antifraude, ban e controle de multiconta.
RUN a2enmod rewrite headers remoteip expires
COPY docker/apache-naruto.conf /etc/apache2/conf-available/naruto.conf
RUN a2enconf naruto

COPY docker/php.ini /usr/local/etc/php/conf.d/naruto.ini

WORKDIR /var/www/html

# --- Dependencias PHP ------------------------------------------------------
#  Camada separada: so refaz o composer install quando o composer.json muda.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader \
    && rm -rf /root/.composer

# --- Codigo da aplicacao ---------------------------------------------------
COPY . .

#  Diretorios gravaveis. Em Coolify monte _cache, uploads e reports como
#  volume: sem isso, avatares enviados, relatorios de batalha e a trava do
#  instalador somem a cada redeploy.
RUN mkdir -p _cache uploads reports \
    && chown -R www-data:www-data _cache uploads reports \
    && chmod -R 775 _cache uploads reports

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD php -r '$c=@file_get_contents("http://127.0.0.1/health.php"); exit(trim((string)$c)==="ok"?0:1);'

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
