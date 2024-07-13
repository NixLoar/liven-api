# Projeto de avaliação técnica Backend Liven

## Enunciado

Desenvolver uma API HTTP com cadastro e controle de usuários.

## Tecnologias utilizadas

- Linguagem: PHP 
- Web Server: Apache
- Autenticação: JWT
- Banco de dados: MySQL
- Testes automatizados: PHPUnit + Github Actions
- Documentação: Swagger

## Git Flow

- **main**: Código em produção

- **dev**: Código mais recente e em desenvolvimento

- **feature/nome_funcionalidade**: Crie uma nova quando for implementar uma nova funcionalidade ou melhoria. Crie a partir da branch *dev*. Ao finalizar, dê *merge* na *dev* e a delete

- **fix/nome_correção**: Crie uma nova quando for implementar uma correção **não urgente**. Crie a partir da branch *dev*. Ao finalizar, dê *merge* na *dev* e a delete

- **hotfix/nome_correção**: Crie uma nova quando for implementar uma correção **urgente** direto em produção. Crie a partir da branch *main*. Ao finalizar, dê *merge* na *main* e *dev* e a delete

- **release/version**: Crie uma nova quando uma etapa de desenvolvimento estiver finalizada e for para os testes finais, na última etapa antes de ir para produção. Crie a partir da branch *dev*, após ter todas as *features*, *fixes* e *hotfixes* mergeados na *dev*. Ao finalizar os testes e possíveis correções, dê *merge* na *main* e *dev* e a delete. Ao ser deletada, a tag de versão do software deve ser atualizada