from __future__ import annotations

import os
from dataclasses import dataclass
from pathlib import Path

from dotenv import load_dotenv


def _normalize_path(value: str, default: str) -> str:
    path = (value or default).strip()
    return path.lstrip("/")


@dataclass(frozen=True)
class Settings:
    bot_token: str
    api_base_url: str
    bot_api_token: str
    users_upsert_path: str
    employers_upsert_path: str
    seekers_upsert_path: str
    regions_path: str
    seekers_types_path: str
    subjects_path: str
    api_timeout: float
    log_level: str
    download_dir: Path
    telegram_disable_ssl_verify: bool


def load_settings(env_path: Path | None = None) -> Settings:
    base_dir = Path(__file__).resolve().parent
    dotenv_path = env_path or (base_dir / ".env")
    load_dotenv(dotenv_path, override=True)

    bot_token = os.getenv("BOT_TOKEN", "").strip()
    api_base_url = os.getenv("API_BASE_URL", "http://127.0.0.1:8000/api").strip().rstrip("/")
    bot_api_token = os.getenv("BOT_API_TOKEN", "").strip()

    if not bot_token:
        raise ValueError("BOT_TOKEN is required in bot/.env")

    if not bot_api_token:
        raise ValueError("BOT_API_TOKEN is required in bot/.env")

    return Settings(
        bot_token=bot_token,
        api_base_url=api_base_url,
        bot_api_token=bot_api_token,
        users_upsert_path=_normalize_path(os.getenv("API_USERS_UPSERT_PATH", ""), "bot/users/upsert"),
        employers_upsert_path=_normalize_path(os.getenv("API_EMPLOYERS_UPSERT_PATH", ""), "bot/employers/upsert"),
        seekers_upsert_path=_normalize_path(os.getenv("API_SEEKERS_UPSERT_PATH", ""), "bot/seekers/upsert"),
        regions_path=_normalize_path(os.getenv("API_REGIONS_PATH", ""), "bot/regions"),
        seekers_types_path=_normalize_path(os.getenv("API_SEEKERS_TYPES_PATH", ""), "bot/seekers-types"),
        subjects_path=_normalize_path(os.getenv("API_SUBJECTS_PATH", ""), "bot/subjects"),
        api_timeout=float(os.getenv("API_TIMEOUT", "15")),
        log_level=os.getenv("LOG_LEVEL", "INFO"),
        download_dir=(base_dir / os.getenv("DOWNLOAD_DIR", "downloads")).resolve(),
        telegram_disable_ssl_verify=os.getenv("TELEGRAM_DISABLE_SSL_VERIFY", "0").strip() == "1",
    )
