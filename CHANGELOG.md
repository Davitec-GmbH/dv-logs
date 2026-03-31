# Changelog

## [1.0.1] - 2026-03-31

### Added
- RST documentation (Introduction, Installation, Configuration, Usage)
- Unit tests for LogModuleController (log parsing, format detection)
- phpunit.xml configuration
- PHP 8.2+ requirement in ext_emconf.php and composer.json
- declare(strict_types=1) in ext_emconf.php
- .gitignore

### Fixed
- Corrected TYPO3 version constraint in ext_emconf.php (was `12.4.0-13.4.0-14.3.99`, now `12.4.0-14.3.99`)
- Added missing composer dependencies (typo3/cms-backend, typo3/cms-extbase)

## [1.0.0] - 2026-02-15

### Added
- Initial release
- Backend module under System > DV Logs
- Log file listing with size and creation date
- Log file detail view with parsed entries (datetime, level, message)
- Log file deletion
- Support for standard TYPO3 log format and component format
- Last 500 lines displayed in reverse chronological order
- TYPO3 v12, v13, and v14 support
