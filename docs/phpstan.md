# PHPStan Level 5 — Code Rules

How PHPStan and Larastan are configured on this project, and what the level 5 rule set actually checks. Config lives in [`phpstan.neon`](../phpstan.neon):

```neon
includes:
    - vendor/larastan/larastan/extension.neon

parameters:
    paths:
        - app
        - routes
        - database
    level: 5
    tmpDir: storage/framework/phpstan
```

Run it with:

```bash
composer analyse
```

## How the levels work

PHPStan has 11 levels (0–10), each one stricter than the last. Picking level 5 gets you everything level 0–4 catches, plus the level 5 checks. Levels are cumulative — running level 5 is not "level 5 only," it's "level 0 through 5." Full list is in the [Rule Levels docs](https://phpstan.org/user-guide/rule-levels).

What each level adds on top of the previous one, taken from the official docs:

| Level | What it adds                                                                                                                               |
| ----- | ------------------------------------------------------------------------------------------------------------------------------------------ |
| 0     | Basic checks: unknown classes, functions, methods called on `$this`, wrong argument counts, always-undefined variables                     |
| 1     | Possibly undefined variables; unknown magic methods and properties on classes with `__call` / `__get`                                      |
| 2     | Unknown methods checked on all expressions (not just `$this`); PHPDoc validation                                                           |
| 3     | Return types; types assigned to properties                                                                                                 |
| 4     | Dead code: always-false `instanceof`, dead `else` branches, unreachable code after `return`                                                |
| 5     | Argument types passed to methods and functions                                                                                             |
| 6     | Missing typehints                                                                                                                          |
| 7     | Partially wrong union types — methods that only exist on some types in a union                                                             |
| 8     | Calling methods and accessing properties on nullable types                                                                                 |
| 9     | Be strict about explicit `mixed` — the only allowed operation is to pass it to another `mixed`                                             |
| 10    | (New in PHPStan 2.0) Be even more strict about `mixed` — report errors even for implicit `mixed` (missing type), not just explicit `mixed` |

We stop at level 5. Higher levels bring stricter typing, but they also bring more annotation overhead and more back-and-forth in review. Move up once the codebase is clean and the team has the appetite for it.

## Working with PHPStan

1. **Keep it in `pre-push` and CI.** The `pre-push` hook already runs `composer quality` (which runs PHPStan), and `.github/workflows/ci.yml` runs it on every push to `dev` and on every pull request into `dev`.
2. **Baseline is a safety net, not a solution.** Use `phpstan-baseline.neon` only when lowering the level and you need to keep PRs green. Every baseline entry should have a follow-up ticket.
3. **Avoid `@phpstan-` annotations unless you really need them.** Generic PHPDoc is easier to read than tool-specific tags.
4. **Use Larastan generics for Eloquent collections.** `Collection<int, User>` lets PHPStan follow model types properly.

## Useful commands

```bash
# Default (per phpstan.neon)
composer analyse

# Just one path
vendor/bin/phpstan analyse app/Http/Controllers --level=5

# JSON output for CI tooling
vendor/bin/phpstan analyse --error-format=json --no-progress
```

## References

- PHPStan — [User Guide](https://phpstan.org/user-guide/getting-started)
- PHPStan — [Rule Levels](https://phpstan.org/user-guide/rule-levels)
- Larastan — [Documentation](https://larastan.laravelshift.com/)
- Larastan — [GitHub repo](https://github.com/larastan/larastan)
- PHP — [Type declarations](https://www.php.net/manual/en/language.types.declarations.php)
