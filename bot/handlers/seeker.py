from __future__ import annotations

import logging
from pathlib import Path

from aiogram import Bot, F, Router
from aiogram.filters import StateFilter
from aiogram.fsm.context import FSMContext
from aiogram.types import CallbackQuery, Message

from bot.api_client import BackendApiClient
from bot.handlers.common import (
    normalize_optional_text,
    parse_optional_int,
    prompt_role_selection,
    send_api_error,
)
from bot.keyboards import nav_keyboard, regions_keyboard, seekers_type_keyboard, subjects_keyboard, work_format_keyboard
from bot.states import SeekerForm

router = Router(name="seeker")
logger = logging.getLogger(__name__)


async def prompt_seeker_region(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    regions = await api_client.list_regions()

    await state.set_state(SeekerForm.region)
    if regions:
        await message.answer(
            "Hududni tanlang:",
            reply_markup=regions_keyboard(regions, "seek_region"),
        )
        return

    await message.answer(
        "Hududlar ro'yxati topilmadi. Region ID ni raqamda kiriting:",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_seeker_experience(message: Message, state: FSMContext) -> None:
    await state.set_state(SeekerForm.experience)
    await message.answer(
        "Tajribani kiriting (ixtiyoriy, '-' yuborsangiz skip):",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_seeker_salary(message: Message, state: FSMContext) -> None:
    await state.set_state(SeekerForm.salary_min)
    await message.answer(
        "Minimal maoshni kiriting (ixtiyoriy, '-' yuborsangiz skip):",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_seeker_work_format(message: Message, state: FSMContext) -> None:
    await state.set_state(SeekerForm.work_format)
    await message.answer(
        "Ish formatini tanlang:",
        reply_markup=work_format_keyboard(),
    )


async def prompt_seeker_about(message: Message, state: FSMContext) -> None:
    await state.set_state(SeekerForm.about_me)
    await message.answer(
        "O'zingiz haqingizda qisqa yozing (ixtiyoriy, '-' yuborsangiz skip):",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_seeker_type(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    types = await api_client.list_seekers_types()
    await state.set_state(SeekerForm.seeker_type)

    if types:
        await message.answer(
            "Lavozim turini tanlang:",
            reply_markup=seekers_type_keyboard(types),
        )
        return

    await message.answer(
        "Lavozim turlari topilmadi. seeker_type_id ni raqamda kiriting:",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_seeker_subject(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    subjects = await api_client.list_subjects()
    await state.set_state(SeekerForm.subject)

    if subjects:
        await message.answer(
            "Fan tanlang (ixtiyoriy):",
            reply_markup=subjects_keyboard(subjects),
        )
        return

    await message.answer(
        "subject_id ni yuboring yoki '-' yuborib skip qiling:",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_seeker_cv(message: Message, state: FSMContext) -> None:
    await state.set_state(SeekerForm.cv_file)
    await message.answer(
        "CV hujjat yuboring yoki '-' yuborib o'tkazing:",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def download_document_optional(
    bot: Bot,
    file_id: str,
    destination_dir: Path,
    filename: str,
) -> Path:
    destination_dir.mkdir(parents=True, exist_ok=True)
    destination = destination_dir / filename
    await bot.download(file_id, destination=destination)
    return destination


@router.callback_query(SeekerForm.region, F.data.startswith("seek_region:"))
async def seeker_region_callback(callback: CallbackQuery, state: FSMContext) -> None:
    if callback.message is None:
        await callback.answer()
        return

    raw_region_id = callback.data.split(":", maxsplit=1)[1]
    if not raw_region_id.isdigit():
        await callback.answer("Noto'g'ri region.", show_alert=True)
        return

    await callback.answer()
    await state.update_data(region_id=int(raw_region_id))
    await prompt_seeker_experience(callback.message, state)


@router.message(SeekerForm.region)
async def seeker_region_text(message: Message, state: FSMContext) -> None:
    value = (message.text or "").strip()
    if not value.isdigit():
        await message.answer("Region ID raqam bo'lishi kerak.")
        return

    await state.update_data(region_id=int(value))
    await prompt_seeker_experience(message, state)


@router.message(SeekerForm.experience)
async def seeker_experience(message: Message, state: FSMContext) -> None:
    await state.update_data(experience=normalize_optional_text(message.text))
    await prompt_seeker_salary(message, state)


@router.message(SeekerForm.salary_min)
async def seeker_salary(message: Message, state: FSMContext) -> None:
    try:
        salary = parse_optional_int(message.text)
    except ValueError:
        await message.answer("Minimal maosh raqam bo'lishi kerak yoki '-' yuboring.")
        return

    await state.update_data(salary_min=salary)
    await prompt_seeker_work_format(message, state)


@router.callback_query(SeekerForm.work_format, F.data.startswith("work_format:"))
async def seeker_work_format(callback: CallbackQuery, state: FSMContext) -> None:
    if callback.message is None:
        await callback.answer()
        return

    work_format = callback.data.split(":", maxsplit=1)[1]
    if work_format not in {"online", "offline", "hybrid"}:
        await callback.answer("Noto'g'ri format.", show_alert=True)
        return

    await callback.answer()
    await state.update_data(work_format=work_format)
    await prompt_seeker_about(callback.message, state)


@router.message(SeekerForm.about_me)
async def seeker_about(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    await state.update_data(about_me=normalize_optional_text(message.text))
    try:
        await prompt_seeker_type(message, state, api_client)
    except Exception as exc:
        logger.exception("Failed to load seekers types: %s", exc)
        await send_api_error(message, exc)


@router.callback_query(SeekerForm.seeker_type, F.data.startswith("seek_type:"))
async def seeker_type_callback(
    callback: CallbackQuery,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    if callback.message is None:
        await callback.answer()
        return

    raw_type_id = callback.data.split(":", maxsplit=1)[1]
    if not raw_type_id.isdigit():
        await callback.answer("Noto'g'ri qiymat.", show_alert=True)
        return

    await callback.answer()
    await state.update_data(seeker_type_id=int(raw_type_id))
    try:
        await prompt_seeker_subject(callback.message, state, api_client)
    except Exception as exc:
        logger.exception("Failed to load subjects: %s", exc)
        await send_api_error(callback.message, exc)


@router.message(SeekerForm.seeker_type)
async def seeker_type_text(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    value = (message.text or "").strip()
    if not value.isdigit():
        await message.answer("seeker_type_id raqam bo'lishi kerak.")
        return

    await state.update_data(seeker_type_id=int(value))
    try:
        await prompt_seeker_subject(message, state, api_client)
    except Exception as exc:
        logger.exception("Failed to load subjects: %s", exc)
        await send_api_error(message, exc)


@router.callback_query(SeekerForm.subject, F.data.startswith("seek_subject:"))
async def seeker_subject_callback(callback: CallbackQuery, state: FSMContext) -> None:
    if callback.message is None:
        await callback.answer()
        return

    raw_subject = callback.data.split(":", maxsplit=1)[1]
    await callback.answer()

    if raw_subject == "skip":
        await state.update_data(subject_id=None)
    elif raw_subject.isdigit():
        await state.update_data(subject_id=int(raw_subject))
    else:
        await callback.message.answer("Noto'g'ri subject qiymati.")
        return

    await prompt_seeker_cv(callback.message, state)


@router.message(SeekerForm.subject)
async def seeker_subject_text(message: Message, state: FSMContext) -> None:
    value = normalize_optional_text(message.text)
    if value is None:
        await state.update_data(subject_id=None)
        await prompt_seeker_cv(message, state)
        return

    if not value.isdigit():
        await message.answer("subject_id raqam bo'lishi kerak yoki '-' yuboring.")
        return

    await state.update_data(subject_id=int(value))
    await prompt_seeker_cv(message, state)


@router.message(SeekerForm.cv_file)
async def seeker_cv_file(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    cv_file_id: str | None = None

    if message.document:
        cv_file_id = message.document.file_id
    else:
        value = normalize_optional_text(message.text)
        if value is not None:
            await message.answer("CV uchun hujjat yuboring yoki '-' yuboring.")
            return

    await state.update_data(cv_file_id=cv_file_id)
    data = await state.get_data()
    user_id = data.get("user_id")
    if not user_id:
        await message.answer("Foydalanuvchi aniqlanmadi. Qayta boshlash uchun /start bosing.")
        await state.clear()
        return

    payload = {
        "user_id": user_id,
        "region_id": data.get("region_id"),
        "experience": data.get("experience"),
        "salary_min": data.get("salary_min"),
        "work_format": data.get("work_format"),
        "about_me": data.get("about_me"),
        "seeker_type_id": data.get("seeker_type_id"),
        "subject_id": data.get("subject_id"),
        "cv_file_id": cv_file_id,
    }

    try:
        await api_client.upsert_seeker(payload)
    except Exception as exc:
        logger.exception("Failed to save seeker profile: %s", exc)
        await send_api_error(message, exc)
        return

    await state.clear()
    await message.answer("Ro'yxatdan o'tdingiz ✅")


@router.callback_query(
    F.data == "nav:back",
    StateFilter(
        SeekerForm.region,
        SeekerForm.experience,
        SeekerForm.salary_min,
        SeekerForm.work_format,
        SeekerForm.about_me,
        SeekerForm.seeker_type,
        SeekerForm.subject,
        SeekerForm.cv_file,
    ),
)
async def seeker_back(
    callback: CallbackQuery,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    if callback.message is None:
        await callback.answer()
        return

    current_state = await state.get_state()
    await callback.answer()

    try:
        if current_state == SeekerForm.region.state:
            await prompt_role_selection(callback.message, state)
        elif current_state == SeekerForm.experience.state:
            await prompt_seeker_region(callback.message, state, api_client)
        elif current_state == SeekerForm.salary_min.state:
            await prompt_seeker_experience(callback.message, state)
        elif current_state == SeekerForm.work_format.state:
            await prompt_seeker_salary(callback.message, state)
        elif current_state == SeekerForm.about_me.state:
            await prompt_seeker_work_format(callback.message, state)
        elif current_state == SeekerForm.seeker_type.state:
            await prompt_seeker_about(callback.message, state)
        elif current_state == SeekerForm.subject.state:
            await prompt_seeker_type(callback.message, state, api_client)
        elif current_state == SeekerForm.cv_file.state:
            await prompt_seeker_subject(callback.message, state, api_client)
    except Exception as exc:
        logger.exception("Back navigation failed in seeker flow: %s", exc)
        await send_api_error(callback.message, exc)

