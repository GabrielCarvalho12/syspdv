# Persona do Projeto
A Lucia tem um pequeno comércio e luta bravamente para mantê-lo! Porém, a Lucia tem problemas em registrar suas vendas!
Já usou papel, planilhas e até mesmo outros sistemas complicados!
Será que o SysPDV conseguirá ajudar às várias Lucias espalhadas pelo nosso Brasil?
Este é o nosso foco e faremos o possível para alcançá-lo!

# Módulos

|                                                            |     |
| ---------------------------------------------------------- | --- |
| Login no Sistema e Recuperação de Senha                    | ✔   |
| Cadastro de Usuários                                       | ✔   |
| Cadastro de Produtos                                       | ✔   |
| Cadastro de Clientes                                       | ✔   |
| PDV Padrão                                                 | ✔   |
| PDV Diferencial (PDV com mais recursos)                    | ✔   |
| Relatórios de vendas                                       | ✔   |
| Dashboard                                                  | ✔   |
| Pedidos (Descontinuado)                                    |     |
| Exportar relatórios para PDF                               | ✔   |
| Exportar relatórios para Excel                             | ✔   |
| Logs de Acessos                                            | ✔   |

# Instalação

Copie o arquivo `.env.example` para `.env`\
Estas são as configurações padrões do arquivo.

Configure os valores de acordo com o seu banco de dados, email (caso for utilizar), etc.

```dotenv
APP_ENV=local
APP_DISPLAY_ERRORS=true
TIMEZONE=America/Sao_Paulo
HTTPS=false

DB_NAME=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_DBNAME=zig

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=

CONTAINER_NAME_API=api_zig
PORT_API=8098

```

> Você precisa do composer instalado em sua máquina!

Entre na pasta do projeto via linha de comando e execute o comando para baixar as dependências do projeto!

```shell
composer install
```

#### Configuração do banco de dados

1. Crie um banco de dados;
2. Edite o arquivo `.env` na raiz do projeto;
3. Dê o seguinte comando para migrar o banco.

```shell
php command migrate
```

**AVISO**: Não se esqueça de rodar o comando abaixo para gerar uma nova migration todas às vezes que alterar o banco de
dados:

```shell
php command create migration [descreva as mudanças]
```

Entre no diretório que será exibido no console, ou vá até o último arquivo do diretório ´./dump/migrations/´.

Caso se trate de uma nova tabela você deverá buscar pelo `sql` da criação da tabela.

No `phpmyadmin`, por exemplo, você deve abrir a tabela > Export > dump all rows
(caso só precise da estrutura e não dos dados) > Go.

Salve o conteúdo dentro da migration que acabou de criar. Quando der a opção de salvar, vá até o diretório onde estão as
migrations > duplo clique no arquivo > substituir e pronto!

Caso se trate de uma nova coluna ou edição de uma existente, você verá que logo após a edição o `phpmyadmin` exibirá o
comando executado, como o exemplo:

```sql
ALTER TABLE `clientes`
CHANGE `telefone`
    VARCHAR(50) CHARACTER SET latin1
    COLLATE latin1_swedish_ci NULL DEFAULT NULL;
```

Cole este valor dentro da migration que acabou de criar e pronto!

#### Execute o projeto

Depois, rode o servidor php.

```shell
php -S localhost:8000
```

Você também pode executar um container Docker com o comando:\

```shell
docker-compose up -d
```

# Usuário de teste

Email: admin@admin.com \
Password: 33473347
