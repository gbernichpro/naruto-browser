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
*   **Servidor:** Apache/Nginx (Pronto para WAMP/XAMPP)

---

## 🚀 Instalação e Configuração

### Pré-requisitos
*   Um servidor web (Apache/Nginx)
*   PHP 8.0 ou superior
*   Banco de Dados MySQL

### Passos

1.  **Clonar o Repositório**
    ```bash
    git clone https://github.com/gbernichpro/naruto-browser.git
    ```

2.  **Configuração do Banco de Dados**
    *   Crie um banco de dados, geralmente chamado `naruto` ou `naruto_game`.
    *   Importe o arquivo SQL fornecido na pasta `_sql/` ou na raiz (se disponível).

3.  **Configuração**
    *   Navegue até o diretório `_inc/`.
    *   Edite o arquivo `conexao.php` com as credenciais do seu banco de dados:
        ```php
        $db = mysqli_connect("localhost", "root", "senha", "nome_do_banco");
        ```

4.  **Rodar o Jogo**
    *   Coloque a pasta do projeto na raiz do seu servidor web (ex: `www` ou `htdocs`).
    *   Acesse pelo navegador: `http://localhost/Naruto`

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
