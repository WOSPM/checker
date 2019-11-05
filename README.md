# WOSPM Checker

A checker for project owners to measure their open source project.

[![Contributor Covenant](https://img.shields.io/badge/Contributor%20Covenant-v1.4%20adopted-ff69b4.svg)](CONTRIBUTING.md) [![CircleCI](https://circleci.com/gh/WOSPM/checker.svg?style=svg)](https://circleci.com/gh/WOSPM/checker) ![WOSPM](https://img.shields.io/badge/WOSPM-Welcoming-green)

---

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
## Table Of Contents

- [Introduction](#introduction)
- [How To Install And Use](#how-to-install-and-use)
- [WOSPM Metrics](#wospm-metrics)
- [Contributing](#contributing)
- [Code of Conduct](#code-of-conduct)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

---

## Introduction

`wospm-checker` is a commandline tool to measure how an open source project welcomes users and possible contributors. The tool checks the repository against a list of metrics.

## How To Install And Use

Install the package with composer.

```bash
composer global require wospm/checker

```

You can check your project by running the `wospm-checker` command in the root folder of your repo.

```bash
/full/path/to/wospm-checker
```

## WOSPM Metrics

WOSPM metrics are measures which are mostly derived from [Open Source Guides](https://opensource.guide/) to make quantitative assessments about the open sourse projects.

| Code        | Title           |
| ------------- |-------------|
| [WOSPM0001](./mdocs/WOSPM0001.md)      | USING_WOSPM |
| [WOSPM0002](./mdocs/WOSPM0002.md)      | README |
| [WOSPM0003](./mdocs/WOSPM0003.md)      | LICENSE |
| [WOSPM0004](./mdocs/WOSPM0004.md)      | CONTRIBUTING |
| [WOSPM0005](./mdocs/WOSPM0005.md)      | CODE_OF_CONDUCT |
| [WOSPM0006](./mdocs/WOSPM0006.md)      | README_TOC |
| [WOSPM0007](./mdocs/WOSPM0007.md)      | LINK_TO_CONTRIBUTE |
| [WOSPM0008](./mdocs/WOSPM0008.md)      | LINK_TO_CODE_OF_CONDUCT |
| [WOSPM0009](./mdocs/WOSPM0009.md)      | README_ADEQUATE |

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for information.

## Code of Conduct

See [CODE_OF_CONDUCT](CODE_OF_CONDUCT) for information.
