# 📍 Kamoca/FallbackCepApi

Um pacote Laravel robusto e confiável para consulta de CEP com **fallback automático** entre múltiplos provedores de API. Nunca mais perca uma consulta por falha de API! 🚀

## ✨ Características

- 🔄 **Fallback automático** entre provedores
- ⚡ **Configuração de prioridades** para otimizar performance
- 🌐 **Múltiplos provedores** suportados (ViaCEP, BrasilAPI)
- 🛠️ **Fácil configuração** via arquivo de config
- 🌍 **Suporte a internacionalização** (PT-BR e EN)
- 📦 **Auto-discovery** do Laravel
- 🧪 **Padronização** de resposta entre provedores

## 📋 Requisitos

[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue.svg)](https://php.net/) [![Laravel](https://img.shields.io/badge/laravel-%5E12.20-red.svg)](https://laravel.com/) [![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

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

### Configurações Principais

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Placeholder usado nas URLs dos provedores
    |--------------------------------------------------------------------------
    |
    | Esse valor será substituído pelo CEP fornecido ao construir a URL final.
    | O valor padrão é '{{cep}}'.
    |
    */
    'placeholder' => env('FALLBACK_CEP_API_PLACEHOLDER', '{{cep}}'),

    /*
    |--------------------------------------------------------------------------
    | Configurações dos provedores de CEP
    |--------------------------------------------------------------------------
    | Habilite ou desabilite provedores, defina prioridades e URLs base.
    */
    'providers' => [
        'via_cep' => [
            'enabled' => env('FALLBACK_CEP_API_VIA_CEP_ENABLED', true),
            'priority' => (int) env('FALLBACK_CEP_API_VIA_CEP_PRIORITY', 1), // Menor = maior prioridade
            'url_template' => env('FALLBACK_CEP_API_VIA_CEP_BASE_URL', "https://viacep.com.br/ws/{{cep}}/json/"),
            'token' => null,
        ],

        'brasil_api' => [
            'enabled' => env('FALLBACK_CEP_API_BRASIL_API_ENABLED', true),
            'priority' => (int) env('FALLBACK_CEP_API_BRASIL_API_PRIORITY', 2),
            'url_template' => env('FALLBACK_CEP_API_BRASIL_API_BASE_URL', "https://brasilapi.com.br/api/cep/v1/{{cep}}"),
            'token' => null,
        ],
    ],
];
```

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

### Injeção de Dependência

```php
<?php

namespace App\Http\Controllers;

use Kamoca\FallbackCepApi\CepResolver;

class AddressController extends Controller
{
    public function searchCep(string $cep, CepResolver $cepResolver)
    {
        try {
            $address = $cepResolver->resolve($cep);

            return response()->json([
                'success' => true,
                'data' => $address
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
```

### Usando o Helper do Container

```php
<?php

use Kamoca\FallbackCepApi\CepResolver;

// Resolvendo via container
$cepResolver = app(CepResolver::class);
$address = $cepResolver->resolve('01310-100');

// Ou usando make
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
        // Remove formatação automaticamente
        return $this->cepResolver->resolve($cep);
    }
}
```

### Resposta Padronizada

O pacote retorna sempre uma estrutura padronizada, independente do provedor:

```php
[
    'cep' => '01310-100',
    'rua' => 'Avenida Paulista',
    'bairro' => 'Bela Vista',
    'cidade' => 'São Paulo',
    'uf' => 'SP'
    'provider' => 'ViaCep', // Nome do provedor que retornou a resposta
]
```

## 🌍 Internacionalização

O pacote vem com suporte para **português brasileiro** e **inglês**. As mensagens de erro são traduzidas automaticamente baseado no locale da aplicação.

### Namespace de Tradução

Use o namespace `fallback-cep-api` para acessar as traduções:

```php
__(
    'fallback-cep-api.error.validation.missing_key',
    ['key' => 'cep']
)

__(
    'fallback-cep-api.error.runtime.request_failed', 
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

### Adicionando Novos Provedores

Para adicionar um novo provedor, siga estes passos:

1. Implemente a interface `CepProviderContract`
2. Extenda `BaseCepProvider`
3. Configure no arquivo `cep.php`
4. Adicione o mapeamento no `CepResolver`

## 🧪 Testes

**Nota**: Este pacote ainda não possui uma suíte de testes implementada. Contribuições são bem-vindas! 🤝

Para executar testes (quando implementados):

```bash
composer test
```

## 🤝 Contribuindo

Contribuições são **muito bem-vindas**! Para contribuir:

1. Faça um Fork do projeto
2. Crie sua Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a Branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

### Diretrizes para Contribuição

- Mantenha a compatibilidade com PHP 8.2+
- Siga os padrões PSR-12
- Adicione testes para novas funcionalidades
- Documente mudanças no README
- Use commits descritivos

## 📄 Licença

Este projeto está licenciado sob a **Licença MIT** - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 👨‍💻 Autor

**Kauan Morinel Calheiro**

- Email: kauan.calheiro@universo.univates.br
- GitHub: [@KauanCalheiro](https://github.com/KauanCalheiro)
