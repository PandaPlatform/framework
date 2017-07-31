## Bug Reports ##

To encourage active collaboration, Panda strongly encourages pull requests, not just bug reports. "Bug reports" may also be sent in the form of a pull request containing a failing test.

However, if you file a bug report, your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and a code sample that demonstrates the issue. The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix.

Creating a bug report serves to help yourself and others start on the path of fixing the problem.

The Panda source code is managed on GitHub, and there are repositories for each of the Panda packages:

* Panda Application Template
* Panda Framework
* Panda Ui

## Core Development Discussion ##

You may propose new features or improvements of existing Panda behavior in the Panda Framework issue board. If you propose a new feature, please be willing to implement at least some of the code that would be needed to complete the feature.


## Selecting the proper branch ##

All bug fixes should be sent to the latest stable branch or to the current LTS branch (2.0). Bug fixes should never be sent to the master branch unless they fix features that exist only in the upcoming release.

Minor features that are fully backwards compatible with the current Panda release may be sent to the latest stable branch.

Major new features should always be sent to the master branch, which contains the upcoming Panda release.


## Security Vulnerabilities ##

If you discover a security vulnerability within Panda, please send an email to Ioannis Papikas at ipapikas@pandaphp.org. All security vulnerabilities will be promptly addressed.


## Coding Style ##

Panda follows the PSR-2 coding standard and the PSR-4 autoloading standard.


## PHPDoc ##

Below is an example of a valid Panda documentation block. Note the empty line between parameters and the return statement. Allow one empty space between @param and the type of the parameter:

```php
/**
 * Register a binding with the container.
 *
 * @param string      $param1
 * @param string|null $default
 * 
 * @return void
 */
public function doSomething($param1, $default = null)
{
    //
}
```

### StyleCI ###
Don't worry if your code styling isn't perfect! StyleCI will automatically merge any style fixes into the Panda repository after pull requests are merged. This allows us to focus on the content of the contribution and not the code style.