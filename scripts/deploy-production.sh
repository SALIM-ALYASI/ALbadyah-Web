#!/usr/bin/env bash
set -euo pipefail

PROJECT="/Volumes/Projects/Laravel/ALbadyah-Web"
BRANCH="main"

REMOTE_HOST="ALbadyah-Web"
REMOTE_PROJECT="/home/u145701829/domains/al-badyah.com/public_html"

cd "$PROJECT"

echo "========================================"
echo " AL-BADYAH SAFE DEPLOY"
echo "========================================"

echo
echo "1/8 — فحص حالة Git"
git status --short

if git diff --name-only | grep -qE '^\.env$|/\.env$'; then
    echo "ERROR: يوجد تعديل في .env — تم إيقاف النشر."
    exit 1
fi

echo
echo "2/8 — تحديث معلومات GitHub"
git fetch origin "$BRANCH"

if ! git merge-base --is-ancestor "origin/$BRANCH" HEAD; then
    echo "ERROR: GitHub يحتوي تغييرات غير موجودة محليًا."
    echo "نفّذ أولاً:"
    echo "git pull --rebase origin $BRANCH"
    exit 1
fi

echo
echo "3/8 — فحص PHP"

PHP_FILES=$(git diff --name-only --diff-filter=ACMR HEAD | grep -E '\.php$' || true)

if [ -n "$PHP_FILES" ]; then
    while IFS= read -r file; do
        [ -f "$file" ] || continue
        php -l "$file" >/dev/null
        echo "OK: $file"
    done <<< "$PHP_FILES"
else
    echo "لا توجد ملفات PHP معدلة تحتاج فحصًا."
fi

echo
echo "4/8 — فحص Laravel"

php artisan optimize:clear >/dev/null

php artisan route:list --no-ansi >/dev/null

echo "Laravel routes: OK"

if [ -f artisan ]; then
    php artisan about --no-ansi >/dev/null
    echo "Laravel boot: OK"
fi

echo
echo "5/8 — فحص اختبارات المشروع"

if php artisan test --stop-on-failure; then
    echo "Tests: OK"
else
    echo "ERROR: فشل اختبار واحد أو أكثر."
    exit 1
fi

echo
echo "6/8 — Commit"

if [ -n "$(git status --porcelain)" ]; then

    # لا نضيف ملفات البيئة أو الصور التشغيلية
    git add \
        app \
        bootstrap \
        config \
        database \
        resources \
        routes \
        tests \
        composer.json \
        composer.lock \
        artisan \
        scripts \
        2>/dev/null || true

    if git diff --cached --quiet; then
        echo "لا توجد تغييرات كود جاهزة للـ commit."
    else
        MESSAGE="${1:-Update AL-Badyah application}"
        git commit -m "$MESSAGE"
    fi
else
    echo "لا توجد تغييرات محلية."
fi

echo
echo "7/8 — تحديث GitHub"

git push origin "$BRANCH"

echo "GitHub: UPDATED"

echo
echo "8/8 — تحديث Hostinger"

ssh "$REMOTE_HOST" "
set -euo pipefail

cd '$REMOTE_PROJECT'

echo '--- Production status ---'

if [ -n \"\$(git diff --name-only)\" ]; then
    echo 'ERROR: توجد تعديلات tracked غير محفوظة على الإنتاج.'
    git status --short
    exit 1
fi

git fetch origin '$BRANCH'

git checkout '$BRANCH'

git pull --ff-only origin '$BRANCH'

php artisan migrate --force

php artisan optimize:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo
echo '--- Production verification ---'

php artisan route:list --no-ansi >/dev/null

echo \"COMMIT=\$(git rev-parse --short HEAD)\"
echo 'HOSTINGER_DEPLOY_OK'
"

echo
echo "========================================"
echo " DEPLOY COMPLETED"
echo "========================================"

echo "LOCAL   : $(git rev-parse --short HEAD)"
echo "GITHUB  : $(git rev-parse --short origin/$BRANCH)"

REMOTE_COMMIT=$(ssh "$REMOTE_HOST" \
    "cd '$REMOTE_PROJECT' && git rev-parse --short HEAD")

echo "HOSTING : $REMOTE_COMMIT"

if [ "$(git rev-parse HEAD)" != "$(git rev-parse origin/$BRANCH)" ]; then
    echo "WARNING: Local != GitHub"
    exit 1
fi

echo
echo "✅ Local = GitHub = Hostinger"
