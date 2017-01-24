# Generic commands and options

The application by default comes with the following base commands:

* `list`: Displays the list of commands. This is the default action if no command is provided.
* `help`: Shows help about a command. This is the same as `command --help`.

The following _useful_ options are available (for a full list, run `ssconsole help`):

* `--flush`: Flush the SilverStripe cache, class and template manifest. This is equivalent to adding "flush=1" to your `sake` command.
* `--quiet`: Disable output - useful for CRUD operations that do not need a response.
* `--no-interaction`: Disable interactive questions - useful for automated use. Ensure you pass the required arguments for the command.
* `--help`: Display the help information, arguments, options and usage example for a command.
