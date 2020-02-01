# Debug steps
1. Make the application run locally
2. Repeat the error on your local machine (where error_reporting and display_errors is enabled)
3. Check nginx/apache/php-fpm logs
4. Install/use xdebug (go through code step-by-step) (or dump(); exit; until you find it)
5. Write failing test
6. Fix code
7. Check who did it with git annotate and explain what went wrong and how to prevent it.

## Common mistakes
* Sending information before header
* Infinite loops
* Parsing error (like forgetting a ;)
