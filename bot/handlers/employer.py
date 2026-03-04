from __future__ import annotations

import logging

from aiogram import F, Router
from aiogram.filters import StateFilter
from aiogram.fsm.context import FSMContext
from aiogram.types import CallbackQuery, Message

from bot.api_client import BackendApiClient
from bot.handlers.common import normalize_optional_text, prompt_role_selection, send_api_error
from bot.keyboards import nav_keyboard, org_type_keyboard, regions_keyboard
from bot.states import EmployerForm

router = Router(name="employer")
logger = logging.getLogger(__name__)


async def prompt_employer_org_name(message: Message, state: FSMContext) -> None:
    await state.set_state(EmployerForm.org_name)
    await message.answer(
        "Tashkilot nomini kiriting:",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_employer_org_type(message: Message, state: FSMContext) -> None:
    await state.set_state(EmployerForm.org_type)
    await message.answer(
        "Tashkilot turini tanlang:",
        reply_markup=org_type_keyboard(),
    )


async def prompt_employer_region(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    regions = await api_client.list_regions()

    await state.set_state(EmployerForm.region)
    if regions:
        await message.answer(
            "Hududni tanlang:",
            reply_markup=regions_keyboard(regions, "emp_region"),
        )
        return

    await message.answer(
        "Hududlar ro'yxati topilmadi. Region ID ni raqamda kiriting:",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_employer_city(message: Message, state: FSMContext) -> None:
    await state.set_state(EmployerForm.city)
    await message.answer(
        "Shaharni kiriting (ixtiyoriy, '-' yuborsangiz skip bo'ladi):",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_employer_district(message: Message, state: FSMContext) -> None:
    await state.set_state(EmployerForm.district)
    await message.answer(
        "Tumanni kiriting (ixtiyoriy, '-' yuborsangiz skip bo'ladi):",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_employer_address(message: Message, state: FSMContext) -> None:
    await state.set_state(EmployerForm.address)
    await message.answer(
        "Manzilni kiriting (ixtiyoriy, '-' yuborsangiz skip bo'ladi):",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


async def prompt_employer_contact(message: Message, state: FSMContext) -> None:
    await state.set_state(EmployerForm.org_contact)
    await message.answer(
        "Aloqa ma'lumotini kiriting (telefon yoki username):",
        reply_markup=nav_keyboard(show_back=True, show_cancel=True),
    )


@router.message(EmployerForm.org_name)
async def employer_org_name_input(message: Message, state: FSMContext) -> None:
    org_name = normalize_optional_text(message.text)
    if org_name is None:
        await message.answer("Tashkilot nomi majburiy. Iltimos, qayta kiriting.")
        return

    await state.update_data(org_name=org_name)
    await prompt_employer_org_type(message, state)


@router.callback_query(EmployerForm.org_type, F.data.startswith("org_type:"))
async def employer_org_type_input(callback: CallbackQuery, state: FSMContext, api_client: BackendApiClient) -> None:
    if callback.message is None:
        await callback.answer()
        return

    org_type = callback.data.split(":", maxsplit=1)[1]
    if org_type not in {"learning_center", "school", "kindergarten"}:
        await callback.answer("Noto'g'ri tur.", show_alert=True)
        return

    await callback.answer()
    await state.update_data(org_type=org_type)

    try:
        await prompt_employer_region(callback.message, state, api_client)
    except Exception as exc:
        logger.exception("Failed to load regions for employer flow: %s", exc)
        await send_api_error(callback.message, exc)


@router.callback_query(EmployerForm.region, F.data.startswith("emp_region:"))
async def employer_region_callback(callback: CallbackQuery, state: FSMContext) -> None:
    if callback.message is None:
        await callback.answer()
        return

    raw_region_id = callback.data.split(":", maxsplit=1)[1]
    if not raw_region_id.isdigit():
        await callback.answer("Noto'g'ri region.", show_alert=True)
        return

    await callback.answer()
    await state.update_data(region_id=int(raw_region_id))
    await prompt_employer_city(callback.message, state)


@router.message(EmployerForm.region)
async def employer_region_text(message: Message, state: FSMContext) -> None:
    value = (message.text or "").strip()
    if not value.isdigit():
        await message.answer("Region ID raqam bo'lishi kerak.")
        return

    await state.update_data(region_id=int(value))
    await prompt_employer_city(message, state)


@router.message(EmployerForm.city)
async def employer_city_input(message: Message, state: FSMContext) -> None:
    await state.update_data(city=normalize_optional_text(message.text))
    await prompt_employer_district(message, state)


@router.message(EmployerForm.district)
async def employer_district_input(message: Message, state: FSMContext) -> None:
    await state.update_data(district=normalize_optional_text(message.text))
    await prompt_employer_address(message, state)


@router.message(EmployerForm.address)
async def employer_address_input(message: Message, state: FSMContext) -> None:
    await state.update_data(address=normalize_optional_text(message.text))
    await prompt_employer_contact(message, state)


@router.message(EmployerForm.org_contact)
async def employer_contact_input(
    message: Message,
    state: FSMContext,
    api_client: BackendApiClient,
) -> None:
    org_contact = normalize_optional_text(message.text)
    if org_contact is None:
        await message.answer("Aloqa ma'lumoti majburiy. Qayta kiriting.")
        return

    await state.update_data(org_contact=org_contact)
    data = await state.get_data()
    user_id = data.get("user_id")
    if not user_id:
        await message.answer("Foydalanuvchi aniqlanmadi. Qayta boshlash uchun /start bosing.")
        await state.clear()
        return

    payload = {
        "user_id": user_id,
        "org_name": data.get("org_name"),
        "org_type": data.get("org_type"),
        "region_id": data.get("region_id"),
        "city": data.get("city"),
        "district": data.get("district"),
        "address": data.get("address"),
        "org_contact": org_contact,
    }

    try:
        await api_client.upsert_employer(payload)
    except Exception as exc:
        logger.exception("Failed to save employer profile: %s", exc)
        await send_api_error(message, exc)
        return

    await state.clear()
    await message.answer("Ro'yxatdan o'tdingiz ✅")


@router.callback_query(
    F.data == "nav:back",
    StateFilter(
        EmployerForm.org_name,
        EmployerForm.org_type,
        EmployerForm.region,
        EmployerForm.city,
        EmployerForm.district,
        EmployerForm.address,
        EmployerForm.org_contact,
    ),
)
async def employer_back(
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
        if current_state == EmployerForm.org_name.state:
            await prompt_role_selection(callback.message, state)
        elif current_state == EmployerForm.org_type.state:
            await prompt_employer_org_name(callback.message, state)
        elif current_state == EmployerForm.region.state:
            await prompt_employer_org_type(callback.message, state)
        elif current_state == EmployerForm.city.state:
            await prompt_employer_region(callback.message, state, api_client)
        elif current_state == EmployerForm.district.state:
            await prompt_employer_city(callback.message, state)
        elif current_state == EmployerForm.address.state:
            await prompt_employer_district(callback.message, state)
        elif current_state == EmployerForm.org_contact.state:
            await prompt_employer_address(callback.message, state)
    except Exception as exc:
        logger.exception("Back navigation failed in employer flow: %s", exc)
        await send_api_error(callback.message, exc)

