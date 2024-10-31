# BuzzingPixel PHP Queue Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased
### Added
- Add an HTTP interface for viewing and managing enqueued, completed, and failed items (see readme for how to implement and access routing), as well as JSON output
- Added method `enqueueJob` to the `QueueHandler` interface and implemented it in `RedisQueueHandler` implementation — this method simplifies adding a single job onto a queue
- Added method `getEnqueuedItemsFromAllQueues` to the `QueueHandler` interface and implemented it in `RedisQueueHandler` implementation
- Added a `QueueConfig` class for configuring various aspects of the queuing system (see readme for how to implement)
- Completed Queue items are now saved for a period of time
- Failed Queue items are now saved for a period of time

## 1.2.0 - 2023-10-06
### Added
- Added the ability to configure the Symfony command name through the constructor

## 1.1.1 - 2023-05-23
### Fixed
- Fixed a sorting issue with the `getEnqueuedItems` method on the RedisQueueHandler

## 1.1.0 - 2023-05-11
### Added
- Added methods for getting queue names on the queue handler
- Added methods for getting totals from queue
- Added method for getting enqueued items
- Added method to dequeue item
- Added method to dequeue all items

## 1.0.0 - 2023-04-06
### Added
- Initial release
