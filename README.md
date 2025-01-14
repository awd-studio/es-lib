# AWD Event Sourcing library

## Provides a base event-sourcing functionality.

### Install:
```sh
composer install awd-studio/es-lib
```

### Integration
To use the library in your projects, you need to implement such interfaces:
```php
# Storage / DB layer

## Reading
\AwdEs\Event\Storage\Fetcher\EventFetcher::class
\AwdEs\Event\Storage\Fetcher\Handling\CriteriaHandlingCase::class

## Writing
\AwdEs\Event\Storage\Recorder\EventRecorder::class


# Metadata

## Applying
\AwdEs\Event\Applying\EventApplier::class

## Reading metadata
\AwdEs\Event\Handling\EventHandlerMethodResolver::class
\AwdEs\Event\Meta\EventMetadataResolver::class
```
