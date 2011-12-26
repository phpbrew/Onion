

0.0.10 - 一 12/26 20:23:56 2011
    - Add curl progress bar stuff.
    - Add retry counter for channel discover.
    - Fix package.xml generation for pyrus installer (validation)

0.0.9 - 日 12/25 12:43:21 2011
    "Compile using vendor files" by yuya-takeyama
    Currently ./scripts/compile.sh and ./tests/bootstrap.php loads implicit dependencies from external repos.
    Now Onion has bundle feature, so they should load from ./vendor/pear explicitly.
    This change improves development portability, and easy to build continuously at external environment (for example Travis CI).

0.0.7
    - Use improved GetOptionKit and CLIFramework.
    - Added compile command.
