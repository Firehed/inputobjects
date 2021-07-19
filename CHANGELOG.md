# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [4.0.0] - Unreleased
### Summary
Library updated for modern PHP versions & community coding standards

### Changed
- PHP >= 7.4 is now required
- CI moved to Github Actions; updated to supported PHP versions (including PHP 8)
- [**BREAKING**] Native type hints were added in many locations.
  In practice none should actaully be BC breaks, but you may now receive a
  native `TypeError` where previously an `InvalidArgumentException` would have
  been thrown.
- Code (mostly) updated to PSR-12 formatting.
- Tests are no longer exported, so the `tests/` directory will not be included
  unless the library is installed using Composer's `--prefer-source` flag.
  `InputObjectTestTrait` has been moved to `src/` so it's still exported for use
  by library consumers.
- `InputObjectTestTrait` no longer provides `::covers` annotations, as that is
  semi-deprecated in newer PHPUnit versions. This may result in code coverage
  changes during unit tests.
- [**Breaking**, minor] `ListOf::setSeparator()` will now reject an empty string
  as a separator.


## [3.2.2] - 2019-02-04
### Summary
- Changes some tests to improve compatibility with PHPUnit 8

## [3.2.1] - 2018-09-11
### Fixed
- Fixes a rare interaction with the `AnyOf` validator that could result in the wrong value being returned

## [3.2.0] - 2018-08-01
### Added
- `Uuid` validator

## [3.1.0] - 2018-06-21

The changes from 3.0.4 should have been tagged as 3.1.0 since new features were added.
In addition, there were the following changes:

### Added
- `AnyOf` validator
- `InlineStructure` validator
- `Literal` validator
- `Unixtime` validator

## [3.0.4] - 2018-06-12
### Added
- `DateTimeString` validator
- `Any` validator
- `Nullable` validator

### Fixed
- Resolved issue in validator test trait so evaluations that return objects test as you probably intended (e.g. shallow comparison)

## [3.0.3] - 2018-05-03
### Fixed
- Resolved issue on Text validator where `setMin` and `setTrim` would conflict

## [3.0.2] - 2018-03-26
### Added
- Added `setTrim()` method to Text validator

## [3.0.1] - 2018-03-19
### Added
- Added support for validation error message propagation and access, particularly in structure objects


## [3.0.0] - 2017-12-01
### Added
- Added this changelog
- `Integer` validator
- `Boolean` validator
- `ListOf` has a `setSeparator()` method, allowing string inputs to be parsed and split accordingly

### Changed
- [**BREAKING**] The `Money` validator now returns `Money\Money` instead of the deprecated `SebastianBergmann\Money`. The two types are generally interchangable for common uses, but can break typehints downstream.
- [**BREAKING**] The `Enum` validator now expects a list of valid values in the constructor, rather than subclassing the class and defining them in a separate method. The easiest migration path is to call `parent::__construct($this->getValidValues());` in a new constructor in the subclass definition.

### Deprecated
- `WholeNumber` is deprecated in favor of `Integer`. Constructing it will now issue `E_USER_DEPRECATED`, which depending on your application may be problematic (e.g. converting all errors to exceptions).

### Internals
- Configured CI
