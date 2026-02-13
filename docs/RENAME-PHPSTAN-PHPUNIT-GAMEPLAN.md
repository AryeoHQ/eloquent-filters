# Game plan: PHPStan → PhpStan and PHPUnit → PhpUnit

**Scope:** `src/`, `tooling/`, `tests/` only.  
**Do not change:** Vendor namespaces (e.g. `PHPStan\Rules\Rule`, `PHPUnit\Framework\TestCase`) or `composer.json` package names like `phpstan/phpstan` / `phpunit/phpunit`.

---

## 1. What we are changing

### 1.1 Our PHPStan naming → PhpStan

| Location | Current | Target |
|----------|---------|--------|
| **Namespace** (our code) | `Tooling\EloquentFilters\PHPStan\*` | `Tooling\EloquentFilters\PhpStan\*` |
| **Namespace** (tests) | `Tests\Tooling\EloquentFilters\PHPStan\*` | `Tests\Tooling\EloquentFilters\PhpStan\*` |
| **Directory** (src) | `src/Tooling/EloquentFilters/PHPStan/` | `src/Tooling/EloquentFilters/PhpStan/` |
| **Directory** (tests) | `tests/Tooling/EloquentFilters/PHPStan/` | `tests/Tooling/EloquentFilters/PhpStan/` |
| **Config path** | `tooling/phpstan/` | `tooling/PhpStan/` (optional; see 2.2) |
| **Config reference** | `Tooling\EloquentFilters\PHPStan\Rules\FilteringRule` | `Tooling\EloquentFilters\PhpStan\Rules\FilteringRule` |

**Leave unchanged:** All `use PHPStan\...` imports (Analyser, Rules, Testing, etc.) — those are the official phpstan package.

### 1.2 Our PHPUnit naming → PhpUnit

- **Current state:** We have no project-owned namespace, folder, or class named `PHPUnit`. All `PHPUnit\*` references are to the **framework** (`PHPUnit\Framework\*`, `PHPUnit\Metadata\*`).
- **Action:** Keep all framework imports as `PHPUnit\*`. Do not rename `phpunit.xml` or `.phpunit.cache` (tool conventions). If we add our own tooling/helpers named after the tool later, use `PhpUnit` (e.g. `Tooling\...\PhpUnit\...`).

---

## 2. File and reference checklist

### 2.1 Files to edit (namespace/string updates)

| File | Changes |
|------|--------|
| `src/Tooling/EloquentFilters/PHPStan/Rules/FilteringRule.php` | Namespace `PHPStan` → `PhpStan`. (Do not change `use PHPStan\...`.) |
| `src/Tooling/EloquentFilters/PHPStan/Examples/BaseBuilder.php` | Namespace `PHPStan` → `PhpStan`. |
| `tests/Tooling/EloquentFilters/PHPStan/Rules/FilteringRuleTest.php` | Namespace and `use Tooling\...\PHPStan\...` → `PhpStan`. (Do not change `use PHPStan\...` or `use PHPUnit\...`.) |
| `tooling/phpstan/rules.neon` | `class: Tooling\EloquentFilters\PHPStan\Rules\FilteringRule` → `...\PhpStan\Rules\FilteringRule`. |
| `composer.json` | If we rename `tooling/phpstan/` → `tooling/PhpStan/`: update `extra.tooling.phpstan` to `"tooling/PhpStan/rules.neon"`. |

### 2.2 Directories to rename (with macOS-safe approach)

- `src/Tooling/EloquentFilters/PHPStan` → `PhpStan`
- `tests/Tooling/EloquentFilters/PHPStan` → `PhpStan`
- `tooling/phpstan` → `tooling/PhpStan` (optional; keeps tool config under a PascalCase folder)

**No filename or class renames** are required: no file or class is named `PHPStan` or `PHPUnit`.

---

## 3. macOS case-insensitivity and Git

On macOS the filesystem is typically case-insensitive. A direct rename `PHPStan` → `PhpStan` can leave Git thinking nothing changed, so the case change may not appear on GitHub.

**Use a two-step rename so Git records the change:**

1. **Step 1 – move to a temporary name (different case):**
   - `PHPStan` → `PhpStanTemp` (or `_PhpStan`)
   - `phpstan` → `PhpStanTemp` (or `_PhpStan`) for tooling
2. **Commit** (Git sees delete + add).
3. **Step 2 – move to final name:**
   - `PhpStanTemp` → `PhpStan`
4. **Commit** (Git sees rename).

Use `git mv` so Git tracks renames:

```bash
# Example for src (repeat idea for tests and tooling)
git mv src/Tooling/EloquentFilters/PHPStan src/Tooling/EloquentFilters/PhpStanTemp
git commit -m "Rename PHPStan dir (step 1: temp name for case change)"
git mv src/Tooling/EloquentFilters/PhpStanTemp src/Tooling/EloquentFilters/PhpStan
git commit -m "Rename PHPStan dir (step 2: PhpStan)"
```

Do the same for `tests/.../PHPStan` and, if desired, `tooling/phpstan` → `tooling/PhpStanTemp` → `tooling/PhpStan`.

---

## 4. Recommended order of operations

1. **Update all in-file references first** (namespaces and `rules.neon`, and `composer.json` if changing tooling path), so that after directory renames the code still points at the new paths/namespaces.
2. **Rename directories** using the two-step `git mv` approach above (src, then tests, then tooling if applicable).
3. **Run tests and static analysis:**
   - `composer test`
   - `vendor/bin/phpstan analyse` (or your normal PHPStan command)
4. **Push and verify on GitHub** that the new case appears correctly in the repo.

---

## 5. Summary

| Item | PHPStan → PhpStan | PHPUnit → PhpUnit |
|------|-------------------|-------------------|
| **Our namespaces** | Yes: `...\PHPStan\*` → `...\PhpStan\*` | N/A (no project PHPUnit namespace) |
| **Our directories** | Yes: `PHPStan` → `PhpStan` (two-step on macOS) | N/A |
| **Config path** | Optional: `tooling/phpstan` → `tooling/PhpStan` | N/A |
| **Vendor imports** | No change (`use PHPStan\...`) | No change (`use PHPUnit\...`) |
| **Config files** | No rename of `rules.neon` | No rename of `phpunit.xml` / `.phpunit.cache` |
| **Class/file names** | No renames needed | No renames needed |

This keeps our own naming consistent (PhpStan / PhpUnit) while leaving vendor and tool filenames unchanged and ensures the renames are committed correctly on a case-insensitive filesystem.
