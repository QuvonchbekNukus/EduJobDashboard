from __future__ import annotations

import asyncio
import os
import sys
from pathlib import Path

from aiogram import Bot, Dispatcher
from aiogram.client.default import DefaultBotProperties
from aiogram.client.session.aiohttp import AiohttpSession
from aiogram.enums import ParseMode
import certifi

if __package__ is None or __package__ == "":
    project_root = Path(__file__).resolve().parent.parent
    if str(project_root) not in sys.path:
        sys.path.insert(0, str(project_root))

from bot.api_client import BackendApiClient
from bot.config import load_settings
from bot.handlers import common_router, employer_router, seeker_router, start_router
from bot.utils.logger import setup_logging


async def main() -> None:
    os.environ.setdefault("SSL_CERT_FILE", certifi.where())
    os.environ.setdefault("REQUESTS_CA_BUNDLE", certifi.where())

    settings = load_settings()
    setup_logging(settings.log_level)

    session = AiohttpSession()
    if settings.telegram_disable_ssl_verify:
        session._connector_init["ssl"] = False

    bot = Bot(
        token=settings.bot_token,
        default=DefaultBotProperties(parse_mode=ParseMode.HTML),
        session=session,
    )
    dp = Dispatcher()

    api_client = BackendApiClient(settings)
    dp["api_client"] = api_client

    dp.include_router(common_router)
    dp.include_router(start_router)
    dp.include_router(employer_router)
    dp.include_router(seeker_router)

    await bot.delete_webhook(drop_pending_updates=True)

    try:
        await dp.start_polling(bot)
    finally:
        await api_client.close()
        await bot.session.close()


if __name__ == "__main__":
    asyncio.run(main())
