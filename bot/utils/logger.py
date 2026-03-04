from __future__ import annotations

import logging
from pathlib import Path


def setup_logging(level: str = "INFO", log_dir: Path | None = None) -> None:
    target_dir = (log_dir or Path(__file__).resolve().parents[1] / "logs").resolve()
    target_dir.mkdir(parents=True, exist_ok=True)

    log_file = target_dir / "bot.log"
    numeric_level = getattr(logging, level.upper(), logging.INFO)

    formatter = logging.Formatter(
        fmt="%(asctime)s | %(levelname)s | %(name)s | %(message)s",
        datefmt="%Y-%m-%d %H:%M:%S",
    )

    stream_handler = logging.StreamHandler()
    stream_handler.setFormatter(formatter)

    file_handler = logging.FileHandler(log_file, encoding="utf-8")
    file_handler.setFormatter(formatter)

    root_logger = logging.getLogger()
    root_logger.setLevel(numeric_level)
    root_logger.handlers.clear()
    root_logger.addHandler(stream_handler)
    root_logger.addHandler(file_handler)

