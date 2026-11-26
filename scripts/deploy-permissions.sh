#!/usr/bin/env bash
#
# VerifyMagang - permission setup for local Linux/WSL and production Linux.
#
# Run from any directory:
#   bash /path/to/magang-verify/scripts/deploy-permissions.sh
#
# Local WSL (Apache/PHP-FPM commonly runs as www-data):
#   sudo env WEB_USER=www-data bash scripts/deploy-permissions.sh
#
# Shared hosting where PHP runs as the hosting account:
#   WEB_USER="$(id -un)" bash scripts/deploy-permissions.sh
#
# This script changes permissions only under uploads/ and storage/.
# It does not change ownership, delete data, or use chmod 777.

set -Eeuo pipefail

PROJECT_ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
UPLOADS_DIR="$PROJECT_ROOT/uploads"
STORAGE_DIR="$PROJECT_ROOT/storage"

DEPLOY_USER="${SUDO_USER:-$(id -un)}"
WEB_USER="${WEB_USER:-}"

log()  { printf '[VerifyMagang] %s\n' "$*"; }
warn() { printf '[VerifyMagang] WARNING: %s\n' "$*" >&2; }
die()  { printf '[VerifyMagang] ERROR: %s\n' "$*" >&2; exit 1; }

[[ -d "$PROJECT_ROOT" ]] || die "Project root tidak ditemukan."
[[ "$DEPLOY_USER" != "root" ]] || warn "SUDO_USER tidak terdeteksi; ACL deploy akan diberikan ke root."

id "$DEPLOY_USER" >/dev/null 2>&1 || die "User deploy '$DEPLOY_USER' tidak ditemukan."

# Detect common Linux web-server accounts. On shared hosting, PHP often runs
# as the hosting account, so WEB_USER should be set explicitly to that account.
if [[ -z "$WEB_USER" ]]; then
  for candidate in www-data apache nginx; do
    if id "$candidate" >/dev/null 2>&1; then
      WEB_USER="$candidate"
      break
    fi
  done
fi

if [[ -n "$WEB_USER" ]]; then
  id "$WEB_USER" >/dev/null 2>&1 || die "WEB_USER '$WEB_USER' tidak ditemukan."
fi

# Create the standard directories used by VerifyMagang.
mkdir -p \
  "$UPLOADS_DIR/cv" \
  "$UPLOADS_DIR/profil" \
  "$UPLOADS_DIR/portofolio" \
  "$UPLOADS_DIR/sertifikat" \
  "$STORAGE_DIR/logs" \
  "$STORAGE_DIR/pendaftaran"

if command -v setfacl >/dev/null 2>&1; then
  DIR_ACL="u:${DEPLOY_USER}:rwx"
  FILE_ACL="u:${DEPLOY_USER}:rw-"
  DEFAULT_ACL="d:u:${DEPLOY_USER}:rwx"

  if [[ -n "$WEB_USER" && "$WEB_USER" != "$DEPLOY_USER" ]]; then
    DIR_ACL="${DIR_ACL},u:${WEB_USER}:rwx"
    FILE_ACL="${FILE_ACL},u:${WEB_USER}:rw-"
    DEFAULT_ACL="${DEFAULT_ACL},d:u:${WEB_USER}:rwx"
  fi

  find "$UPLOADS_DIR" "$STORAGE_DIR" -type d \
    -exec setfacl -m "$DIR_ACL" {} +
  find "$UPLOADS_DIR" "$STORAGE_DIR" -type f \
    -exec setfacl -m "$FILE_ACL" {} +
  find "$UPLOADS_DIR" "$STORAGE_DIR" -type d \
    -exec setfacl -m "$DEFAULT_ACL" {} +

  log "ACL berhasil diterapkan."
else
  # Fallback when ACL tools/filesystem support are unavailable.
  # Access remains limited to owner and group, not 'other'.
  warn "setfacl tidak tersedia; menggunakan permission owner/group."
  chmod -R u+rwX,g+rwX "$UPLOADS_DIR" "$STORAGE_DIR"
  find "$UPLOADS_DIR" "$STORAGE_DIR" -type d -exec chmod g+s {} +

  if [[ -n "$WEB_USER" && "$WEB_USER" != "$DEPLOY_USER" ]]; then
    warn "Pastikan user PHP '$WEB_USER' berada dalam group yang memiliki akses ke folder ini."
  fi
fi

log "Project: $PROJECT_ROOT"
log "Deploy user: $DEPLOY_USER"
log "Web/PHP user: ${WEB_USER:-tidak terdeteksi; diasumsikan sama dengan user deploy}"
log "Selesai. Ownership tidak diubah dan chmod 777 tidak digunakan."
