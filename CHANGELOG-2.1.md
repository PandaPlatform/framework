CHANGELOG for 2.1.x
===================

This changelog references the relevant changes (bug and security fixes) done
in all versions (major and minor)

To get the diff for a specific change, go to https://github.com/PandaPlatform/framework/commit/XXX where
XXX is the change hash

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
