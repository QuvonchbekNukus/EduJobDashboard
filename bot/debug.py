#!/usr/bin/env python3
"""Bot debugging and testing script."""

import sys
import os
from pathlib import Path

# Add project root to path
project_root = Path(__file__).resolve().parent.parent
if str(project_root) not in sys.path:
    sys.path.insert(0, str(project_root))

print("=" * 60)
print("BOT DEBUG TOOL")
print("=" * 60)

# Step 1: Check Python version
print("\n1️⃣ Python Version Check:")
print(f"   Python: {sys.version}")
print(f"   Executable: {sys.executable}")

# Step 2: Check dependencies
print("\n2️⃣ Checking Dependencies:")
try:
    import aiogram
    print(f"   ✅ aiogram {aiogram.__version__}")
except ImportError as e:
    print(f"   ❌ aiogram: {e}")

try:
    import httpx
    print(f"   ✅ httpx {httpx.__version__}")
except ImportError as e:
    print(f"   ❌ httpx: {e}")

try:
    import dotenv
    print(f"   ✅ python-dotenv")
except ImportError as e:
    print(f"   ❌ python-dotenv: {e}")

# Step 3: Check .env file
print("\n3️⃣ Environment Configuration:")
env_path = Path(__file__).parent / ".env"
if env_path.exists():
    print(f"   ✅ .env file exists: {env_path}")
    with open(env_path) as f:
        for line in f:
            if line.strip() and not line.startswith('#'):
                key = line.split('=')[0]
                print(f"      - {key}")
else:
    print(f"   ❌ .env file NOT found: {env_path}")

# Step 4: Try loading config
print("\n4️⃣ Loading Configuration:")
try:
    from bot.config import load_settings
    settings = load_settings()
    print(f"   ✅ Config loaded successfully")
    print(f"   - Bot Token: {settings.bot_token[:20]}...")
    print(f"   - API URL: {settings.api_base_url}")
    print(f"   - Bot API Token: {settings.bot_api_token}")
except Exception as e:
    print(f"   ❌ Config error: {e}")
    import traceback
    traceback.print_exc()
    sys.exit(1)

# Step 5: Try importing handlers
print("\n5️⃣ Importing Handlers:")
try:
    from bot.handlers import common_router, start_router, employer_router, seeker_router
    print(f"   ✅ All routers imported")
    print(f"   - common_router: {type(common_router)}")
    print(f"   - start_router: {type(start_router)}")
    print(f"   - employer_router: {type(employer_router)}")
    print(f"   - seeker_router: {type(seeker_router)}")
except Exception as e:
    print(f"   ❌ Router import error: {e}")
    import traceback
    traceback.print_exc()
    sys.exit(1)

# Step 6: Test API client
print("\n6️⃣ Testing API Client:")
try:
    from bot.api_client import BackendApiClient
    import asyncio
    
    async def test_api():
        client = BackendApiClient(settings)
        try:
            regions = await client.list_regions()
            print(f"   ✅ API connected. Regions: {len(regions)}")
            for r in regions[:3]:
                print(f"      - {r.get('name')}")
        except Exception as e:
            print(f"   ⚠️  API error (may be running): {e}")
        finally:
            await client.close()
    
    asyncio.run(test_api())
except Exception as e:
    print(f"   ⚠️  API Client error: {e}")

print("\n" + "=" * 60)
print("✅ All checks completed! Bot should start now.")
print("=" * 60)
print("\nTo start the bot, run:\n   python app.py\n")
