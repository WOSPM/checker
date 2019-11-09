# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/WOSPM/checklist).

## Pull Requests

- **Sync** - Please make sure your repository is up to date with ours to avoid conflicts as much as possible.
- **Language** - Please make sure to check your contribution for grammar mistakes and typos as much as possible.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

## Add New Metric

If you think that a new metric is to be added, please create an [ISSUE](https://github.com/WOSPM/checker/issues/new/choose) first by clicking metric request button. Please fill all the sections and try to explain the metric in detail.

If you want to implement a new metric, please folllow the following instructions;

- Choose a unique metric number and a unique title.
- Create a document under `mdocs` folder to describe the metric. You can use [WOSPM0000.sample.md](./mdocs/WOSPM0000.sample.md) file as a template.
- Create a class extending [Metric](./src/classes/Metric.php) class and implement the `check` method.
- Add the unittest for the new metric.
- Add the link to the README file.

## Add New Translation

- Fork the repository.
- Translate the `README.md` file.
- write the translations to a new file with naming schema (`README-[Language-code].md`), [Available languages codes](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes).

*Example: If you want to translate for Deutsch, you would name the translated readme file as `README-de.md`.*

**Stay Secure**!
