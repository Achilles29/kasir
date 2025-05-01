#!/bin/bash

# Aktifkan virtual environment
source /opt/lampp/htdocs/kasir/printer_server/venv/bin/activate

# Jalankan script sync kamu
python3 /opt/lampp/htdocs/kasir/sync/auto_sync.py >> /opt/lampp/htdocs/kasir/sync/auto_sync.log 2>&1
