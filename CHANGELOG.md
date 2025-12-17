# Changelog for FriendsOfBehat/MinkExtension

## Version 3.0.0

* [BC break] Goutte support has been removed.

* [BC break] The classes `FailureShowListener`, `SessionsListener` and `MinkExtension` are now `final`.

## Version 2.8.0

* The classes `FailureShowListener`, `SessionsListener` and `MinkExtension` have been marked as `@final`, and they will become
  `final` classes in the next major release (https://github.com/FriendsOfBehat/MinkExtension/pull/41).

* Goutte support has been deprecated, and will be removed in the next major release. The `GoutteFactory` will trigger a deprecation
  notice when it is used to build a driver, for example by using `goutte` as the driver identifier in your `behat.yml` configuration
  file (https://github.com/FriendsOfBehat/MinkExtension/pull/39/). Note, however, that Behat currenty does not have a built-in mechanism
  to collect such deprecation notices and display them in a user-friendly way.

* Added soft `@return` type hints to the `MinkAwareContext` and `DriverFactory` interfaces and to all methods in `MinkContext` and `RawMinkContext`.
  If you implement or extend these classes or any of the drivers shipped here, make sure to add _real_ corresponding return types to your 
  implementations now. Signatures will be changed to include return types in the next major version.

