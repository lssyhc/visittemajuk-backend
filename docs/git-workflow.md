# Git Workflow

The team works on a personal branch named `dev_<name>`, opens a pull request into `dev`, and after review merges into `dev`. Before the next cycle, pull the latest `dev` and branch off it again. That's it.

The references at the end of this file cover the underlying Git and GitHub mechanics if you want background. The flow itself is the four steps below.

## The cycle

1. Sync `dev` and create your personal branch.
2. Work on it, commit, push.
3. Open a pull request from `dev_<name>` into `dev`.
4. After review, merge into `dev`. Next cycle starts by pulling `dev` again.

## 1. Sync and branch

`dev` is the only long-lived integration branch. Branch off the latest `dev` so you start from a clean base.

```bash
git switch dev
git pull --rebase origin dev
git switch -c dev_<your-name>
```

Use your first name, initial, or GitHub handle — pick one and stick with it. Branches on the repo today follow this pattern: `dev_cahyo`, `dev_ghani`, `dev_radith`.

## 2. Work, commit, push

Commit messages follow [Conventional Commits 1.0.0](https://www.conventionalcommits.org/en/v1.0.0/) and are validated by Commitlint on the `commit-msg` hook:

```text
<type>(<scope>): <short description>

<body>

<footer>
```

`type` is one of `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `perf`, `chore`, `ci`, `build`, or `revert`. `feat` and `fix` are the ones you'll use most. Example:

```text
feat(auth): add 5/min rate-limit to /auth/login

Stops brute-force attempts on the login endpoint using Laravel's throttle middleware.

Refs: VP-123
```

Push your branch when it's reviewable:

```bash
git push -u origin dev_<your-name>
```

Husky runs the gates for you on each commit and push (see `README.md` → Git Hooks and CI).

## 3. Open the pull request

Open the PR on GitHub from `dev_<your-name>` into `dev`. Fill the PR template:

- One-paragraph summary of the change.
- Context or problem it solves.
- How to test it (manual steps or automated tests).
- Ticket reference (for example, `Closes #42`).

At least one reviewer must approve, and the status checks must be green before you merge.

## 4. Merge and resync

Merge the PR into `dev` once review and checks are green. Then resync before your next change:

```bash
git switch dev
git pull --rebase origin dev
```

That gives you a fresh base for the next `dev_<your-name>` branch (or to keep working on the same one if you need a follow-up).

## Conventions at a glance

| Topic              | Convention                                          |
| ------------------ | --------------------------------------------------- |
| Personal branch    | `dev_<name>` (matches your handle used in the team) |
| Integration branch | `dev` (protected)                                   |
| PR target          | `dev`                                               |
| Commits            | Conventional Commits, validated by Commitlint       |
| Pull strategy      | `git pull --rebase` to keep history linear          |

## References

- Git: <https://git-scm.com/doc>
- Conventional Commits 1.0.0: <https://www.conventionalcommits.org/en/v1.0.0/>
- GitHub Flow: <https://docs.github.com/en/get-started/quickstart/github-flow>
- Husky: <https://typicode.github.io/husky/>
- Commitlint: <https://commitlint.js.org/>
- lint-staged: <https://github.com/lint-staged/lint-staged>
