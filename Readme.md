## Famelo.Features

This Package provides a Service and a ViewHelper to help you roll out new features
easily.

## Creating a new Feature

Features are specified in a seperate Configuration file called "Features.yaml"

Here's an example:


```yaml
OnlyForAdministrators:
  condition: hasRole('My.Package:Administrator')

OnlyForMneuhaus:
  condition: isUser('mneuhaus')

OnlyAfterThisDate:
  condition: afterDate('22.11.2013')

onlyGuests:
  condition: isGuest()

onlyLoggedIn:
  condition: isNotGuest()

only25PercentOfTheUsers:
  condition: userPercentage(25)

onlyWithMatchingClientIp:
  condition: clientIp('127.0.0.1')

combination:
  condition: hasRole('My.Package:Administrator') && userPercentage(25)

combination2:
  condition: hasRole('My.Package:Administrator') || afterDate('22.11.2013')
```

## Using the FeatureService

you can easily inject the featureService like this:

```php
	/**
	 * The featureService
	 *
	 * @var \Famelo\Features\FeatureService
	 * @Flow\Inject
	 */
	protected $featureService;
```

Then you can ask it if the feature you want to act upon is activated for the current
user:

```php
if ($this->featureService->isFeatureActive("myFeature")) {
	do some cool stuff
}
```

## Using the ActiveViewHelper

For convenience theres a wrapper for the featureService for fluid:

```html
<feature:active feature="myFeature">
    show my cool feature
</feature:active>
```

alternatively you can use it like the ifViewHelper with then/else

```html
<feature:active feature="myFeature">
	<then>
    	show my cool feature
    </then>
    <else>
    	show some other suff
    </else>
</feature:active>
```

## Settings

```yaml
Famelo:
  Features:
    # You can change this setting to use your own ConditionMatcher with more specific functions
    # you might need
    conditionMatcher: \Famelo\Features\Core\ConditionMatcher

    # What should happen if the service is asked about a feature it doesn't now?
    # can be: active, inactive, exception
    # exception is the default and will throw an exception because you probably
    # mistyped some feature name
    noMatchBehavior: exception
```
