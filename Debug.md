# Debug steps
1. Repeat the error on your local machine.
    * Enable error_reporting
    * Enable display_errors
    * Virtualisation tool: Docker or vagrant
2. If still no error is visible: Check nginx/apache/php-fpm logs
3. Install/use xdebug (go through code step-by-step) (or dump(); exit; until you find it)
4. Write failing test
    * To avoid the return of specific errors
5. Fix code
6. Check who did it with git annotate and explain what went wrong and how to prevent it.

## Common mistakes
* Sending information before header
* Infinite loops
* Parsing error (like forgetting a ;)
