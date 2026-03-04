from __future__ import annotations

import logging

from aiogram import F, Router
from aiogram.filters import CommandStart
from aiogram.fsm.context import FSMContext
from aiogram.types import CallbackQuery, Message

from bot.api_client import BackendApiClient
from bot.handlers.common import prompt_role_selection, send_api_error
from bot.handlers.employer import prompt_employer_org_name
from bot.handlers.seeker import prompt_seeker_region
from bot.states import RegistrationRole

router = Router(name="start")
logger = logging.getLogger(__name__)


@router.message(CommandStart())
async def start_registration(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    await state.clear()

    user = message.from_user
    if user is None:
        await message.answer("Foydalanuvchi ma'lumoti topilmadi.")
        return

    payload = {
        "telegram_id": user.id,
        "username": user.username,
        "name": user.first_name,
        "lastname": user.last_name,
    }

    try:
        backend_user = await api_client.upsert_user(payload)
    except Exception as exc:
        logger.exception("Failed to upsert user on /start: %s", exc)
        await send_api_error(message, exc)
        return

    await state.update_data(user_id=backend_user.get("id"))
    await message.answer("Assalomu alaykum. EduJob botiga xush kelibsiz.")
    await prompt_role_selection(message, state)


@router.callback_query(RegistrationRole.choosing, F.data.startswith("role:"))
async def choose_role(
    callback: CallbackQuery,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    if callback.message is None:
        await callback.answer()
        return

    selected_role = callback.data.split(":", maxsplit=1)[1]
    if selected_role not in {"employer", "seeker"}:
        await callback.answer("Noto'g'ri rol tanlandi.", show_alert=True)
        return

    user = callback.from_user
    payload = {
        "telegram_id": user.id,
        "username": user.username,
        "name": user.first_name,
        "lastname": user.last_name,
        "role": selected_role,
    }

    try:
        backend_user = await api_client.upsert_user(payload)
    except Exception as exc:
        logger.exception("Failed to upsert user with role: %s", exc)
        await callback.answer()
        await send_api_error(callback.message, exc)
        return

    await callback.answer()
    await state.update_data(
        role=selected_role,
        user_id=backend_user.get("id"),
    )

    if selected_role == "employer":
        await prompt_employer_org_name(callback.message, state)
        return

    await prompt_seeker_region(callback.message, state, api_client)

