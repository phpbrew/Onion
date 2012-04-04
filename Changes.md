
version 1.2.0  - 三  4/ 4 15:04:42 2012
    - Refactored PEAR channel discover, parser.
    - Add ProgressStar to CurlKit
    - Cleaned up dependency resolver.
    - Fixed package parsring.

version 1.1.0  - 一  3/19 19:16:58 2012
    - Add CacheKit support.
    - Add self-update option
    - Update for GetOptionKit

version 1.0.0  - Wed Feb 29 23:39:42 2012
    - Add  --exclude option to compile command.
    - mark as stable.

version 0.0.11 - 一 12/26 20:23:56 2011
    - Add curl progress bar stuff.
    - Add retry counter for channel discover.
    - Fix package.xml generation for pyrus installer (validation)

version 0.0.9 - 日 12/25 12:43:21 2011
    "Compile using vendor files" by yuya-takeyama
    Currently ./scripts/compile.sh and ./tests/bootstrap.php loads implicit dependencies from external repos.
    Now Onion has bundle feature, so they should load from ./vendor/pear explicitly.
    This change improves development portability, and easy to build continuously at external environment (for example Travis CI).

version 0.0.7
    - Use improved GetOptionKit and CLIFramework.
    - Added compile command.
