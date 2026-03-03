#!/bin/bash
# ============================================================
# Installatie script voor de auto-print achtergrondservice
# Uitvoeren als: sudo bash scripts/install-autoprint.sh
# ============================================================

set -e

APP_DIR="$(cd "$(dirname "$0")/.." && pwd)"
SERVICE_NAME="restaurant-autoprint"
SERVICE_FILE="/etc/systemd/system/${SERVICE_NAME}.service"

echo "=== Restaurant Auto-Print Service Installatie ==="
echo "App directory: ${APP_DIR}"
echo ""

# Controleer of we als root draaien
if [ "$EUID" -ne 0 ]; then
    echo "Voer dit script uit als root: sudo bash scripts/install-autoprint.sh"
    exit 1
fi

# Controleer of php beschikbaar is
if ! command -v php &> /dev/null; then
    echo "FOUT: PHP is niet gevonden. Installeer PHP eerst."
    exit 1
fi

# Controleer of lp beschikbaar is
if ! command -v lp &> /dev/null; then
    echo "WAARSCHUWING: 'lp' print commando niet gevonden."
    echo "Installeer CUPS: sudo apt install cups"
    echo ""
fi

# Toon beschikbare printers
echo "Beschikbare printers:"
lpstat -p 2>/dev/null || echo "  (geen printers gevonden of CUPS niet gestart)"
echo ""

# Vraag om printernaam
read -p "Printernaam (leeg = standaardprinter): " PRINTER_NAME

# Bouw service bestand
cat > "${SERVICE_FILE}" << EOF
[Unit]
Description=Restaurant Auto-Print Service
After=network.target
Wants=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=${APP_DIR}
ExecStart=/usr/bin/php ${APP_DIR}/artisan orders:auto-print --interval=15$([ -n "$PRINTER_NAME" ] && echo " --printer=${PRINTER_NAME}")
Restart=always
RestartSec=5
StandardOutput=journal
StandardError=journal
SyslogIdentifier=restaurant-autoprint

[Install]
WantedBy=multi-user.target
EOF

echo "Service bestand aangemaakt: ${SERVICE_FILE}"

# Activeer en start de service
systemctl daemon-reload
systemctl enable "${SERVICE_NAME}"
systemctl start "${SERVICE_NAME}"

echo ""
echo "=== Installatie compleet ==="
echo ""
echo "Handige commando's:"
echo "  Status:     sudo systemctl status ${SERVICE_NAME}"
echo "  Logs:       sudo journalctl -u ${SERVICE_NAME} -f"
echo "  Stoppen:    sudo systemctl stop ${SERVICE_NAME}"
echo "  Herstarten: sudo systemctl restart ${SERVICE_NAME}"
echo ""
echo "De service start automatisch na herstart van de computer."
