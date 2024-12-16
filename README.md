## HTTP API for user registration and management

## Technologies Used

- Language: PHP
- Web Server: Apache
- Authentication: JWT
- Database: MySQL
- Automated Testing: PHPUnit + Github Actions
- Documentation: Swagger

## Git Flow

- **main**: Production code

- **dev**: Most recent development code

- **feature/feature_name**: Create a new branch when implementing a new feature or improvement. Branch off from dev. Once completed, merge it into dev and delete it.

- **fix/fix_name**: Create a new branch when implementing a non-urgent fix. Branch off from dev. Once completed, merge it into dev and delete it.

- **hotfix/fix_name**: Create a new branch when implementing an urgent fix directly on production. Branch off from main. Once completed, merge it into main and dev and delete it.

- **release/version**: Create a new branch when a development phase is complete and is ready for final testing, the last step before going to production. Branch off from dev after all features, fixes, and hotfixes have been merged into dev. Once final tests and possible fixes are complete, merge into main and dev and delete it. Once deleted, the software version tag should be updated.

## Postman Workspace

workspace: https://www.postman.com/nixloar/workspace/liven-api/collection/29519308-5a37395b-4522-4933-a532-1d17b2c938e5