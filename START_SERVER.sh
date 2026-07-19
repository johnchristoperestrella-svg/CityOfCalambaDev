#!/bin/bash
# Calamba PopDev Resource Network - Linux/Mac Startup Script

echo "════════════════════════════════════════════════════════════"
echo "   CALAMBA POPDEV RESOURCE NETWORK - STARTUP"
echo "════════════════════════════════════════════════════════════"
echo ""
echo "Starting PHP Development Server..."
echo ""

cd "$(dirname "$0")/public"
php -S localhost:8000 -t .
