#!/usr/bin/env bash
# BankU Reseller Identity Verification APIs - curl client.
# Mirrors app/Services/BankU/BankUIdentityService.php.
#
# Credentials come from the environment or from ./.env (BANKU_BASE_URL,
# BANKU_CLIENT_ID, BANKU_CLIENT_SECRET). Never commit real values.
#
# Usage: ./banku_api.sh <command> [args...]   (run with no args for help)

set -euo pipefail

# Load .env values for BANKU_* if not already exported.
if [[ -f "$(dirname "$0")/.env" ]]; then
  while IFS='=' read -r key val; do
    [[ "$key" =~ ^BANKU_(BASE_URL|CLIENT_ID|CLIENT_SECRET)$ ]] || continue
    [[ -z "${!key:-}" ]] && export "$key=$(echo "$val" | tr -d '\r"'"'")"
  done < "$(dirname "$0")/.env"
fi

BANKU_BASE_URL="${BANKU_BASE_URL:-https://app.banku.co.in}"
: "${BANKU_CLIENT_ID:?Set BANKU_CLIENT_ID}"
: "${BANKU_CLIENT_SECRET:?Set BANKU_CLIENT_SECRET}"

uuid() {
  if command -v uuidgen >/dev/null 2>&1; then uuidgen | tr 'A-Z' 'a-z'
  elif [[ -r /proc/sys/kernel/random/uuid ]]; then cat /proc/sys/kernel/random/uuid
  else powershell -NoProfile -c "[guid]::NewGuid().ToString()" | tr -d '\r'
  fi
}

# post <endpoint> <json-body> [idempotency-key]
post() {
  local endpoint="$1" body="$2" idem="${3:-$(uuid)}"
  local out
  out=$(curl -sS -X POST "${BANKU_BASE_URL%/}/${endpoint#/}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Client-Id: ${BANKU_CLIENT_ID}" \
    -H "X-Client-Secret: ${BANKU_CLIENT_SECRET}" \
    -H "Idempotency-Key: ${idem}" \
    --connect-timeout 5 --max-time 15 \
    -w '\nHTTP %{http_code}\n' \
    -d "$body")
  if command -v jq >/dev/null 2>&1; then
    # Pretty-print the JSON part, keep the trailing HTTP status line.
    printf '%s\n' "$out" | sed '$d' | jq . 2>/dev/null || printf '%s\n' "$out" | sed '$d'
    printf '%s\n' "$out" | tail -n 1
  else
    printf '%s\n' "$out"
  fi
}

# ---- Aadhaar (charged on send-otp only; verify-otp is free) ----
aadhaar_send_otp()   { post /api/reseller/v1/aadhaar/send-otp   "{\"aadhaar\":\"$1\"}"; }
aadhaar_verify_otp() { post /api/reseller/v1/aadhaar/verify-otp "{\"otp\":\"$1\",\"ref_id\":\"$2\"}"; }

# ---- PAN ----
pan_verify() { post /api/reseller/v1/pan/verify "{\"pan\":\"$1\"}"; }

# ---- Vehicle RC ----
rc_verify() {
  post /api/reseller/v1/identity/rc/verify \
    "{\"vehicleRegistrationNumber\":\"$1\",\"verification_id\":\"rc_$(uuid)\"}"
}

# ---- Driving licence (dob: YYYY-MM-DD) ----
dl_verify() {
  post /api/reseller/v1/identity/driving-license/verify \
    "{\"drivingLicenseNumber\":\"$1\",\"dob\":\"$2\",\"verification_id\":\"dl_$(uuid)\"}"
}

# ---- IFSC ----
ifsc_verify() {
  local ref; ref="ifsc_$(uuid)"
  post /api/reseller/v1/ifsc/verify "{\"ifsc\":\"$1\",\"verification_id\":\"$ref\"}"
}

# ---- Bank account (async: verify, then poll status with the same ref) ----
bank_verify() {   # <account> <ifsc> <name> [reference]
  local ref="${4:-$(uuid)}"
  post /api/reseller/v1/bank-account/verify-async \
    "{\"bank_account\":\"$1\",\"ifsc\":\"$2\",\"name\":\"$3\",\"user_id\":\"${ref//-/_}\"}"
  echo "reference (use for bank_status): $ref"
}
bank_status() {   # <reference>
  post /api/reseller/v1/bank-account/status "{\"user_id\":\"${1//-/_}\"}"
}

# ---- GSTIN ----
gstin_verify() { post /api/reseller/v1/tax/gstin/verify "{\"gstNumber\":\"$1\"}"; }

usage() {
  cat <<'EOF'
Commands:
  aadhaar_send_otp   <aadhaar>
  aadhaar_verify_otp <otp> <ref_id>
  pan_verify         <pan>
  rc_verify          <vehicle_registration_number>
  dl_verify          <driving_license_number> <dob YYYY-MM-DD>
  ifsc_verify        <ifsc>
  bank_verify        <account> <ifsc> <name> [reference]
  bank_status        <reference>
  gstin_verify       <gst_number>

Example: ./banku_api.sh rc_verify MH12AB1234
EOF
}

cmd="${1:-}"
case "$cmd" in
  aadhaar_send_otp|aadhaar_verify_otp|pan_verify|rc_verify|dl_verify|ifsc_verify|bank_verify|bank_status|gstin_verify)
    shift; "$cmd" "$@" ;;
  *) usage; exit 1 ;;
esac
