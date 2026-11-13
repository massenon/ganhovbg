#!/usr/bin/env bash
# Mesure locale (Lighthouse) du LCP reel d'une URL, ex. la production.
# Usage : outils/mesure_performance.sh [URL] [mobile|desktop]
# Prerequis : Node.js ; Lighthouse est lance via npx (aucune installation globale).
set -euo pipefail
URL="${1:-https://ganho.app/}"
MODE="${2:-mobile}"
SORTIE="$(dirname "$0")/rapports"
mkdir -p "$SORTIE"
HORO="$(date +%Y%m%d-%H%M%S)"
FICHIER="$SORTIE/lighthouse-$HORO"
PRESET=()
[ "$MODE" = "desktop" ] && PRESET=(--preset=desktop)
npx --yes lighthouse@12 "$URL" "${PRESET[@]}" \
  --only-categories=performance,accessibility,best-practices,seo,pwa \
  --output=json --output=html --output-path="$FICHIER" \
  --chrome-flags="--headless=new --no-sandbox" --quiet
node -e '
const r=require(process.argv[1]+".report.json");
const a=r.audits, c=r.categories;
const lcp=a["largest-contentful-paint"].numericValue;
console.log("URL        :", r.finalDisplayedUrl);
console.log("LCP        :", (lcp/1000).toFixed(2), "s  (objectif < 2,0 s)");
console.log("FCP        :", (a["first-contentful-paint"].numericValue/1000).toFixed(2), "s");
console.log("CLS        :", a["cumulative-layout-shift"].numericValue.toFixed(3));
console.log("TBT        :", Math.round(a["total-blocking-time"].numericValue), "ms");
for (const k of Object.keys(c)) console.log(k.padEnd(15), Math.round((c[k].score||0)*100));
console.log("Rapport    :", process.argv[1]+".report.html");
process.exit(lcp<2000?0:1);
' "$FICHIER"
