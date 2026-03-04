from __future__ import annotations

from typing import Iterable

from aiogram.types import InlineKeyboardMarkup
from aiogram.utils.keyboard import InlineKeyboardBuilder


def nav_keyboard(*, show_back: bool = True, show_cancel: bool = True) -> InlineKeyboardMarkup:
    builder = InlineKeyboardBuilder()
    if show_back:
        builder.button(text="Back", callback_data="nav:back")
    if show_cancel:
        builder.button(text="Cancel", callback_data="nav:cancel")
    builder.adjust(2)
    return builder.as_markup()


def role_keyboard() -> InlineKeyboardMarkup:
    builder = InlineKeyboardBuilder()
    builder.button(text="Ish beruvchi", callback_data="role:employer")
    builder.button(text="Ish qidiruvchi", callback_data="role:seeker")
    builder.adjust(1)
    builder.button(text="Cancel", callback_data="nav:cancel")
    return builder.as_markup()


def org_type_keyboard() -> InlineKeyboardMarkup:
    builder = InlineKeyboardBuilder()
    builder.button(text="O'quv markazi", callback_data="org_type:learning_center")
    builder.button(text="Maktab", callback_data="org_type:school")
    builder.button(text="Bog'cha", callback_data="org_type:kindergarten")
    builder.adjust(1)
    builder.attach(InlineKeyboardBuilder.from_markup(nav_keyboard()))
    return builder.as_markup()


def work_format_keyboard() -> InlineKeyboardMarkup:
    builder = InlineKeyboardBuilder()
    builder.button(text="Online", callback_data="work_format:online")
    builder.button(text="Offline", callback_data="work_format:offline")
    builder.button(text="Hybrid", callback_data="work_format:hybrid")
    builder.adjust(1)
    builder.attach(InlineKeyboardBuilder.from_markup(nav_keyboard()))
    return builder.as_markup()


def regions_keyboard(regions: Iterable[dict], callback_prefix: str) -> InlineKeyboardMarkup:
    builder = InlineKeyboardBuilder()
    for region in regions:
        region_id = region.get("id")
        if region_id is None:
            continue
        title = str(region.get("name") or f"Region {region_id}")
        builder.button(text=title, callback_data=f"{callback_prefix}:{region_id}")
    builder.adjust(2)
    builder.attach(InlineKeyboardBuilder.from_markup(nav_keyboard()))
    return builder.as_markup()


def seekers_type_keyboard(types: Iterable[dict]) -> InlineKeyboardMarkup:
    builder = InlineKeyboardBuilder()
    for item in types:
        item_id = item.get("id")
        if item_id is None:
            continue
        title = str(item.get("label") or item.get("name") or item_id)
        builder.button(text=title, callback_data=f"seek_type:{item_id}")
    builder.adjust(1)
    builder.attach(InlineKeyboardBuilder.from_markup(nav_keyboard()))
    return builder.as_markup()


def subjects_keyboard(subjects: Iterable[dict]) -> InlineKeyboardMarkup:
    builder = InlineKeyboardBuilder()
    for item in subjects:
        item_id = item.get("id")
        if item_id is None:
            continue
        title = str(item.get("label") or item.get("name") or item_id)
        builder.button(text=title, callback_data=f"seek_subject:{item_id}")
    builder.button(text="Skip", callback_data="seek_subject:skip")
    builder.adjust(1)
    builder.attach(InlineKeyboardBuilder.from_markup(nav_keyboard()))
    return builder.as_markup()

