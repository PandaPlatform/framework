CHANGELOG for 2.0.x
===================

This changelog references the relevant changes (bug and security fixes) done
in all versions (major and minor)

To get the diff for a specific change, go to https://github.com/PandaPlatform/framework/commit/XXX where
XXX is the change hash

* 2.0.0 (2017-06-28)
  * [Registry] Create Registry Package
  * [Config] Create Config Package
  * [Localization] Create Localization Package
  * [Helpers] Add StringHelper, DateTimeHelper and NumberHelper
  * [Storage] Add StorageAdapterInterface and create Filesystem implements StorageAdapterInterface
  * [Contracts] Move interface to their packages
  * [Framework] Improve Application structure and logic
  * [Framework] Separate BootLoaders and create BootstrapRegistry (extends SharedRegistry)
  * [Framework] Add more BootLoaders
  * [Framework] Improve Application structure and logic
  * [Framework] Add Facades method docblocks
  * [Framework] Work with views as plain html files without specific folder structure
  
* 2.0.1 (2017-08-04)
  * [Contracts] Set request to be optional to allow mocking for tests
  * [Framework] Fix logging path to be relative to application and not to storage path

* 2.0.2 (2017-08-04)
  * [Framework] Set request parameter in boot() to be optional for Kernel and Application
  
* 2.0.3 (2017-08-04)
  * [Framework] Introduce logger name parameter in config file
  * [Framework] Simplify logger processors by removing them

* 2.0.4 (2017-08-24)
  * [Framework] Add a default value for the environment variable
  * [Framework] Add an active function check in Debugger
  * [Framework] Change scope to protected for all BootLoaders' fields
  * [Localization] Minor cs and version fixes
  * [Registry/Config] Update README
  * [Framework] Mark Application as initialized to avoid duplicate boots

* 2.0.5 (2017-08-28)
  * [Framework] Fix BootstrapRegistry setting items to Shared Registry

* 2.0.5 (2017-10-03)
  * [Localization] Make LocaleHelper more flexible about locale formats
