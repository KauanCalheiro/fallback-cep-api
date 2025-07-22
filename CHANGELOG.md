# Changelog 📜

Todas as mudanças notáveis deste projeto serão documentadas neste arquivo. ✍️

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) 🗂️,
e este projeto adere ao [Semantic Versioning](https://semver.org/spec/v2.0.0.html) 🔢.

### Planejado 📝
- Suporte a novos provedores de CEP 🆕
- Suite de testes automatizados 🧪

## [v1.0.0] - 2025-06-22 🎉

### Adicionado ➕
- Implementação inicial do sistema de fallback para APIs de CEP 🛡️
- Suporte aos provedores ViaCEP e BrasilAPI 🌐
- Sistema de prioridades configurável para provedores ⚙️
- Internacionalização com suporte para PT-BR e EN 🌍
- Service Provider com auto-discovery do Laravel 🚀
- Configuração via arquivo `config/cep.php` 🗃️
- Configuração via variáveis de ambiente ⚡
- Validação automática de dados de resposta ✅
- Padronização de resposta entre diferentes provedores 📦
- Limpeza automática de formatação de CEP 🧹
- Tratamento de exceções específicas 🛠️
- Documentação completa no README 📖

### Estrutura 🏗️
- `CepResolver`: Classe principal para resolução de CEP 🕵️‍♂️
- `CepProviderContract`: Interface para implementação de provedores 🔌
- `BaseCepProvider`: Classe base para provedores 🏛️
- `ViaCepProvider`: Implementação do provedor ViaCEP 🏷️
- `BrasilApiProvider`: Implementação do provedor BrasilAPI 🇧🇷
- `FallbackCepApiServiceProvider`: Service Provider do Laravel 🧩

### Configurações ⚙️
- Placeholder configurável para URLs dos provedores 📝
- Habilitação/desabilitação individual de provedores 🔄
- Sistema de prioridades (menor número = maior prioridade) 🥇
- URLs customizáveis para cada provedor 🌐
- Suporte a tokens de autenticação (preparado para futuros provedores) 🔑

[v1.0.0]: https://github.com/KauanCalheiro/fallback-cep-api/releases/tag/v1.0.0

