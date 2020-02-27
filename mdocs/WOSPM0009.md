# WOSPM0009

## Idenditiy

| Property        | Value           |
| ------------- |-------------|
| code      | WOSPM0009 |
| title      | README_ADEQUATE      |
| message | README should have atleast 200 words.     |
| type | MetricType::ERROR      |

## Rationale

README document is the first place that user faces before using your project or contributing to it. You should create as it is a blog post or article describing your project simply. Unfurtunately, there is no scientific metric about the word count and the `200` as word count is arguable, but It can be accepted that a README file with the word count less than `200` is a short document.

The word count is calculated by;

- Removing the newline, "-", "#" and "|" characters
- Removing HTML comments
- Removing code block
- Removing image markdown
- Replacing link markdown with the text of the link

Please read;

- [Open Source Guide By Github](https://opensource.guide/starting-a-project/#writing-a-readme)
- [Make a README](https://www.makeareadme.com/)

> [Make a README](https://www.makeareadme.com/)
> Because no one can read your mind (yet)
