# 📍 Kamoca/FallbackCepApi

[![Latest Version](https://img.shields.io/github/v/release/KauanCalheiro/fallback-cep-api)](https://github.com/KauanCalheiro/fallback-cep-api/releases)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue.svg)](https://php.net/) 
[![Laravel](https://img.shields.io/badge/laravel-%5E12.20-red.svg)](https://laravel.com/) 
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Downloads](https://img.shields.io/packagist/dt/kamoca/fallback-cep-api)](https://packagist.org/packages/kamoca/fallback-cep-api)

Um pacote Laravel robusto e confiável para consulta de CEP com **fallback automático** entre múltiplos provedores de API. Nunca mais perca uma consulta por falha de API! 🚀

📖 **[Documentação Completa](#)** | 🚀 **[Guia de Instalação](#-instalação)** | 📋 **[Changelog](CHANGELOG.md)**

## ✨ Características

- 🔄 **Fallback automático** entre provedores
- ⚡ **Configuração de prioridades** para otimizar performance
- 🌐 **Múltiplos provedores** suportados (ViaCEP, BrasilAPI)
- 🛠️ **Fácil configuração** via arquivo de config
- 🌍 **Suporte a internacionalização** (PT-BR e EN)
- 📦 **Auto-discovery** do Laravel
- 🧪 **Padronização** de resposta entre provedores

## 📋 Requisitos

[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue.svg)](https://php.net/) 
[![Laravel](https://img.shields.io/badge/laravel-%5E12.20-red.svg)](https://laravel.com/) 
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

### Requisitos Mínimos

- **PHP**: 8.2 ou superior
- **Laravel**: 12.20 ou superior
- **Extensões PHP**: 
  - `curl` (para requisições HTTP)
  - `json` (para processamento JSON)
  - `mbstring` (para manipulação de strings)

### Dependências do Composer

- `illuminate/support`: ^12.20
- `illuminate/http`: Incluído no Laravel

### Compatibilidade

| Laravel | PHP     | Status |
|---------|---------|--------|
| 12.x    | 8.2+    | ✅ Suportado |
| 11.x    | 8.1+    | ⚠️ Não testado |
| 10.x    | 8.0+    | ❌ Não suportado |

## 🚀 Instalação

Instale o pacote via Composer:

```bash
composer require kamoca/fallback-cep-api
```

### Publicar Configuração

Publique o arquivo de configuração para personalizar o comportamento:

```bash
php artisan vendor:publish --tag=cep-config
```

Isso criará o arquivo `config/cep.php` em seu projeto.

### Publicar Traduções (Opcional)

Para personalizar as mensagens de erro:

```bash
php artisan vendor:publish --tag=fallback-cep-translations
```

## ⚙️ Configuração

O arquivo `config/cep.php` permite configurar todos os aspectos do pacote:


### Variáveis de Ambiente

Adicione essas variáveis ao seu `.env` para configurar facilmente:

```env
# Configurações do ViaCEP
FALLBACK_CEP_API_VIA_CEP_ENABLED=true
FALLBACK_CEP_API_VIA_CEP_PRIORITY=1

# Configurações do BrasilAPI
FALLBACK_CEP_API_BRASIL_API_ENABLED=true
FALLBACK_CEP_API_BRASIL_API_PRIORITY=2

# Placeholder personalizado (opcional)
FALLBACK_CEP_API_PLACEHOLDER="{{cep}}"
```

## 🔧 Como Usar

### Usando o Helper do Container

```php
<?php

use Kamoca\FallbackCepApi\CepResolver;

/** @var CepResolver $cepResolver */
$cepResolver = app(CepResolver::class);
$address = $cepResolver->resolve('01310-100');

/** @var CepResolver $cepResolver */
$cepResolver = app()->make(CepResolver::class);
$address = $cepResolver->resolve('01310-100');
``` 

### Em um Service

```php
<?php

namespace App\Services;

use Kamoca\FallbackCepApi\CepResolver;

class AddressService
{
    public function __construct(
        private CepResolver $cepResolver
    ) {}

    public function findAddress(string $cep): array
    {
        return $this->cepResolver->resolve($cep);
    }
}
```

### Facade

```php
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Kamoca\FallbackCepApi\CepResolver;

class Cep extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CepResolver::class;
    }
}
```

```php
use App\Facades\Cep;

$address = Cep::resolve('01310-100');
```

## 🌍 Internacionalização

O pacote vem com suporte para **português brasileiro** e **inglês**. As mensagens de erro são traduzidas automaticamente baseado no locale da aplicação.

### Namespace de Tradução

Use o namespace `fallback-cep` para acessar as traduções:

```php
__(
    'fallback-cep.error.validation.missing_key',
    ['key' => 'cep']
)

__(
    'fallback-cep.error.runtime.request_failed', 
    [
        'cep' => '01310100',
        'provider' => 'ViaCep',
        'error' => 'Network timeout'
    ]
)
```

## 🏗️ Arquitetura

### Provedores Suportados

- **ViaCEP** (`via_cep`) - https://viacep.com.br
- **BrasilAPI** (`brasil_api`) - https://brasilapi.com.br

### Como Funciona o Fallback

1. Os provedores são ordenados por **prioridade** (menor número = maior prioridade)
2. A consulta começa pelo provedor de maior prioridade
3. Se falhar, automaticamente tenta o próximo provedor
4. Continua até encontrar uma resposta válida
5. Se todos falharem, lança uma exceção informativa

### Estrutura das Classes

```
CepResolver (Classe principal)
├── CepProviderContract (Interface)
├── BaseCepProvider (Classe base)
├── ViaCepProvider (Implementação específica)
└── BrasilApiProvider (Implementação específica)
```

### Adicionando Novos Provedores

Para adicionar um novo provedor, siga estes passos:

1. **Crie uma nova classe** que implemente `CepProviderContract`:

```php
<?php

namespace Kamoca\FallbackCepApi\Providers;

use Kamoca\FallbackCepApi\Contracts\CepProviderContract;

class NovoProvider extends BaseCepProvider implements CepProviderContract
{
    public function resolve(string $cep): array
    {
        // Lógica para fazer a requisição
    }

    public function transform(array $data): array
    {
        return [
            'cep' => $data['...'],
            'rua' => $data['...'],
            'bairro' => $data['...'],
            'cidade' => $data['...'],
            'uf' => $data['...'],
            'provider' => 'NovoProvider',
        ];
    }
}
```

2. **Configure no arquivo** `config/cep.php`:

```php
'providers' => [
    // ... outros provedores
    'novo_provider' => [
        'enabled' => env('FALLBACK_CEP_API_NOVO_ENABLED', true),
        'priority' => (int) env('FALLBACK_CEP_API_NOVO_PRIORITY', 3),
        'url_template' => env('FALLBACK_CEP_API_NOVO_BASE_URL', "https://api.novo.com/cep/{$placeholder}"),
        'token' => env('FALLBACK_CEP_API_NOVO_TOKEN'),
        'class' => \Kamoca\FallbackCepApi\Providers\NovoProvider::class,
    ],
],
```

3. **Adicione as variáveis de ambiente** no `.env` (opcional):

```env
FALLBACK_CEP_API_NOVO_ENABLED=true
FALLBACK_CEP_API_NOVO_PRIORITY=3
FALLBACK_CEP_API_NOVO_BASE_URL="https://api.novo.com/cep/{$placeholder}"
FALLBACK_CEP_API_NOVO_TOKEN=seu_token_aqui
```

## 🧪 Testes

**Nota**: Este pacote ainda não possui uma suíte de testes implementada. Contribuições são bem-vindas! 🤝

Para executar testes (quando implementados):

```bash
composer test
```

## 🔧 Troubleshooting

### Problemas Comuns

#### 1. "Class 'Kamoca\FallbackCepApi\CepResolver' not found"

**Solução**: Verifique se o auto-discovery está funcionando:

```bash
php artisan package:discover
php artisan config:clear
composer dump-autoload
```

#### 2. "All providers failed to resolve CEP"

**Possíveis causas**:
- CEP inexistente ou inválido
- Problemas de conectividade
- APIs dos provedores fora do ar

**Solução**: Verifique os logs e teste manualmente as URLs dos provedores.

#### 3. Configuração não está sendo aplicada

**Solução**: Publique e limpe as configurações:

```bash
php artisan vendor:publish --tag=cep-config --force
php artisan config:clear
```

## 🤝 Contribuindo

Contribuições são **muito bem-vindas**! Para contribuir:

1. Faça um Fork do projeto
2. Crie sua Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a Branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está licenciado sob a **Licença MIT** - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 👨‍💻 Autor

**Kauan Morinel Calheiro**

- 📧 Email: [kauan.calheiro@universo.univates.br](mailto:kauan.calheiro@universo.univates.br)
- 🐙 GitHub: [@KauanCalheiro](https://github.com/KauanCalheiro)

---