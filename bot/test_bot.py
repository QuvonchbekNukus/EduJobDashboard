#!/usr/bin/env python3
"""Minimal test bot to verify Telegram connectivity."""

import asyncio
import os
import sys
from pathlib import Path

# Add project root to path
project_root = Path(__file__).resolve().parent.parent
if str(project_root) not in sys.path:
    sys.path.insert(0, str(project_root))

from aiogram import Bot, Dispatcher, F, Router
from aiogram.enums import ParseMode
from aiogram.filters import CommandStart
from aiogram.types import Message
from dotenv import load_dotenv

# Load environment
env_path = Path(__file__).parent / ".env"
load_dotenv(env_path)

BOT_TOKEN = os.getenv("BOT_TOKEN")

if not BOT_TOKEN:
    print("ERROR: BOT_TOKEN not found in .env")
    sys.exit(1)

print(f"Bot Token: {BOT_TOKEN[:20]}...")

# Create router and handlers
router = Router()

@router.message(CommandStart())
async def start_handler(message: Message) -> None:
    """Handle /start command."""
    print(f"[DEBUG] /start handler called by {message.from_user.username}")
    await message.answer(
        f"🤖 Test bot working!\n"
        f"Telegram ID: {message.from_user.id}\n"
        f"Name: {message.from_user.first_name}\n"
        f"Username: {message.from_user.username}"
    )
    print(f"[DEBUG] Sent response to {message.from_user.username}")

@router.message()
async def echo_handler(message: Message) -> None:
    """Echo any message."""
    print(f"[DEBUG] Message from {message.from_user.username}: {message.text[:50]}")
    await message.answer(f"Echo: {message.text}")
    print(f"[DEBUG] Sent echo response")

async def main() -> None:
    """Main bot function."""
    print("[INFO] Starting test bot...")
    
    # Create bot and dispatcher
    bot = Bot(token=BOT_TOKEN, parse_mode=ParseMode.HTML)
    dp = Dispatcher()
    
    # Include router
    dp.include_router(router)
    
    print("[INFO] Bot connected, starting polling...")
    
    try:
        await dp.start_polling(bot, allowed_updates=dp.resolve_used_update_types())
    except Exception as e:
        print(f"[ERROR] {e}")
    finally:
        await bot.session.close()

if __name__ == "__main__":
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        print("\n[INFO] Bot stopped")
    except Exception as e:
        print(f"[ERROR] Unexpected error: {e}")
        import traceback
        traceback.print_exc()
