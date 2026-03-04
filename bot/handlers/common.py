from __future__ import annotations

import logging
from typing import Any

from aiogram import F, Router
from aiogram.filters import Command
from aiogram.fsm.context import FSMContext
from aiogram.types import CallbackQuery, Message

from bot.api_client import (
    ApiConnectionError,
    ApiResponseError,
    ApiUnauthorizedError,
    ApiValidationError,
)
from bot.keyboards import role_keyboard
from bot.states import RegistrationRole

router = Router(name="common")
logger = logging.getLogger(__name__)

SKIP_TOKENS = {"-", "skip", "o'tkazib yuborish", "otkazib yuborish", "yo'q", "yoq"}

FIELD_LABELS = {
    "telegram_id": "Telegram ID",
    "username": "Username",
    "name": "Ism",
    "lastname": "Familiya",
    "role": "Rol",
    "user_id": "Foydalanuvchi",
    "org_name": "Tashkilot nomi",
    "org_type": "Tashkilot turi",
    "region_id": "Hudud",
    "city": "Shahar",
    "district": "Tuman",
    "adress": "Manzil",
    "address": "Manzil",
    "org_contact": "Aloqa",
    "experience": "Tajriba",
    "salary_min": "Minimal maosh",
    "work_format": "Ish formati",
    "about_me": "Haqimda",
    "seekertype_id": "Lavozim turi",
    "seeker_type_id": "Lavozim turi",
    "subject_id": "Fan",
    "cv_file_path": "CV",
    "cv_file_id": "CV",
}


def normalize_optional_text(raw_text: str | None) -> str | None:
    if raw_text is None:
        return None

    value = raw_text.strip()
    if not value or value.lower() in SKIP_TOKENS:
        return None
    return value


def parse_optional_int(raw_text: str | None) -> int | None:
    value = normalize_optional_text(raw_text)
    if value is None:
        return None
    return int(value)


def format_validation_errors(errors: dict[str, Any]) -> str:
    if not errors:
        return "Ma'lumotlar tekshiruvdan o'tmadi."

    lines: list[str] = ["Quyidagi maydonlarda xato bor:"]
    for field, detail in errors.items():
        label = FIELD_LABELS.get(field, field.replace("_", " ").title())
        if isinstance(detail, list) and detail:
            lines.append(f"- {label}: {detail[0]}")
        elif isinstance(detail, str):
            lines.append(f"- {label}: {detail}")
        else:
            lines.append(f"- {label}: noto'g'ri qiymat")
    return "\n".join(lines)


async def send_api_error(message: Message, error: Exception) -> None:
    if isinstance(error, ApiUnauthorizedError):
        await message.answer("BOT_API_TOKEN noto'g'ri. Administratorga murojaat qiling.")
        return

    if isinstance(error, ApiValidationError):
        await message.answer(format_validation_errors(error.errors))
        return

    if isinstance(error, ApiConnectionError):
        await message.answer("Backend ishlamayapti. Keyinroq qayta urinib ko'ring.")
        return

    if isinstance(error, ApiResponseError):
        await message.answer(f"Backend xatolik qaytardi: {error.message}")
        return

    logger.exception("Unexpected bot error: %s", error)
    await message.answer("Kutilmagan xatolik yuz berdi. Qayta urinib ko'ring.")


async def prompt_role_selection(message: Message, state: FSMContext) -> None:
    await state.set_state(RegistrationRole.choosing)
    await message.answer(
        "Ro'yxatdan o'tish uchun rolni tanlang:",
        reply_markup=role_keyboard(),
    )


@router.callback_query(F.data == "nav:cancel")
async def on_cancel_callback(callback: CallbackQuery, state: FSMContext) -> None:
    await state.clear()
    await callback.answer()
    if callback.message:
        await callback.message.answer("Amal bekor qilindi. Davom etish uchun /start bosing.")


@router.message(Command("cancel"))
async def on_cancel_command(message: Message, state: FSMContext) -> None:
    await state.clear()
    await message.answer("Amal bekor qilindi. Davom etish uchun /start bosing.")

