# AWD Event Sourcing library

This is a PHP library designed to simplify the implementation of Event Sourcing patterns in your applications. It provides a robust set of tools and abstractions to manage aggregates, events, event storage, and snapshots, enabling you to build scalable, maintainable, and auditable systems.

## What is Event Sourcing?

Event Sourcing is a design pattern where changes to an application's state are captured as a sequence of events. Instead of storing only the current state, the application maintains a history of all events that have occurred. This approach offers several advantages, including:

- **Auditability:** Every state change is recorded, providing a complete history.
- **Scalability:** Events can be processed asynchronously or in parallel.
- **Reconstructability:** Past states can be rebuilt by replaying events.

## Features

- [x] **Aggregates:** Manage domain entities with built-in support for event sourcing.
- [x] **Events:** Define and handle domain events easily.
- [x] **Event Store:** Persist and retrieve events from a configurable storage backend.
- [x] **Repositories:** Simplify loading and saving aggregates.
- [ ] **Snapshots:** Optimize performance by storing snapshots of aggregate states.

## Installation

You can install ES Lib via Composer:

```sh
composer install awd-studio/es-lib
```
## Usage

Se the [example](example) section.

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
\AwdEs\Meta\Event\EventMetadataResolver::class
```

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
