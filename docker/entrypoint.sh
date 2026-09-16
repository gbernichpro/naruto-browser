#!/bin/sh
# =============================================================================
#  Entrypoint do container do Naruto MMORPG
# =============================================================================
#  Roda antes do Apache subir. Existe por causa dos volumes: um volume montado
#  em cima de _cache/, uploads/ ou reports/ chega vazio e pertencendo ao root,
#  apagando o chown feito no build. Sem este ajuste o jogo sobe sem conseguir
#  gravar cache, avatar nem relatorio de batalha.
# =============================================================================

set -e

for dir in _cache uploads reports; do
    mkdir -p "/var/www/html/${dir}"
    # Um volume ja populado por deploy anterior nao precisa de chown recursivo
    # (seria lento com muitos arquivos); so garante o dono do diretorio.
    chown www-data:www-data "/var/www/html/${dir}" 2>/dev/null || true
    chmod 775 "/var/www/html/${dir}" 2>/dev/null || true
done

# Aviso util no log quando o jogo sobe sem configuracao de banco: sem isso a
# primeira visita so mostra um redirect para /install/ sem explicacao.
if [ -z "${DB_NAME}" ] && [ ! -f /var/www/html/.env ]; then
    echo "[naruto] DB_NAME nao definido e .env ausente: abra /install/ para configurar." >&2
fi

exec "$@"
