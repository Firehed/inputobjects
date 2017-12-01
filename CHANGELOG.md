# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

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
