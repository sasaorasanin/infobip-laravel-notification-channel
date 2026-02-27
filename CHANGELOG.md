# Changelog

All notable changes to this package will be documented in this file

## 2.0.0 - 2026-01-26

### Breaking Changes
- Updated to PHP 8.3+ requirement
- Updated to Laravel 11.x/12.x support
- Updated to Infobip API Client 6.2.1
- Changed authentication from username/password to API key
- Modernized codebase with strict types and constructor property promotion
- Replaced PHPUnit with Pest for testing

### Added
- Full PHP 8.3 type declarations
- Readonly properties for immutable data
- Modern dependency injection patterns
- Comprehensive Pest test suite
- Laravel Pint for code styling
- Event system for notification tracking
- Better error handling and exceptions

### Changed
- Configuration now uses `api_key` instead of `username`/`password`
- Updated all Infobip API client calls to use v6.x API
- Improved service provider registration
- Enhanced documentation with modern examples

### Removed
- Support for Laravel 5.x, 6.x, 7.x, 8.x, 9.x, 10.x
- Support for PHP versions below 8.3
- Old PHPUnit tests
- Legacy username/password authentication

## 1.0.0 - 2020-08-08

- initial release

