# 🍥 Naruto MMORPG Browser Game

![Naruto MMORPG Banner](template/topo.png)

> **Uma migração completa do clássico jogo de navegador de Naruto, agora atualizado para ambientes modernos com PHP 8.**

## 📖 Sobre o Projeto

Este é um **MMORPG baseado em navegador** ambientado no universo de **Naruto**. Os jogadores criam seu próprio ninja, escolhem uma vila e embarcam em uma jornada para se tornarem os shinobis mais fortes. O jogo possui um sistema de RPG robusto com níveis, missões, batalhas e interação com a comunidade.

Recentemente, este projeto passou por um significativo **esforço de modernização** para garantir compatibilidade com **PHP 8.x**, corrigindo funções obsoletas, otimizando interações com o banco de dados e resolvendo problemas de frontend.

---

## ✨ Principais Recursos

*   **🥋 Progressão de Personagem:** Sistema detalhado de estatísticas (Ninjutsu, Taijutsu, Genjutsu), níveis e exames de graduação (Gennin, Chuunin, Jounin, ANBU, Kage).
*   **🏘️ Sistema de Vilas:** Junte-se a uma das vilas icônicas (Folha, Areia, Névoa, Pedra, etc.) ou torne-se um **Renegado (Akatsuki)**.
*   **⚔️ Sistema de Combate:**
    *   **PvE:** Caçe bestas selvagens, complete missões e lute contra NPCs.
    *   **PvP:** Desafie outros jogadores na arena ou no mundo aberto.
    *   **Guerra:** Guerras de vilas em grande escala e controle de território.
*   **📜 Sistema de Missões:** Centenas de missões classificadas (Rank D a S) e tarefas especiais.
*   **🛡️ Itens e Equipamentos:** Loja, ferreiro e drops raros (Armas Lendárias).
*   **🦅 Organizações:** Crie ou junte-se a clãs/organizações com bases exclusivas e benefícios.
*   **🎓 Academia e Jutsus:** Aprenda centenas de jutsus do anime/mangá.
*   **🐶 Sistema de Pets/Invocação:** Animais domáveis e invocações para ajudar na batalha.

---

## 🛠️ Tecnologias Utilizadas

*   **Backend:** PHP (Originalmente 5.x, agora **Compatível com PHP 8.2+**)
*   **Banco de Dados:** MySQL / MariaDB
*   **Frontend:** HTML5, CSS3, JavaScript (jQuery 1.9.0)
*   **Servidor:** Apache/Nginx (pronto para WAMP/XAMPP e para hospedagem compartilhada)
*   **Dependências:** Composer (phpdotenv, PHPMailer)
*   **Deploy:** Dockerfile + Docker Compose (Apache + PHP 8.3), pronto para Coolify

---

## 🚀 Instalação

O jogo tem um **instalador web**: você não edita nenhum arquivo PHP à mão. Suba os arquivos, abra `/install/` no navegador, informe os dados do MySQL e ele cria o banco, importa as **76 tabelas** de `install/schema.sql`, cria sua conta de administrador e grava o `.env`.

Escolha um dos dois caminhos abaixo.

| | Opção A — XAMPP/WAMP e hospedagem tradicional | Opção B — Docker / Coolify |
|---|---|---|
| **Para quem** | Roda local, ou tem cPanel/Plesk/FTP | Tem VPS própria |
| **Você precisa de** | Apache + PHP 8.0+ + MySQL | Docker (e Coolify, se for usar painel) |
| **Configuração** | Arquivo `.env`, escrito pelo instalador | Variáveis de ambiente no painel |
| **HTTPS** | Depende da hospedagem | Automático (Let's Encrypt) |

---

### Opção A — XAMPP / WAMP / hospedagem tradicional

**1. Coloque os arquivos no servidor**

Local, com XAMPP ou WAMP:

```bash
# Windows (XAMPP): C:\xampp\htdocs\    |  WAMP: C:\wamp64\www\
git clone https://github.com/gbernichpro/naruto-browser.git naruto
```

Em hospedagem compartilhada, envie o conteúdo do projeto para `public_html/` (ou `www/`) por FTP ou pelo gerenciador de arquivos do painel.

> A pasta `vendor/` já vem no repositório justamente para esse caso: sem SSH você não conseguiria rodar o Composer. Se **tiver** SSH, prefira rodar `composer install` para pegar as versões travadas no `composer.lock`.

**2. Crie o banco (opcional)**

O instalador cria o banco sozinho se o usuário do MySQL tiver permissão. No XAMPP/WAMP o `root` tem, então pode pular. Em hospedagem compartilhada normalmente você precisa criar o banco pelo painel antes (cPanel → *MySQL Databases*) e anotar nome, usuário e senha.

**3. Rode o instalador**

Abra no navegador:

```
http://localhost/naruto/install/          (XAMPP/WAMP)
https://seudominio.com/install/           (hospedagem)
```

Preencha os dados do MySQL e da conta de administrador e clique em **Instalar agora**. Deixe marcado *"Gravar o arquivo .env na raiz"*.

**4. Feche o instalador**

Assim que terminar, o instalador se tranca sozinho (`_cache/installed.lock`). Para fechar de vez, **apague a pasta `install/` do servidor** — ela não é necessária para o jogo rodar.

**5. Jogue**

```
http://localhost/naruto/
```

#### A.1 — Publicando pelo Git do cPanel (HostGator, Hostinger, etc.)

Se você usa **cPanel → Git Version Control**, o deploy é automatizado pelo arquivo [`.cpanel.yml`](.cpanel.yml) na raiz do projeto. Sem ele o painel mostra *"The system cannot deploy"* e o botão **Deploy HEAD Commit** fica inativo — o arquivo é justamente o roteiro que diz ao cPanel o que fazer depois do `git pull`.

**Antes do primeiro deploy, confira o caminho de destino.** Abra `.cpanel.yml` e ajuste esta linha:

```yaml
- export DEPLOYPATH=/home2/geova330/naruto
```

O valor deve ser a **Raiz do documento** que o cPanel mostra em *Domínios*.

Há dois cenários, e o arquivo cobre os dois sozinho:

* **Clone e pasta pública são o mesmo diretório** (o caso deste servidor). O `git pull` do cPanel já atualiza o site; não há nada para copiar, e o deploy só faz a manutenção das pastas graváveis.
* **Pasta pública é outro diretório** (ex.: o clone fora de `public_html`). O deploy copia os arquivos para lá.

Caminho errado não apaga nada — só publica na pasta errada, e parece que o deploy não funcionou.

**Como o deploy se comporta:**

| Item | O que acontece |
|---|---|
| `.env` | **Nunca é sobrescrito.** Ele guarda a senha do banco de produção e vive só no servidor |
| `uploads/`, `reports/`, `_cache/` | Preservados — são dados de jogador |
| `.htaccess` | Copiado (o deploy usa `rsync`, não `cp -R *`, que ignoraria arquivos com ponto) |
| `Dockerfile`, `docker/`, `.git` | Não vão para a pasta pública |

**Os dois requisitos do cPanel para o botão Deploy funcionar:**

1. O `.cpanel.yml` existe na branch em que o repositório está (`main`, normalmente).
2. Não há alterações não commitadas no clone do servidor. Se o painel reclamar disso, veja se alguém editou algum arquivo direto no servidor — tipicamente o `.env`, que **nesta versão saiu do versionamento** justamente para parar de travar deploys.

> ⚠️ **Se você está publicando esta versão por cima de um jogo que já roda:** o `.env` do seu servidor é anterior a ela e não tem as chaves novas. Nada quebra — todas têm padrão seguro — mas vale adicionar `INSTALLER_ENABLED=false`, `APP_DEBUG=false` e `APP_TIMEZONE=America/Sao_Paulo`. Mesmo sem elas, o instalador se fecha sozinho ao detectar que o banco já tem as tabelas do jogo.

**Verifique a versão do PHP.** Em cPanel → *MultiPHP Manager*, o domínio precisa estar em **PHP 8.0 ou superior**. Em PHP 7.x o jogo não sobe.


<details>
<summary><strong>Problemas comuns nessa opção</strong></summary>

| Sintoma | Causa |
|---|---|
| Página em branco ou erro 500 | Defina `APP_DEBUG=true` no `.env` para ver o erro. **Volte para `false` depois** — com `true` o PHP expõe caminhos do servidor. |
| `Database Connection Failed` | Host errado. Em hospedagem compartilhada quase nunca é `localhost`: veja no painel (costuma ser `mysql.seudominio.com` ou um IP). |
| Banco em porta diferente de 3306 | Preencha o campo **Porta** no instalador, ou use `DB_PORT` no `.env`. |
| Avatares não salvam | `uploads/` precisa de permissão de escrita (`chmod 775`). |
| Instalador diz "já foi instalado" | Apague `_cache/installed.lock` ou defina `INSTALLER_ENABLED=true`. |

</details>

---

### Opção B — Docker / Coolify (VPS)

#### B.1 — Local, com Docker Compose

```bash
git clone https://github.com/gbernichpro/naruto-browser.git
cd naruto-browser
cp .env.example .env          # preencha ao menos DB_PASS e DB_ROOT_PASS
docker compose up -d --build
```

Abra `http://localhost:8080/install/`. No formulário use:

* **Host:** `db` (o nome do serviço no `docker-compose.yml`, não `localhost`)
* **Porta:** `3306`
* **Usuário / senha / banco:** os mesmos `DB_USER`, `DB_PASS` e `DB_NAME` do seu `.env`
* **Desmarque** *"Gravar o arquivo .env na raiz"* — em container a configuração vem das variáveis de ambiente

#### B.2 — Coolify na sua VPS

1. **Coolify → New Resource → Docker Compose**, apontando para este repositório. Ele usa o `docker-compose.yml` da raiz (app + MariaDB + volumes, tudo junto).
   *Se preferir separar,* crie um banco MariaDB pelo próprio Coolify e use **Dockerfile** como build pack para o app, apontando `DB_HOST` para o host interno que o Coolify mostrar.

2. **Remova a seção `ports:`** do serviço `app` — quem publica a porta é o proxy do Coolify.

3. Em **Environment Variables**, preencha:

   ```
   DB_NAME=naruto
   DB_USER=naruto
   DB_PASS=<senha forte>
   DB_ROOT_PASS=<outra senha forte>
   GAME_NAME=Fight
   INSTALLER_ENABLED=true
   INSTALL_TOKEN=<string aleatória longa>
   ```

   `DB_HOST=db` e `DB_PORT=3306` já vêm do compose.

4. **Configure os volumes persistentes** — este é o passo que mais se esquece. Sem eles, **avatares enviados, relatórios de batalha e a trava do instalador somem a cada redeploy**:

   | Caminho no container | Guarda |
   |---|---|
   | `/var/www/html/uploads` | imagens enviadas por jogadores |
   | `/var/www/html/reports` | relatórios de batalha (`.txt`) |
   | `/var/www/html/_cache` | cache e trava de instalação |
   | `/var/lib/mysql` (no serviço `db`) | **o jogo inteiro** |

5. Aponte seu domínio no campo **Domains** e faça o deploy. O Coolify emite o certificado Let's Encrypt sozinho.

6. Abra `https://seudominio.com/install/?token=<INSTALL_TOKEN>` e instale, com **Host `db`** e a opção de gravar `.env` desmarcada.

7. **Depois de instalar**, troque `INSTALLER_ENABLED` para `false` e faça redeploy.

<details>
<summary><strong>Problemas comuns nessa opção</strong></summary>

| Sintoma | Causa |
|---|---|
| `Database Connection Failed` logo no primeiro deploy | O MariaDB ainda está subindo. O `healthcheck` do compose já segura o app; se você separou os serviços no Coolify, é só aguardar e recarregar. |
| Jogo funciona, mas some tudo no redeploy | Faltam os volumes do passo 4. |
| Todo jogador aparece com o mesmo IP | O proxy não está mandando `X-Forwarded-For`. O `mod_remoteip` já vem configurado em `docker/apache-naruto.conf`. |
| Container marcado como *unhealthy* | `health.php` responde 503 quando o banco não responde. Cheque as variáveis `DB_*`. |

</details>

---

### ⚙️ Variáveis de configuração

Todas funcionam tanto no arquivo `.env` quanto como variável de ambiente do painel. O modelo completo e comentado está em [`.env.example`](.env.example).

| Variável | Padrão | Para que serve |
|---|---|---|
| `DB_HOST` `DB_PORT` `DB_NAME` `DB_USER` `DB_PASS` | — | Conexão com o MySQL |
| `GAME_NAME` | `Fight` | Nome do servidor, exibido no título e nas mensagens |
| `APP_DEBUG` | `false` | `true` mostra erros na tela. **Nunca deixe ligado em produção** |
| `APP_TIMEZONE` | `America/Sao_Paulo` | Fuso dos timers de missão, treino, VIP e penalidade |
| `DB_LEGACY_SQL_MODE` | `true` | Relaxa o modo estrito do MySQL, que recusa as datas `0000-00-00` do schema de 2013 |
| `INSTALLER_ENABLED` | `true` | `false` fecha o instalador de forma absoluta — nem token abre |
| `INSTALL_TOKEN` | vazio | Senha de acesso ao instalador. É o único jeito de reabri-lo depois de instalado |
| `SMTP_*`, `MAIL_FROM_*` | vazio | Envio de e-mail (recuperação de senha) |
| `TURNSTILE_*` | vazio | Captcha do cadastro. Vazio = desligado |
| `DISCORD_WEBHOOK_URL` | vazio | Recebe notificação dos erros de PHP |

---

### 🔒 Como o instalador se protege

Um formulário público que recebe credenciais de MySQL e executa SQL é um alvo. Por isso `/install/` se fecha sozinho de **três** formas independentes:

1. A trava em disco `_cache/installed.lock`, gravada ao fim da instalação.
2. `INSTALLER_ENABLED=false` no ambiente.
3. **Detecção automática:** se o banco configurado já tem a tabela `usuarios`, o instalador se recusa a abrir — mesmo sem trava e sem a variável. É o que protege quem publica esta versão por cima de um jogo que já está no ar, cujo `.env` antigo não tem `INSTALLER_ENABLED`.

Quando fechado, ele não mostra o formulário nem revela host, usuário ou nome do banco.

### 🔄 Reinstalar ou resetar o jogo

> ⚠️ `install/schema.sql` começa com `DROP TABLE` nas 76 tabelas. Reinstalar em um banco com jogo ativo **apaga tudo**. O instalador exige confirmação explícita quando detecta tabelas existentes — leia o aviso vermelho antes de marcar.

Como a detecção automática mantém o instalador fechado, apagar a trava não basta. Defina um token no `.env`:

```bash
INSTALL_TOKEN=uma-string-aleatoria-bem-longa
```

E abra `/install/?token=uma-string-aleatoria-bem-longa`.

O token vence a trava em disco e a detecção automática, mas **não** vence `INSTALLER_ENABLED=false` — essa continua sendo o botão de emergência para fechar tudo.

---

## 🔧 Atualizações Recentes (Refatoração)

Revivemos este código legado com as seguintes correções:

*   ✅ **Migração para PHP 8:** Substituição de funções `mysql_*` obsoletas por um shim `mysqli` personalizado (`_inc/mysqli_shim.php`) e atualização de `ereg` para `preg_match`.
*   ✅ **Segurança:** Implementação de `anti_sql_injection` e manipulação de sessão mais segura.
*   ✅ **Modernização do Frontend:** Atualização do jQuery para v1.9.0, correção de bibliotecas conflitantes e resolução de erros de sintaxe JS.
*   ✅ **Correção de Assets:** Restauração de caminhos perdidos e supressão de erros 404 para arquivos ausentes.

---

## 🤝 Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para verificar a aba [Issues](https://github.com/gbernichpro/naruto-browser/issues) ou enviar um Pull Request.

1.  Faça um Fork do projeto
2.  Crie sua Branch de Feature (`git checkout -b feature/MinhaFeatureIncrivel`)
3.  Faça o Commit de suas mudanças (`git commit -m 'Adiciona alguma Feature Incrível'`)
4.  Faça o Push para a Branch (`git push origin feature/MinhaFeatureIncrivel`)
5.  Abra um Pull Request

---

## 📜 Licença

Este projeto é destinado a fins educacionais. Todos os personagens e ativos de Naruto são direitos autorais de **Masashi Kishimoto**.

**Mantido por [G. Bernich](https://github.com/gbernichpro)**
