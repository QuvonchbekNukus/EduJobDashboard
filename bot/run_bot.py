#!/usr/bin/env python3
"""Bot starter with logging."""

import subprocess
import sys
import os
from datetime import datetime
from pathlib import Path

log_dir = Path(__file__).parent / "logs"
log_dir.mkdir(exist_ok=True)

log_file = log_dir / f"bot_{datetime.now().strftime('%Y%m%d_%H%M%S')}.log"

print(f"Starting bot... (logs: {log_file})")

with open(log_file, 'w') as f:
    f.write(f"Bot started at {datetime.now()}\n")
    f.write(f"Python: {sys.version}\n")
    f.write(f"Working dir: {os.getcwd()}\n")
    f.write("=" * 60 + "\n\n")
    f.flush()
    
    try:
        # Run the bot
        result = subprocess.run(
            [sys.executable, str(Path(__file__).parent / "app.py")],
            stdout=f,
            stderr=subprocess.STDOUT,
            text=True
        )
    except KeyboardInterrupt:
        f.write("\nBot stopped by user\n")
    except Exception as e:
        f.write(f"\nError: {e}\n")

print(f"Bot logs saved to: {log_file}")
