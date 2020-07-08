CHANGELOG for 2.1.x
===================

This changelog references the relevant changes (bug and security fixes) done
in all versions (major and minor)

To get the diff for a specific change, go to https://github.com/PandaPlatform/framework/commit/XXX where
XXX is the change hash

* 2.1.11
  * [Events] Add try..catch on decorate call when dispatching events

* 2.1.10
  * [Routing] Log the request not found information
  
* 2.1.9
  * [Helpers] Require `panda/helpers` as external package and remove all local code
  * Start removing dates from versions
  
* 2.1.8 (2018-11-23)
  * [Events] Add channel as second parameter in event dispatch function
  
* 2.1.7 (2018-11-22)
  * [Events] Add ChannelInterface as optional parameter in decorate function on DecorateInterface

* 2.1.6 (2018-10-17)
  * [Helpers] Remove passing by reference in ArrayHelper::merge()
  
* 2.1.5 (2018-10-04)
  * [Framework] Change Router::getMatchingRoute() to public to allow tests
  * [Framework] Add getter for Route::action to allow tests

* 2.1.4 (2018-03-07)
  * [Events] Add subject in MessageInterface
  * [Events] Add EventId in SubscriberInterface
  * [Events] Add identifier in EventInterface
  * [Events] Fix setting message and remove message key from array
  * [Events] Decorate message from event
  
* 2.1.3 (2018-02-23)
  * [Framework] Disable logger when paths from config are absent or invalid
  
* 2.1.2 (2018-02-21)
  * [Helpers] Skip missing replace values in StringHelper::interpolate

* 2.1.1 (2018-02-21)
  * [Helpers] Improve String Helper to interpolate using deep search in parameters array
  * [Framework] Generic cs fixes

* 2.1.0 (2017-10-22)
  * [Events] Add Events Package
